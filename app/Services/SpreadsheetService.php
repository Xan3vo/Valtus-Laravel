<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SpreadsheetService
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

            // For now, just log the data and create a simple CSV format
            // In production, you would implement Google Sheets API here
            $csvData = implode(',', array_map(function($field) {
                return '"' . str_replace('"', '""', $field) . '"';
            }, $rowData));

            Log::info('Order data for spreadsheet (CSV format)', [
                'order_id' => $order->order_id,
                'spreadsheet_id' => $spreadsheetId,
                'csv_data' => $csvData,
                'raw_data' => $rowData
            ]);

            // TODO: Implement actual Google Sheets API integration
            // For now, we'll just log the data
            // You can manually copy the CSV data from logs and paste to spreadsheet

            return true;

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
        if (empty($spreadsheetUrl)) {
            return [
                'success' => false,
                'message' => 'Spreadsheet URL not configured'
            ];
        }

        $spreadsheetId = self::extractSpreadsheetId($spreadsheetUrl);
        if (!$spreadsheetId) {
            return [
                'success' => false,
                'message' => 'Invalid spreadsheet URL format'
            ];
        }

        // TODO: Test actual connection with Google Sheets API
        return [
            'success' => true,
            'message' => 'Spreadsheet URL is valid (API integration pending)',
            'spreadsheet_id' => $spreadsheetId
        ];
    }
}
