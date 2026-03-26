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
    // Log untuk debugging (optional - bisa dihapus di production)
    console.log('doPost called', e.postData ? 'has postData' : 'no postData');
    
    // Check if postData exists
    if (!e || !e.postData || !e.postData.contents) {
      return ContentService
        .createTextOutput(JSON.stringify({
          success: false, 
          message: 'No post data received'
        }))
        .setMimeType(ContentService.MimeType.JSON);
    }
    
    // Parse data dari Laravel
    let data;
    try {
      data = JSON.parse(e.postData.contents);
    } catch (parseError) {
      return ContentService
        .createTextOutput(JSON.stringify({
          success: false, 
          message: 'Invalid JSON: ' + parseError.toString()
        }))
        .setMimeType(ContentService.MimeType.JSON);
    }
    
    // Validate required fields
    if (!data.action) {
      return ContentService
        .createTextOutput(JSON.stringify({
          success: false, 
          message: 'Missing action field'
        }))
        .setMimeType(ContentService.MimeType.JSON);
    }
    
    if (data.action === 'addOrder') {
      // Validate required fields for addOrder
      if (!data.spreadsheetId || !data.data) {
        return ContentService
          .createTextOutput(JSON.stringify({
            success: false, 
            message: 'Missing spreadsheetId or data field'
          }))
          .setMimeType(ContentService.MimeType.JSON);
      }
      
      // Get orderId and checkDuplicate flag for deduplication
      const orderId = data.orderId || (data.data && data.data[0]) || null;
      const checkDuplicate = data.checkDuplicate !== false; // Default to true if not specified
      
      return addOrderToSheet(data.spreadsheetId, data.data, orderId, checkDuplicate);
    }
    
    return ContentService
      .createTextOutput(JSON.stringify({
        success: false, 
        message: 'Invalid action: ' + data.action
      }))
      .setMimeType(ContentService.MimeType.JSON);
      
  } catch (error) {
    // Log error untuk debugging
    console.error('doPost error:', error.toString());
    
    return ContentService
      .createTextOutput(JSON.stringify({
        success: false, 
        message: 'Server error: ' + error.toString(),
        stack: error.stack ? error.stack.toString() : 'No stack trace'
      }))
      .setMimeType(ContentService.MimeType.JSON);
  }
}

function addOrderToSheet(spreadsheetId, rowData, orderId, checkDuplicate) {
  try {
    // Validate inputs
    if (!spreadsheetId) {
      return ContentService
        .createTextOutput(JSON.stringify({
          success: false, 
          message: 'Spreadsheet ID is required'
        }))
        .setMimeType(ContentService.MimeType.JSON);
    }
    
    if (!rowData || !Array.isArray(rowData) || rowData.length === 0) {
      return ContentService
        .createTextOutput(JSON.stringify({
          success: false, 
          message: 'Row data is required and must be an array'
        }))
        .setMimeType(ContentService.MimeType.JSON);
    }
    
    // Buka spreadsheet dengan error handling
    let spreadsheet;
    try {
      spreadsheet = SpreadsheetApp.openById(spreadsheetId);
    } catch (openError) {
      return ContentService
        .createTextOutput(JSON.stringify({
          success: false, 
          message: 'Failed to open spreadsheet: ' + openError.toString()
        }))
        .setMimeType(ContentService.MimeType.JSON);
    }
    
    const sheet = spreadsheet.getActiveSheet();
    
    // CRITICAL: Pastikan header ada dan sesuai format terbaru (9 kolom dengan Email)
    // Header sesuai dengan format Laravel: Order ID, Username, Email, Nama Produk, Amount, Gamepass, Status, Tanggal, Jam
    const expectedHeaders = ['Order ID', 'Username', 'Email', 'Nama Produk', 'Amount', 'Gamepass', 'Status', 'Tanggal', 'Jam'];
    const expectedHeaderCount = expectedHeaders.length; // 9 kolom
    
    // Cek apakah sheet kosong atau header tidak sesuai
    const lastRow = sheet.getLastRow();
    const needsHeaderUpdate = lastRow === 0; // Sheet kosong
    let headerNeedsUpdate = false;
    
    if (lastRow > 0) {
      // Cek header yang ada
      try {
        const existingHeaderRange = sheet.getRange(1, 1, 1, Math.min(sheet.getLastColumn(), expectedHeaderCount));
        const existingHeaders = existingHeaderRange.getValues()[0];
        
        // Cek apakah header sudah sesuai (9 kolom dan kolom ke-3 adalah 'Email')
        if (existingHeaders.length < expectedHeaderCount || 
            (existingHeaders.length >= 3 && String(existingHeaders[2] || '').trim() !== 'Email')) {
          headerNeedsUpdate = true;
          console.log('Header needs update - current:', existingHeaders, 'expected:', expectedHeaders);
        }
      } catch (checkError) {
        // If check fails, assume header needs update
        headerNeedsUpdate = true;
        console.warn('Error checking existing headers:', checkError.toString());
      }
    }
    
    // Update header jika diperlukan
    if (needsHeaderUpdate || headerNeedsUpdate) {
      try {
        // Set header dengan format terbaru (9 kolom dengan Email)
        sheet.getRange(1, 1, 1, expectedHeaderCount).setValues([expectedHeaders]);
        
        // Format header
        const headerRange = sheet.getRange(1, 1, 1, expectedHeaderCount);
        headerRange.setBackground('#E3F2FD');
        headerRange.setFontWeight('bold');
        headerRange.setFontColor('#000000');
        
        console.log('Header updated successfully:', expectedHeaders);
      } catch (headerError) {
        console.error('Error setting headers:', headerError.toString());
        // Continue anyway, might still work
      }
    }
    
    // CRITICAL: Deduplication check - cek apakah order_id sudah ada di spreadsheet
    // Ini mencegah double/triple entry untuk order yang sama
    // Check dilakukan SEBELUM lock untuk menghindari race condition
    if (checkDuplicate && orderId) {
      try {
        // Normalize orderId (trim whitespace, convert to string)
        const normalizedOrderId = String(orderId).trim();
        
        const lastRow = sheet.getLastRow();
        if (lastRow > 1) {
          // Cek kolom pertama (Order ID) dari row 2 sampai last row
          // Use getValues() untuk mendapatkan semua nilai sekaligus (lebih cepat)
          const orderIdRange = sheet.getRange(2, 1, lastRow - 1, 1);
          const orderIdValues = orderIdRange.getValues();
          
          // Check if order_id already exists (case-insensitive, trim whitespace)
          for (let i = 0; i < orderIdValues.length; i++) {
            const existingOrderId = String(orderIdValues[i][0] || '').trim();
            // Exact match (case-sensitive untuk Order ID karena biasanya alphanumeric)
            if (existingOrderId === normalizedOrderId) {
              // Order ID already exists, skip insertion
              console.log('Duplicate order detected, skipping:', normalizedOrderId);
              return ContentService
                .createTextOutput(JSON.stringify({
                  success: true, 
                  message: 'Order already exists, skipped duplicate',
                  orderId: normalizedOrderId,
                  duplicate: true
                }))
                .setMimeType(ContentService.MimeType.JSON);
            }
          }
        }
      } catch (dedupError) {
        // If deduplication check fails, log but continue (better to add duplicate than miss order)
        console.warn('Deduplication check failed:', dedupError.toString());
        // Continue to insertion - better to have duplicate than miss order
      }
    }
    
    // Tambahkan data baru dengan locking mechanism untuk concurrent requests
    // Use lock service to prevent race conditions when multiple requests come in simultaneously
    const lock = LockService.getScriptLock();
    
    // Try to acquire lock (wait max 5 seconds)
    let lockAcquired = false;
    try {
      lockAcquired = lock.tryLock(5000); // 5 seconds timeout
    } catch (lockError) {
      console.warn('Lock acquisition failed:', lockError.toString());
      // Continue anyway, might still work
    }
    
    // Get the next row number (inside lock if possible to prevent race conditions)
    let newRow;
    if (lockAcquired) {
      try {
        newRow = sheet.getLastRow() + 1;
      } finally {
        lock.releaseLock();
      }
    } else {
      // If lock failed, just get the row (might have race condition but better than nothing)
      newRow = sheet.getLastRow() + 1;
    }
    
    // Ensure rowData matches expected column count (pad with empty strings if needed)
    const expectedColumns = 9; // Order ID, Username, Email, Nama Produk, Amount, Gamepass, Status, Tanggal, Jam
    const paddedRowData = [];
    for (let i = 0; i < expectedColumns; i++) {
      let cellValue = rowData[i] !== undefined ? String(rowData[i]) : '';
      
      // CRITICAL: Format Amount (column 5, index 4) sebagai text untuk mencegah Google Sheets mengubah format
      // Jika amount dimulai dengan apostrophe, pastikan tetap sebagai text
      if (i === 4 && cellValue) {
        // Column 5 adalah Amount - pastikan format ribuan penuh tetap sebagai text
        // Remove leading apostrophe if present (will be set as text format)
        cellValue = cellValue.replace(/^'/, '');
        // Set cell format as text to prevent Google Sheets from auto-formatting
        paddedRowData[i] = cellValue;
      } else {
        paddedRowData[i] = cellValue;
      }
    }
    
    try {
      // CRITICAL: Set Amount column (column 5, index 4) as text format BEFORE writing
      // This ensures format ribuan penuh (1.000, 10.000) tidak disingkat menjadi (1, 1.3)
      // Set format first to prevent Google Sheets from auto-formatting the number
      try {
        const amountCell = sheet.getRange(newRow, 5); // Column 5 is Amount (after Email)
        amountCell.setNumberFormat('@'); // Set as text format (@ = text in Google Sheets)
      } catch (formatError) {
        console.warn('Failed to set Amount format as text (non-critical):', formatError.toString());
      }
      
      // Write data to spreadsheet (this is the critical operation)
      sheet.getRange(newRow, 1, 1, paddedRowData.length).setValues([paddedRowData]);
      
      // Double-check Amount format after writing (in case it was reset)
      try {
        const amountCell = sheet.getRange(newRow, 5); // Column 5 is Amount (after Email)
        amountCell.setNumberFormat('@'); // Set as text format again to ensure it sticks
      } catch (formatError) {
        console.warn('Failed to re-set Amount format as text (non-critical):', formatError.toString());
      }
      
      // Format baris data (optional, bisa skip jika timeout)
      try {
        const dataRange = sheet.getRange(newRow, 1, 1, paddedRowData.length);
        dataRange.setBorder(true, true, true, true, true, true);
      } catch (formatError) {
        // Format error is not critical, log but continue
        console.warn('Format error (non-critical):', formatError.toString());
      }
      
      // Auto-resize columns (optional, bisa skip jika timeout)
      try {
        sheet.autoResizeColumns(1, Math.min(paddedRowData.length, 10)); // Limit to 10 columns max
      } catch (resizeError) {
        // Resize error is not critical, log but continue
        console.warn('Resize error (non-critical):', resizeError.toString());
      }
    } catch (writeError) {
      return ContentService
        .createTextOutput(JSON.stringify({
          success: false, 
          message: 'Failed to write data: ' + writeError.toString()
        }))
        .setMimeType(ContentService.MimeType.JSON);
    }
    
    return ContentService
      .createTextOutput(JSON.stringify({
        success: true, 
        message: 'Order added successfully',
        row: newRow,
        orderId: paddedRowData[0] || 'unknown'
      }))
      .setMimeType(ContentService.MimeType.JSON);
      
  } catch (error) {
    console.error('addOrderToSheet error:', error.toString());
    
    return ContentService
      .createTextOutput(JSON.stringify({
        success: false, 
        message: 'Error adding order: ' + error.toString(),
        stack: error.stack ? error.stack.toString() : 'No stack trace'
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
