<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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
            $rowData = [
                $order->order_id,
                $order->username,
                $order->gamepass_link ?? 'N/A',
                $order->game_type === 'Robux' ? $order->amount . ' Robux' : $order->product_name,
                $order->order_status ?? 'pending',
                $order->created_at->format('Y-m-d H:i:s')
            ];

            // Use Google Apps Script for automatic integration
            $scriptUrl = Setting::getValue('spreadsheet_script_url', '');
            if (empty($scriptUrl)) {
                Log::error('Google Apps Script URL not configured');
                return false;
            }
            
            $response = Http::timeout(30)->post($scriptUrl, [
                'action' => 'addOrder',
                'spreadsheetId' => $spreadsheetId,
                'data' => $rowData
            ], [
                'Content-Type' => 'application/json'
            ]);

            if ($response->successful()) {
                Log::info('Order added to spreadsheet successfully', [
                    'order_id' => $order->order_id,
                    'spreadsheet_id' => $spreadsheetId
                ]);
                return true;
            } else {
                Log::error('Failed to add order to spreadsheet', [
                    'order_id' => $order->order_id,
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                
                // Fallback: log data for manual copy
                $csvData = implode(',', array_map(function($field) {
                    return '"' . str_replace('"', '""', $field) . '"';
                }, $rowData));
                
                Log::info('=== FALLBACK SPREADSHEET DATA ===', [
                    'order_id' => $order->order_id,
                    'csv_format' => $csvData,
                    'instructions' => 'API failed, copy this data manually to spreadsheet'
                ]);
                
                return false;
            }

        } catch (\Exception $e) {
            Log::error('Failed to add order to spreadsheet', [
                'order_id' => $order->order_id ?? 'unknown',
                'error' => $e->getMessage()
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
