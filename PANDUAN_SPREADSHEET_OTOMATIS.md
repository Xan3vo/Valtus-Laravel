# 📊 Panduan Spreadsheet Otomatis

## 🎯 Cara Kerja
Setiap pesanan yang **disetujui** di admin panel akan **otomatis** masuk ke Google Spreadsheet tanpa perlu copy-paste manual!

## 📋 Langkah Setup Lengkap

### 1. Buat Google Spreadsheet
1. Buka [Google Sheets](https://sheets.google.com)
2. Buat spreadsheet baru
3. Beri nama: "Valtus Orders"
4. Buat header di baris pertama:
   ```
   A1: Order ID
   B1: Username  
   C1: GamePass Link
   D1: Amount
   E1: Status
   F1: Date
   ```
5. **PENTING**: Share spreadsheet dengan "Anyone with the link can edit"

### 2. Setup Google Apps Script
1. Di spreadsheet, klik **Extensions** > **Apps Script**
2. Hapus semua kode default
3. Copy kode dari file `google-apps-script-simple.js` dan paste
4. Ganti `spreadsheetId` di function `testAddOrder()` dengan ID spreadsheet Anda
5. Klik **Save** (Ctrl+S)

### 3. Deploy Apps Script
1. Di Apps Script, klik **Deploy** > **New deployment**
2. Pilih type: **Web app**
3. Description: "Valtus Order Integration"
4. Execute as: **Me**
5. Who has access: **Anyone**
6. Klik **Deploy**
7. **COPY URL DEPLOYMENT** (penting!)

### 4. Setup di Admin Panel
1. Login ke admin panel Valtus
2. Masuk ke **Settings** > **Spreadsheet Integration**
3. Paste **link spreadsheet** di field "Google Spreadsheet Link"
4. Paste **URL deployment** di field "Google Apps Script URL"
5. Centang "Aktifkan integrasi spreadsheet"
6. Klik **Save Settings**

### 5. Test Integration
1. Buat pesanan test di Valtus
2. Konfirmasi pembayaran di admin panel (klik "Setujui")
3. Cek spreadsheet - data harus muncul otomatis!

## 🔧 Troubleshooting

### Data tidak muncul di spreadsheet?
1. **Cek log Laravel**: `storage/logs/laravel.log`
2. **Pastikan Apps Script sudah di-deploy** dengan permission "Anyone"
3. **Pastikan spreadsheet di-share** dengan "Anyone with the link can edit"
4. **Test Apps Script** dengan function `testAddOrder()`

### Error "Script not found"?
1. Pastikan Apps Script sudah di-deploy
2. Pastikan permissions: "Anyone"
3. Regenerate URL deployment

### Error "Spreadsheet not found"?
1. Pastikan spreadsheet ID benar
2. Pastikan spreadsheet tidak dihapus
3. Pastikan spreadsheet di-share dengan benar

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
4. Pastikan spreadsheet permissions sudah benar

## ✅ Checklist Setup

- [ ] Google Spreadsheet dibuat dengan header yang benar
- [ ] Spreadsheet di-share dengan "Anyone with the link can edit"
- [ ] Google Apps Script dibuat dan di-deploy
- [ ] Apps Script permission: "Anyone"
- [ ] URL deployment di-copy
- [ ] Admin panel diisi dengan link spreadsheet dan URL deployment
- [ ] Integrasi spreadsheet diaktifkan
- [ ] Test dengan pesanan real

## 🎉 Hasil Akhir

Setelah setup selesai:
- Setiap pesanan yang disetujui akan **otomatis** masuk ke spreadsheet
- Data muncul dalam hitungan detik
- Tidak perlu copy-paste manual
- Format data rapi dan terstruktur
