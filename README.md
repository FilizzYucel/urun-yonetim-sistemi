# 🛒 Ürün Yönetim Sistemi - Fullstack Proje

**Angular + Symfony + PostgreSQL** ile yapılmış, modern ve tam işlevsel bir ürün yönetim uygulaması.

![Status](https://img.shields.io/badge/Status-Active-brightgreen) ![License](https://img.shields.io/badge/License-MIT-blue)

---

## 📋 İçindekiler

- [Teknolojiler](#-teknolojiler)
- [Proje Yapısı](#-proje-yapısı)
- [Kurulum](#-kurulum)
- [Çalıştırma](#-çalıştırma)
- [API Endpoints](#-api-endpoints)
- [Özellikler](#-özellikler)
- [Ekran Görüntüleri](#-ekran-görüntüleri)

---

## 🔧 Teknolojiler

### Backend
- **PHP 8.5** - Programlama dili
- **Symfony 8.0** - Web framework
- **Doctrine ORM** - Veritabanı katmanı
- **nelmio/CORS Bundle** - Cross-Origin desteği

### Frontend
- **Angular 21** - UI framework
- **TypeScript** - Programlama dili
- **Bootstrap/CSS** - Styling

### Database
- **PostgreSQL 18.1** - İlişkisel veritabanı
- **pgAdmin 4** - Web tabanlı yönetim aracı

---

## 📁 Proje Yapısı

```
benim-projem/
├── src/                          # Symfony backend
│   ├── Controller/               # HTTP controllers
│   │   ├── MerhabaController.php  # User API
│   │   ├── ProductController.php  # Product API
│   │   └── CategoryController.php # Category API
│   ├── Entity/                   # Database entities
│   │   ├── User.php
│   │   ├── Product.php
│   │   └── Category.php
│   ├── Repository/               # Database queries
│   │   ├── UserRepository.php
│   │   ├── ProductRepository.php
│   │   └── CategoryRepository.php
│   ├── Service/                  # Business logic
│   │   └── UserService.php
│   └── Kernel.php
│
├── frontend/                     # Angular frontend
│   ├── src/
│   │   ├── app/
│   │   │   ├── components/       # UI Components
│   │   │   │   ├── product-list/
│   │   │   │   └── category-list/
│   │   │   ├── services/         # API Services
│   │   │   │   └── product.service.ts
│   │   │   ├── models/           # TypeScript interfaces
│   │   │   │   └── product.model.ts
│   │   │   ├── app.ts            # Main component
│   │   │   ├── app.routes.ts     # Routing
│   │   │   └── app.config.ts     # Configuration
│   │   ├── main.ts
│   │   └── index.html
│   ├── angular.json
│   └── package.json
│
├── config/                       # Symfony configuration
│   ├── packages/
│   │   ├── doctrine.yaml
│   │   ├── nelmio_cors.yaml
│   │   └── ...
│   ├── routes.yaml
│   └── services.yaml
│
├── migrations/                   # Database migrations
├── public/                       # Web root
├── .env                          # Environment variables
├── composer.json                 # PHP dependencies
└── README.md                     # This file
```

---

## 🚀 Kurulum

### Ön Gereksinimler
- **PHP 8.5+**
- **Node.js 20+**
- **PostgreSQL 18+**
- **Composer** (PHP package manager)
- **npm** (Node package manager)

### 1️⃣ PostgreSQL Kurulum (Windows)

```powershell
# PostgreSQL 18 zaten kuruluysa, cluster başlat
& "C:\Program Files\PostgreSQL\18\bin\pg_ctl.exe" -D "C:\Program Files\PostgreSQL\18\data" -l "$env:USERPROFILE\pg_logfile.txt" start

# Veritabanı oluştur
psql -U postgres
```

```sql
CREATE USER app WITH PASSWORD 'app123';
CREATE DATABASE app OWNER app;
GRANT ALL PRIVILEGES ON DATABASE app TO app;
\c app
GRANT ALL ON SCHEMA public TO app;
```

### 2️⃣ Backend Kurulum

```powershell
cd benim-projem

# Composer dependencies kur
composer install

# Database migration çalıştır
php bin/console doctrine:migrations:migrate

# Cache temizle
php bin/console cache:clear
```

### 3️⃣ Frontend Kurulum

```powershell
cd frontend

# npm dependencies kur
npm install

# Angular CLI kontrol et
ng version
# Angular CLI: 21.0.3 veya daha yeni
```

### 4️⃣ Environment Ayarları

`.env` dosyasının doğru ayarlı olduğunu kontrol et:

```dotenv
# Database
DATABASE_URL="postgresql://app:app123@127.0.0.1:5432/app?serverVersion=18&charset=utf8"

# CORS (Angular ile iletişim için)
CORS_ALLOW_ORIGIN='^https?://(localhost|127\.0\.0\.1)(:[0-9]+)?$'

# App
APP_ENV=dev
APP_SECRET=your-secret-here
```

---

## ⚙️ Çalıştırma

### Terminal 1: PostgreSQL Server

```powershell
# Server durumu kontrol et
& "C:\Program Files\PostgreSQL\18\bin\pg_ctl.exe" -D "C:\Program Files\PostgreSQL\18\data" status

# Eğer durmuşsa başlat
& "C:\Program Files\PostgreSQL\18\bin\pg_ctl.exe" -D "C:\Program Files\PostgreSQL\18\data" -l "$env:USERPROFILE\pg_logfile.txt" start
```

### Terminal 2: Symfony Backend (Port 8000)

```powershell
# Proje klasöründe çalıştır
symfony serve --no-tls

# VEYA PHP built-in server kullan
php -S 127.0.0.1:8000 -t public
```

**Erişim:** http://127.0.0.1:8000

### Terminal 3: Angular Frontend (Port 4200)

```powershell
cd frontend

# Development server başlat
ng serve --open

# Tarayıcı otomatik açılacak, veya manuel:
# http://localhost:4200
```

**Erişim:** http://localhost:4200

---

## 📡 API Endpoints

### Base URL
```
http://127.0.0.1:8000/api
```

### Users
```
GET    /users                 # Tüm kullanıcılar
POST   /users                 # Yeni kullanıcı
GET    /users/{id}            # Tek kullanıcı
PUT    /users/{id}            # Güncelle
DELETE /users/{id}            # Sil
```

### Categories
```
GET    /categories            # Tüm kategoriler
POST   /categories            # Yeni kategori
GET    /categories/{id}       # Tek kategori
PUT    /categories/{id}       # Güncelle
DELETE /categories/{id}       # Sil
```

### Products
```
GET    /products              # Tüm ürünler
POST   /products              # Yeni ürün
GET    /products/{id}         # Tek ürün
PUT    /products/{id}         # Güncelle
DELETE /products/{id}         # Sil
GET    /products?category=1   # Kategoriye göre filtrele
```

### Örnek API Çağrıları

```bash
# Tüm ürünleri getir
curl -X GET "http://127.0.0.1:8000/api/products"

# Yeni ürün oluştur
curl -X POST "http://127.0.0.1:8000/api/products" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "iPhone 15",
    "description": "Apple akıllı telefon",
    "price": 49999.99,
    "stock": 10,
    "categoryId": 1
  }'

# Ürün güncelle
curl -X PUT "http://127.0.0.1:8000/api/products/1" \
  -H "Content-Type: application/json" \
  -d '{"name": "iPhone 15 Pro", "stock": 5}'

# Ürün sil
curl -X DELETE "http://127.0.0.1:8000/api/products/1"
```

---

## ✨ Özellikler

### Backend Özellikleri
- ✅ RESTful API
- ✅ Doctrine ORM ile database işlemleri
- ✅ Input validation
- ✅ Error handling
- ✅ CORS desteği
- ✅ JSON response formatting

### Frontend Özellikleri
- ✅ Responsive design
- ✅ Product CRUD operasyonları
- ✅ Category yönetimi
- ✅ Real-time API iletişimi
- ✅ Error/Success mesajları
- ✅ Loading states
- ✅ Kategori filtreleme

### Database Özellikleri
- ✅ User management
- ✅ Product management
- ✅ Category management
- ✅ Relationships (Product → Category)
- ✅ Timestamps (created_at)

---

## 📊 Veritabanı Şeması

```
┌─────────────────┐
│      user       │
├─────────────────┤
│ id (PK)         │
│ name            │
│ email           │
│ created_at      │
└─────────────────┘

┌──────────────────┐
│    category      │
├──────────────────┤
│ id (PK)          │
│ name             │
│ description      │
└──────────────────┘

┌──────────────────┐
│     product      │
├──────────────────┤
│ id (PK)          │
│ name             │
│ description      │
│ price            │
│ stock            │
│ category_id (FK) │──→ category
│ created_at       │
└──────────────────┘
```

---

## 🧪 Test Etme

### Angular'da ürün ekle:
1. http://localhost:4200 açı
2. **Ürünler** sayfasına git
3. **➕ Yeni Ürün Ekle** butonuna tıkla
4. Form doldur ve Kaydet'e tıkla

### PostgreSQL'de kontrol et:
```bash
# pgAdmin 4'ü aç: http://localhost:5050
# Veya psql komut satırında:
psql -U app -d app

SELECT * FROM product;
SELECT * FROM category;
```

---

## 🛠️ Troubleshooting

### "Connection refused" hatası
```powershell
# PostgreSQL çalışıyor mu kontrol et
& "C:\Program Files\PostgreSQL\18\bin\pg_ctl.exe" -D "C:\Program Files\PostgreSQL\18\data" status

# Eğer durmuşsa başlat
& "C:\Program Files\PostgreSQL\18\bin\pg_ctl.exe" -D "C:\Program Files\PostgreSQL\18\data" -l "$env:USERPROFILE\pg_logfile.txt" start
```

### "CORS error" hatası
- Symfony server'ın `CORS_ALLOW_ORIGIN` .env'de doğru ayarlı olduğunu kontrol et
- Symfony'i yeniden başlat

### "Port already in use" hatası
```powershell
# Occupying process'i bul ve kapat
Get-Process | Where-Object {$_.Name -like "*php*"} | Stop-Process -Force
Get-Process | Where-Object {$_.Name -like "*node*"} | Stop-Process -Force
```

### "npm packages not found"
```powershell
cd frontend
rm -r node_modules package-lock.json
npm install
```

---

## 📚 Öğrenme Kaynakları

- [Symfony Dokümentasyonu](https://symfony.com/doc)
- [Angular Dokümentasyonu](https://angular.io/docs)
- [PostgreSQL Dokümentasyonu](https://www.postgresql.org/docs)
- [REST API Best Practices](https://restfulapi.net)

---

## 📝 Proje Tarihi

- **11 Aralık 2025**: PHP OOP, Symfony, PostgreSQL, Doctrine başlangıç
- **12 Aralık 2025**: Angular frontend, CORS, Fullstack entegrasyon
- **17 Aralık 2025**: Final version, README hazırlanması

---

## 📄 Lisans

Bu proje MIT Lisansı altında yayımlanmıştır.

---

## 👤 Geliştirici

**Filiz** - Fullstack Geliştirici

---

## 💡 İpuçları

### Chrome DevTools ile API isteklerini görmek
1. Chrome DevTools aç (**F12**)
2. **Network** sekmesine git
3. Ürün ekle/sil/güncelle ve istekleri gözlemle

### Symfony route'larını listelemek
```powershell
php bin/console debug:router
```

### Angular component'larını debug etmek
```powershell
# Angular DevTools Chrome extension'ı kur
```

### PostgreSQL'de veri sorgula
```bash
psql -U app -d app -c "SELECT * FROM product;"
```

---

## ⚡ HIZLI BAŞLANGIÇ (İlk Kurulum)

**Sadece 5 adım!** ⏱️

### 1. Proje Klasörüne Git
```powershell
cd proje-klasoru/benim-projem
```

### 2. PostgreSQL Başlat (Terminal 1)
```powershell
& "C:\Program Files\PostgreSQL\18\bin\pg_ctl.exe" -D "C:\Program Files\PostgreSQL\18\data" -l "$env:USERPROFILE\pg_logfile.txt" start
```

### 3. Backend Başlat (Terminal 2)
```powershell
symfony serve --no-tls
# Veya: php -S 127.0.0.1:8000 -t public
```
✅ http://127.0.0.1:8000

### 4. Frontend Başlat (Terminal 3)
```powershell
cd frontend
ng serve --open
```
✅ http://localhost:4200 (Chrome otomatik açılacak)

### 5. Tarayıcıda Kullan!
- 🛍️ **Ürünler** sayfasında ürün ekle/düzenle/sil
- 📁 **Kategoriler** sayfasında kategori yönet
- ✅ API'den veri gelişini kontrol et

---

**Yalnızca 1. kez başlatmak için gerekli (proje klasöründe):**
```powershell
composer install

cd frontend
npm install
```

ayrıca vs code kullanıyorsan başlatması için chate sorabilirsin hızlıca başlatır.
---

## ❓ Sorular & Destek

Herhangi bir sorun için:
1. **Terminal output**'unu kontrol et
2. **Browser console**'u aç (F12)
3. **Network tab**'da API çağrılarını kontrol et

**İyi geliştirmeler! 🚀**

