# 📊 Panduan Setup Google Spreadsheet Integration

## 🎯 Tujuan
Mengintegrasikan sistem Valtus dengan Google Spreadsheet untuk tracking pesanan otomatis.

## 📋 Langkah-langkah Setup

### 1. Buat Google Spreadsheet
1. Buka [Google Sheets](https://sheets.google.com)
2. Buat spreadsheet baru
3. Beri nama: "Valtus Orders Tracking"
4. Buat header di baris pertama:
   ```
   A1: Order ID
   B1: Username  
   C1: GamePass Link
   D1: Amount
   E1: Status
   F1: Date
   ```

### 2. Setup Google Apps Script
1. Di spreadsheet, klik **Extensions** > **Apps Script**
2. Hapus semua kode default
3. Copy kode dari file `google-apps-script.js` dan paste
4. Ganti `spreadsheetId` di function `testAddOrder()` dengan ID spreadsheet Anda
5. Klik **Save** (Ctrl+S)

### 3. Deploy sebagai Web App
1. Di Apps Script, klik **Deploy** > **New deployment**
2. Pilih type: **Web app**
3. Description: "Valtus Order Integration"
4. Execute as: **Me**
5. Who has access: **Anyone**
6. Klik **Deploy**
7. **COPY URL DEPLOYMENT** (penting!)

### 4. Update Laravel Configuration
1. Buka admin panel Valtus
2. Masuk ke **Settings** > **Spreadsheet Integration**
3. Paste URL deployment di field "Google Spreadsheet Link"
4. Centang "Aktifkan integrasi spreadsheet"
5. Klik **Save Settings**

### 5. Test Integration
1. Buat pesanan test di Valtus
2. Konfirmasi pembayaran di admin panel
3. Cek spreadsheet - data harus muncul otomatis

## 🔧 Troubleshooting

### Data tidak muncul di spreadsheet?
1. Cek log Laravel: `storage/logs/laravel.log`
2. Pastikan URL deployment benar
3. Pastikan spreadsheet permissions: "Anyone with the link can edit"
4. Test dengan function `testAddOrder()` di Apps Script

### Error "Script not found"?
1. Pastikan Apps Script sudah di-deploy
2. Pastikan permissions: "Anyone"
3. Regenerate URL deployment

### Error "Spreadsheet not found"?
1. Pastikan spreadsheet ID benar
2. Pastikan spreadsheet tidak dihapus
3. Pastikan Apps Script punya akses ke spreadsheet

## 📊 Format Data yang Dikirim

Setiap pesanan yang dikonfirmasi akan mengirim data:
```javascript
[
  "J201XUJ",                    // Order ID
  "rizkimulyawan110404",        // Username
  "https://www.roblox.com/...", // GamePass Link
  "100 Robux",                  // Amount
  "pending",                    // Status
  "2025-10-16 12:00:00"        // Date
]
```

## 🎨 Customization

### Mengubah Format Data
Edit function `addOrderToSheet()` di Apps Script untuk mengubah format atau menambah kolom.

### Mengubah Styling
Edit bagian formatting di Apps Script untuk mengubah tampilan spreadsheet.

### Menambah Validasi
Tambahkan validasi data di Apps Script sebelum menulis ke spreadsheet.

## 🔒 Security Notes

- URL deployment bersifat publik, jangan share ke sembarang orang
- Spreadsheet harus dalam mode "Anyone with the link can edit"
- Apps Script hanya menambah data, tidak menghapus atau mengubah data existing

## 📞 Support

Jika ada masalah:
1. Cek log Laravel untuk error details
2. Test dengan function `testAddOrder()` di Apps Script
3. Pastikan semua langkah setup sudah benar
