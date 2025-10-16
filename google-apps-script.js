/**
 * Google Apps Script untuk menerima data dari Laravel Valtus
 * 
 * Cara Setup:
 * 1. Buka Google Sheets yang sudah dibuat
 * 2. Klik Extensions > Apps Script
 * 3. Hapus semua kode default dan ganti dengan kode ini
 * 4. Simpan dan deploy sebagai web app
 * 5. Set permissions: Anyone can access
 * 6. Copy URL deployment dan ganti di SpreadsheetService.php
 */

function doPost(e) {
  try {
    // Parse data dari Laravel
    const data = JSON.parse(e.postData.contents);
    
    if (data.action === 'addOrder') {
      return addOrderToSheet(data.spreadsheetId, data.data);
    }
    
    return ContentService
      .createTextOutput(JSON.stringify({success: false, message: 'Invalid action'}))
      .setMimeType(ContentService.MimeType.JSON);
      
  } catch (error) {
    return ContentService
      .createTextOutput(JSON.stringify({success: false, message: error.toString()}))
      .setMimeType(ContentService.MimeType.JSON);
  }
}

function addOrderToSheet(spreadsheetId, rowData) {
  try {
    // Buka spreadsheet
    const spreadsheet = SpreadsheetApp.openById(spreadsheetId);
    const sheet = spreadsheet.getActiveSheet();
    
    // Pastikan header ada (jika sheet kosong)
    if (sheet.getLastRow() === 0) {
      const headers = ['Order ID', 'Username', 'GamePass Link', 'Amount', 'Status', 'Date'];
      sheet.getRange(1, 1, 1, headers.length).setValues([headers]);
      
      // Format header
      const headerRange = sheet.getRange(1, 1, 1, headers.length);
      headerRange.setBackground('#E3F2FD');
      headerRange.setFontWeight('bold');
      headerRange.setFontColor('#000000');
    }
    
    // Tambahkan data baru
    const newRow = sheet.getLastRow() + 1;
    sheet.getRange(newRow, 1, 1, rowData.length).setValues([rowData]);
    
    // Format baris data
    const dataRange = sheet.getRange(newRow, 1, 1, rowData.length);
    dataRange.setBorder(true, true, true, true, true, true);
    
    // Auto-resize columns
    sheet.autoResizeColumns(1, rowData.length);
    
    return ContentService
      .createTextOutput(JSON.stringify({
        success: true, 
        message: 'Order added successfully',
        row: newRow
      }))
      .setMimeType(ContentService.MimeType.JSON);
      
  } catch (error) {
    return ContentService
      .createTextOutput(JSON.stringify({
        success: false, 
        message: 'Error adding order: ' + error.toString()
      }))
      .setMimeType(ContentService.MimeType.JSON);
  }
}

// Test function untuk debugging
function testAddOrder() {
  const testData = [
    'TEST123',
    'testuser',
    'https://www.roblox.com/game-pass/123456',
    '100 Robux',
    'pending',
    '2025-10-16 12:00:00'
  ];
  
  // Ganti dengan spreadsheet ID Anda
  const spreadsheetId = '16dIqq3qIjo3d6OpjxKakrCTCGBttdZ-0s1qouNo7WVQ';
  
  const result = addOrderToSheet(spreadsheetId, testData);
  console.log(result);
}
