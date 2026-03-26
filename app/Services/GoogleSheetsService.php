<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class GoogleSheetsService
{
    public static function addOrderToSpreadsheet($order)
    {
        try {
            // Check if spreadsheet integration is enabled
            $spreadsheetEnabled = Setting::getValue('spreadsheet_enabled', '0') === '1';
            if (!$spreadsheetEnabled) {
                Log::info('Spreadsheet integration disabled');
                return false;
            }

            $spreadsheetUrl = Setting::getValue('spreadsheet_url', '');
            if (empty($spreadsheetUrl)) {
                Log::warning('Spreadsheet URL not configured');
                return false;
            }

            // Extract spreadsheet ID from URL
            $spreadsheetId = self::extractSpreadsheetId($spreadsheetUrl);
            if (!$spreadsheetId) {
                Log::error('Invalid spreadsheet URL format', ['url' => $spreadsheetUrl]);
                return false;
            }

            // Prepare data for spreadsheet
            // 1. Nama Produk: Robux untuk Robux, product_name untuk lainnya
            $productName = $order->game_type === 'Robux' ? 'Robux' : ($order->product_name ?? $order->game_type);
            
            // 2. Amount: format ribuan penuh sebagai STRING untuk mencegah Google Sheets mengubah format
            // CRITICAL: Kirim sebagai string dengan format ribuan penuh (1.000, 10.000, bukan 1 atau 1.3)
            // Google Sheets akan mengubah angka menjadi format singkat jika dikirim sebagai number
            // Apps Script akan set format sebagai text untuk mencegah auto-formatting
            $amountRaw = (int) ($order->amount ?? 0);
            $amount = number_format($amountRaw, 0, ',', '.'); // Format: 1.000, 10.000, 100.000
            // Kirim sebagai string biasa, Apps Script akan set format sebagai text
            
            // 3. Gamepass: link untuk gamepass, "Group" untuk group, "-" untuk produk other
            $gamepassInfo = '-';
            if ($order->game_type === 'Robux') {
                if ($order->purchase_method === 'group') {
                    $gamepassInfo = 'Group';
                } else {
                    $gamepassInfo = $order->gamepass_link ?? '-';
                }
            } else {
                // Untuk produk non-Robux, jika ada gamepass_link tetap tampilkan, jika tidak pakai strip
                $gamepassInfo = $order->gamepass_link ?? '-';
            }
            
            // 4. Date format: WIB timezone, format tanggal-bulan-tahun
            // Convert to WIB (Asia/Jakarta timezone, UTC+7)
            // Use Carbon to properly convert timezone
            $createdAt = Carbon::parse($order->created_at)->setTimezone('Asia/Jakarta');
            $dateFormatted = $createdAt->format('d-m-Y'); // Format: 11-04-2004
            $timeFormatted = $createdAt->format('H:i:s'); // Format: 14:30:45 (24 jam dengan detik)
            
            // Prepare row data for spreadsheet (9 columns with email)
            $rowData = [
                $order->order_id,
                $order->username,
                $order->email ?? '',    // Email (untuk menghubungi customer)
                $productName,           // Nama Produk
                $amount,                // Amount (format ribuan penuh sebagai string: 1.000, 10.000)
                $gamepassInfo,          // Gamepass (link/Group/-)
                $order->order_status ?? 'pending',
                $dateFormatted,         // Tanggal (dd-mm-yyyy)
                $timeFormatted          // Jam (HH:mm:ss)
            ];

            // Use Google Apps Script for automatic integration
            $scriptUrl = Setting::getValue('spreadsheet_script_url', '');
            if (empty($scriptUrl)) {
                Log::error('Google Apps Script URL not configured');
                return false;
            }
            
            // Add unique request ID to prevent duplicate processing
            // Include order ID, timestamp, and microsecond for better uniqueness in concurrent scenarios
            // This ensures each request has a truly unique ID even if multiple orders come in at the exact same time
            $microtime = microtime(true);
            $requestId = 'order_' . $order->id . '_' . $order->order_id . '_' . intval($microtime * 1000) . '_' . rand(1000, 9999);
            
            // Retry mechanism for concurrent requests (max 5 attempts with increasing delay)
            // Increased retries and delay for better handling of multiple concurrent requests (5+ orders)
            // Google Apps Script has rate limits, so we need more retries for concurrent scenarios
            $maxRetries = 5; // Increased from 3 to 5 for better concurrent handling
            $retryDelay = 1500; // Start with 1500ms (increased from 1000ms) for better concurrent handling
            
            for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
                try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'User-Agent' => 'Valtus-Admin-Panel/1.0',
                        'X-Request-ID' => $requestId,
                        'X-Attempt' => $attempt
            ])->timeout(30)->post($scriptUrl, [
                'action' => 'addOrder',
                'spreadsheetId' => $spreadsheetId,
                'data' => $rowData,
                'requestId' => $requestId,
                'timestamp' => now()->toISOString(),
                'orderId' => $order->order_id, // For deduplication check in Apps Script
                'checkDuplicate' => true // Flag to enable deduplication
            ]);

            if ($response->successful()) {
                $responseBody = $response->body();
                $responseData = null;
                
                // Try to parse JSON response
                if (!empty($responseBody)) {
                    try {
                        $responseData = json_decode($responseBody, true);
                    } catch (\Exception $jsonError) {
                        Log::warning('Failed to parse JSON response from spreadsheet API', [
                            'order_id' => $order->order_id,
                            'response_body' => substr($responseBody, 0, 500), // First 500 chars
                            'error' => $jsonError->getMessage()
                        ]);
                    }
                }
                
                // Check if response data is valid
                if ($responseData === null) {
                    Log::warning('Spreadsheet API returned null response (attempt ' . $attempt . ')', [
                        'order_id' => $order->order_id,
                        'status' => $response->status(),
                        'response_body' => substr($responseBody ?? '', 0, 500),
                        'request_id' => $requestId
                    ]);
                    
                    // If this is not the last attempt, retry
                    if ($attempt < $maxRetries) {
                        usleep($retryDelay * 1000);
                        $retryDelay *= 2;
                        continue;
                    }
                    
                    return false;
                }
                
                // Check if the response indicates success
                // Also check for duplicate flag - if order already exists, that's also success
                if (isset($responseData['success']) && $responseData['success']) {
                    $isDuplicate = isset($responseData['duplicate']) && $responseData['duplicate'] === true;
                    
                    if ($isDuplicate) {
                        Log::info('Order already exists in spreadsheet (duplicate skipped)', [
                            'order_id' => $order->order_id,
                            'spreadsheet_id' => $spreadsheetId,
                            'request_id' => $requestId,
                            'attempt' => $attempt
                        ]);
                    } else {
                        Log::info('Order added to spreadsheet successfully', [
                            'order_id' => $order->order_id,
                            'spreadsheet_id' => $spreadsheetId,
                            'request_id' => $requestId,
                            'attempt' => $attempt
                        ]);
                    }
                    return true; // Return true even for duplicates (order is in spreadsheet)
                } else {
                            // Response was successful but API returned error
                            Log::warning('Spreadsheet API returned error (attempt ' . $attempt . ')', [
                        'order_id' => $order->order_id,
                        'response' => $responseData,
                        'request_id' => $requestId
                    ]);
                            
                            // If this is not the last attempt, retry
                            if ($attempt < $maxRetries) {
                                usleep($retryDelay * 1000); // Convert ms to microseconds
                                $retryDelay *= 2; // Exponential backoff
                                continue;
                            }
                            
                    return false;
                }
            } else {
                        // HTTP error - retry if not last attempt
                        Log::warning('Failed to add order to spreadsheet (attempt ' . $attempt . ')', [
                    'order_id' => $order->order_id,
                    'status' => $response->status(),
                    'response' => $response->body(),
                            'request_id' => $requestId
                        ]);
                        
                        // If this is not the last attempt, retry with delay
                        if ($attempt < $maxRetries) {
                            usleep($retryDelay * 1000); // Convert ms to microseconds
                            $retryDelay *= 2; // Exponential backoff
                            continue;
                        }
                    }
                } catch (\Exception $e) {
                    Log::warning('Exception while adding order to spreadsheet (attempt ' . $attempt . ')', [
                        'order_id' => $order->order_id,
                        'error' => $e->getMessage(),
                        'request_id' => $requestId
                    ]);
                    
                    // If this is not the last attempt, retry with delay
                    if ($attempt < $maxRetries) {
                        usleep($retryDelay * 1000); // Convert ms to microseconds
                        $retryDelay *= 2; // Exponential backoff
                        continue;
                    }
                    
                    // Last attempt failed, log error
                    throw $e;
                }
            }
            
            // All retries failed
            Log::error('Failed to add order to spreadsheet after ' . $maxRetries . ' attempts', [
                'order_id' => $order->order_id,
                    'request_id' => $requestId
                ]);
                
                // Fallback: log data for manual copy
                $csvData = implode(',', array_map(function($field) {
                    return '"' . str_replace('"', '""', $field) . '"';
                }, $rowData));
                
                Log::info('=== FALLBACK SPREADSHEET DATA ===', [
                    'order_id' => $order->order_id,
                    'csv_format' => $csvData,
                    'request_id' => $requestId,
                'instructions' => 'API failed after retries, copy this data manually to spreadsheet'
                ]);
                
                return false;

        } catch (\Exception $e) {
            Log::error('Failed to add order to spreadsheet', [
                'order_id' => $order->order_id ?? 'unknown',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    private static function extractSpreadsheetId($url)
    {
        // Extract spreadsheet ID from Google Sheets URL
        // URL format: https://docs.google.com/spreadsheets/d/SPREADSHEET_ID/edit
        if (preg_match('/\/spreadsheets\/d\/([a-zA-Z0-9-_]+)/', $url, $matches)) {
            return $matches[1];
        }
        return null;
    }

    public static function testSpreadsheetConnection()
    {
        $spreadsheetUrl = Setting::getValue('spreadsheet_url', '');
        $apiKey = Setting::getValue('google_sheets_api_key', '');
        
        if (empty($spreadsheetUrl)) {
            return [
                'success' => false,
                'message' => 'Spreadsheet URL not configured'
            ];
        }

        if (empty($apiKey)) {
            return [
                'success' => false,
                'message' => 'Google Sheets API key not configured'
            ];
        }

        $spreadsheetId = self::extractSpreadsheetId($spreadsheetUrl);
        if (!$spreadsheetId) {
            return [
                'success' => false,
                'message' => 'Invalid spreadsheet URL format'
            ];
        }

        // Test connection by reading the spreadsheet
        $url = "https://sheets.googleapis.com/v4/spreadsheets/{$spreadsheetId}?key={$apiKey}";
        
        try {
            $response = Http::timeout(10)->get($url);
            
            if ($response->successful()) {
                return [
                    'success' => true,
                    'message' => 'Spreadsheet connection successful',
                    'spreadsheet_id' => $spreadsheetId
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Failed to connect to spreadsheet: ' . $response->body()
                ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Connection error: ' . $e->getMessage()
            ];
        }
    }
}
