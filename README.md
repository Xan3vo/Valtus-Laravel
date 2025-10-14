# Valtus - Top Up Robux & Game Items

Valtus adalah platform top-up Robux dan item game lainnya yang aman, cepat, dan terpercaya. Website ini dibangun menggunakan Laravel dengan sistem admin panel yang lengkap untuk mengelola produk, pesanan, dan pembayaran.

## 🚀 Fitur Utama

### 👤 **User Side (Frontend)**
- **Homepage**: Tampilan utama dengan quick pick Robux, statistik real-time, dan hero image yang dapat dikustomisasi
- **Beli Robux**: Sistem pembelian Robux dengan validasi username Roblox dan pencarian GamePass (harga customer tetap normal, GamePass dibuat dengan harga lebih tinggi untuk kompensasi pajak Roblox)
- **Cek Pesanan**: Tracking status pesanan dengan timeline detail
- **Responsive Design**: Optimized untuk desktop dan mobile
- **Video Tutorial**: Modal video "Cara Beli" dan "Cara Bikin Gamepass" yang dapat dikustomisasi admin

### 🔧 **Admin Side (Backend)**
- **Dashboard**: Overview statistik dan grafik pendapatan
- **Products Management**: Kelola produk Robux dan item game lainnya
- **Orders Management**: Kelola pesanan dengan status tracking
- **Payments Management**: Konfirmasi pembayaran dan upload bukti transfer
- **Settings**: Pengaturan umum, pembayaran, dan media
- **Reports**: Analisis pendapatan dan profit

## 🛠️ Teknologi yang Digunakan

- **Backend**: Laravel 10
- **Frontend**: Blade Templates + Tailwind CSS
- **Database**: MySQL
- **Authentication**: Laravel Auth
- **API Integration**: Roblox API untuk validasi username dan GamePass
- **File Storage**: Local storage untuk upload gambar dan video

## 📋 Prerequisites

- PHP 8.1 atau lebih tinggi
- Composer
- MySQL 5.7 atau lebih tinggi
- Node.js & NPM (untuk asset compilation)

## 🔧 Installation

1. **Clone Repository**
   ```bash
   git clone <repository-url>
   cd valtus
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database Configuration**
   Edit file `.env` dan sesuaikan konfigurasi database:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=valtus
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

5. **Database Migration & Seeding**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. **Storage Link**
   ```bash
   php artisan storage:link
   ```

7. **Run Application**
   ```bash
   php artisan serve
   ```

8. **Compile Assets (Optional)**
   ```bash
   npm run dev
   # atau untuk production
   npm run build
   ```

## 🔐 Login Admin

**URL Admin**: `http://localhost:8000/admin/login`

**Kredensial**:
- **Email**: `admin@valtus.com`
- **Password**: `admin123`

## 📊 Database Structure

### Tables Utama:
- `users` - Data admin
- `orders` - Data pesanan
- `payments` - Data pembayaran
- `products` - Data produk
- `settings` - Konfigurasi website
- `proofs` - File bukti transfer

## 🎯 Alur Aplikasi

### 1. **User Flow (Pembelian Robux)**

#### **Step 1: Homepage**
- User mengunjungi homepage
- Melihat quick pick Robux (100-2000 RBX)
- Input custom amount atau pilih quick pick
- Klik "Beli Robux Sekarang"

#### **Step 2: Search Username**
- User diarahkan ke halaman search
- Input username Roblox
- Klik "Cek" untuk validasi username
- Jika valid, tombol "Lanjutkan Pembelian" aktif

#### **Step 3: GamePass Check**
- Sistem mengecek GamePass dengan harga sesuai
- Jika GamePass ditemukan → lanjut ke payment
- Jika tidak ditemukan → tampilkan modal instruksi buat GamePass

#### **Step 4: Payment**
- User input email dan konfirmasi data
- Pilih metode pembayaran (QRIS/Manual)
- Upload bukti transfer (jika manual)
- Order dibuat dengan status "waiting_confirmation"

#### **Step 5: Order Tracking**
- User dapat cek status di halaman "Cek Pesanan"
- Timeline status: waiting_confirmation → pending → completed

### 2. **Admin Flow (Manajemen)**

#### **Dashboard**
- Overview statistik: pending orders, completed orders, revenue
- Grafik pendapatan harian
- Quick access ke menu utama

#### **Orders Management**
- Lihat semua pesanan dengan filter status
- Detail pesanan: data user, produk, pembayaran
- Proses order: ubah status dari pending ke completed
- Set auto-complete date berdasarkan setting

#### **Payments Management**
- Konfirmasi pembayaran (approve/reject)
- Lihat bukti transfer yang diupload user
- Update status pembayaran

#### **Settings Management**
- **General Settings**: Nama website, kontak, social media
- **Payment Settings**: Konfigurasi QRIS, gateway payment
- **Media Management**: Upload hero image, video tutorial
- **Robux Pricing**: Set harga per 100 Robux

## 🎨 Customization

### **Media Management**
Admin dapat mengkustomisasi:
- **Hero Image**: Gambar utama di homepage (file upload atau URL)
- **Cara Beli Video**: Video tutorial pembelian (file upload atau YouTube)
- **Cara Gamepass Video**: Video tutorial buat GamePass (file upload atau YouTube)

### **Settings**
- **Website Info**: Nama, deskripsi, kontak
- **Social Media**: Instagram, TikTok, Facebook, Discord, Telegram, YouTube
- **Payment**: QRIS image, gateway configuration
- **Robux Pricing**: Harga per 100 Robux

## 📱 Mobile Responsiveness

Website fully responsive dengan:
- Hamburger menu untuk mobile
- Touch-friendly interface
- Optimized images dan videos
- Mobile-first design approach

## 🔒 Security Features

- CSRF protection
- Input validation
- File upload security
- SQL injection prevention
- XSS protection
- Secure file storage

## 🚀 Deployment

1. **Production Environment Setup**
   ```bash
   composer install --optimize-autoloader --no-dev
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

2. **Web Server Configuration**
   - Set document root ke `public/` folder
   - Configure URL rewriting untuk Laravel
   - Set proper file permissions

3. **Database Migration**
   ```bash
   php artisan migrate --force
   ```

## 📞 Support

Untuk pertanyaan atau bantuan teknis, silakan hubungi:
- **Email**: admin@valtus.com
- **Website**: [Valtus Website]

## 📄 License

This project is proprietary software. All rights reserved.

---

**Valtus** - Your Trusted Robux & Game Items Provider 🎮✨