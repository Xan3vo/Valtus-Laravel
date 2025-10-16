# 📊 Panduan Spreadsheet Sederhana

## 🎯 Cara Kerja
Setiap pesanan yang **disetujui** di admin panel akan muncul di log Laravel dengan format yang siap copy-paste ke Google Spreadsheet.

## 📋 Langkah Setup

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

### 2. Setup di Admin Panel
1. Login ke admin panel Valtus
2. Masuk ke **Settings** > **Spreadsheet Integration**
3. Paste link spreadsheet di field "Google Spreadsheet Link"
4. Centang "Aktifkan integrasi spreadsheet"
5. Klik **Save Settings**

### 3. Cara Menggunakan
1. Setiap kali ada pesanan yang **disetujui** (klik "Setujui" di admin)
2. Buka file `storage/logs/laravel.log`
3. Cari log dengan format `=== SPREADSHEET DATA ===`
4. Copy data dari `csv_format` 
5. Paste ke Google Spreadsheet

## 📝 Contoh Data

Ketika pesanan disetujui, akan muncul log seperti ini:
```
[2025-10-16 12:00:00] local.INFO: === SPREADSHEET DATA === {"order_id":"J201XUJ","spreadsheet_url":"https://docs.google.com/spreadsheets/d/...","csv_format":"\"J201XUJ\",\"rizkimulyawan110404\",\"https://www.roblox.com/game-pass/1525959160\",\"100 Robux\",\"pending\",\"2025-10-16 12:00:00\"","instructions":"Copy the CSV data above and paste it into your Google Spreadsheet"}
```

**Yang perlu dicopy:**
```
"J201XUJ","rizkimulyawan110404","https://www.roblox.com/game-pass/1525959160","100 Robux","pending","2025-10-16 12:00:00"
```

## 🔧 Tips

### Cara Copy-Paste ke Spreadsheet
1. Buka Google Spreadsheet
2. Pilih baris kosong pertama (setelah header)
3. Paste data CSV
4. Google Sheets akan otomatis memisahkan ke kolom yang benar

### Format Data
- **Order ID**: ID unik pesanan
- **Username**: Username Roblox customer
- **GamePass Link**: Link GamePass yang harus dibuat customer
- **Amount**: Jumlah Robux/item
- **Status**: Status pesanan (pending/completed)
- **Date**: Tanggal dan waktu pesanan dibuat

## ⚠️ Catatan Penting

- Data hanya muncul di log ketika pesanan **disetujui**
- Pesanan yang **ditolak** tidak akan masuk ke spreadsheet
- Pastikan selalu copy data dari log terbaru
- Data di log tidak akan hilang, bisa di-copy kapan saja

## 🆘 Troubleshooting

### Data tidak muncul di log?
1. Pastikan integrasi spreadsheet sudah diaktifkan
2. Pastikan pesanan sudah disetujui (bukan ditolak)
3. Cek file `storage/logs/laravel.log` dengan benar

### Format data tidak sesuai?
1. Pastikan copy data dari `csv_format` (bukan `raw_data`)
2. Pastikan paste di baris kosong pertama setelah header
3. Google Sheets akan otomatis format data

## 📞 Bantuan

Jika ada masalah:
1. Cek log Laravel di `storage/logs/laravel.log`
2. Pastikan semua langkah setup sudah benar
3. Pastikan pesanan sudah disetujui di admin panel
