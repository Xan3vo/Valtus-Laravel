/**
 * Google Apps Script untuk Valtus - Otomatis Tambah Data
 * 
 * Setup:
 * 1. Buka Google Spreadsheet
 * 2. Extensions > Apps Script
 * 3. Ganti kode dengan ini
 * 4. Deploy > New deployment > Web app
 * 5. Execute as: Me, Who has access: Anyone
 * 6. Copy URL dan masukkan di admin panel
 */

function doPost(e) {
  try {
    const data = JSON.parse(e.postData.contents);
    
    if (data.action === 'addOrder') {
      return addOrderToSheet(data.spreadsheetId, data.data);
    }
    
    return createResponse(false, 'Invalid action');
  } catch (error) {
    return createResponse(false, error.toString());
  }
}

function addOrderToSheet(spreadsheetId, rowData) {
  try {
    // Buka spreadsheet
    const spreadsheet = SpreadsheetApp.openById(spreadsheetId);
    const sheet = spreadsheet.getActiveSheet();
    
    // Pastikan header ada
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
    
    return createResponse(true, 'Order added successfully', { row: newRow });
    
  } catch (error) {
    return createResponse(false, 'Error: ' + error.toString());
  }
}

function createResponse(success, message, data = null) {
  const response = {
    success: success,
    message: message
  };
  
  if (data) {
    response.data = data;
  }
  
  return ContentService
    .createTextOutput(JSON.stringify(response))
    .setMimeType(ContentService.MimeType.JSON);
}

// Test function
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
