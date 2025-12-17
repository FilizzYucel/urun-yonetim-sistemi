# 🎓 Fullstack Öğrenme Notları - Gün 1-4

**Tarih:** 9-15 Aralık 2025  
**Proje:** benim-projem (Symfony + PostgreSQL + Angular)

---

## 📚 BUGÜN ÖĞRENDİKLERİMİZ

### 1️⃣ PHP OOP Temelleri
- **Namespace:** Kodları organize etmek için kullanılır (`App\Controller`, `App\Service`)
- **Class:** Şablon gibi, nesneler bu şablondan oluşur
- **Dependency Injection (DI):** Bağımlılıkları constructor'dan geçirmek

```php
// DI Örneği
public function __construct(private UserService $userService)
{
    // Symfony otomatik olarak UserService'i enjekte eder
}
```

---

### 2️⃣ Symfony Framework

#### Route (Yönlendirme)
```php
use Symfony\Component\Routing\Attribute\Route;

#[Route('/users', name: 'user_list', methods: ['GET'])]
public function index(): JsonResponse
```

#### Controller
- HTTP isteklerini karşılar
- Service'leri çağırır
- Response döner

#### Service
- İş mantığı burada yazılır
- Controller'dan bağımsız
- Test edilebilir

---

### 3️⃣ PostgreSQL Veritabanı

#### Kurulum (Windows)
```powershell
# Cluster oluşturma
& "C:\Program Files\PostgreSQL\18\bin\initdb.exe" -D "C:\Program Files\PostgreSQL\18\data" --no-locale -A trust

# Server başlatma
& "C:\Program Files\PostgreSQL\18\bin\pg_ctl.exe" -D "C:\Program Files\PostgreSQL\18\data" -l "$env:USERPROFILE\pg_logfile.txt" start

# Server durdurma
& "C:\Program Files\PostgreSQL\18\bin\pg_ctl.exe" -D "C:\Program Files\PostgreSQL\18\data" stop
```

#### Veritabanı ve Kullanıcı Oluşturma
```sql
CREATE USER app WITH PASSWORD 'app123';
CREATE DATABASE app OWNER app;
GRANT ALL PRIVILEGES ON DATABASE app TO app;
\c app
GRANT ALL ON SCHEMA public TO app;
```

#### Bağlantı Bilgileri
- **Host:** 127.0.0.1
- **Port:** 5432
- **Database:** app
- **User:** app
- **Password:** app123

---

### 4️⃣ Doctrine ORM

#### Entity (Varlık)
Veritabanı tablosunu temsil eden PHP sınıfı:

```php
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;
}
```

#### Repository
Veritabanı sorgularını yapar:
```php
$user = $userRepository->find($id);           // ID ile bul
$users = $userRepository->findAll();          // Tümünü getir
$user = $userRepository->findOneBy(['email' => $email]); // Şarta göre bul
```

#### Migration (Göç)
```powershell
# Migration oluştur
php bin/console make:migration

# Migration çalıştır
php bin/console doctrine:migrations:migrate
```

---

### 5️⃣ REST API Kavramı

**API Nedir?**
- Application Programming Interface
- Uygulamalar arası iletişim köprüsü
- Garson gibi: Müşteri (Frontend) ile Mutfak (Backend) arasında

**HTTP Metodları:**
| Metod  | İşlem    | Örnek                |
|--------|----------|----------------------|
| GET    | Okuma    | Kullanıcıları listele |
| POST   | Oluşturma| Yeni kullanıcı ekle   |
| PUT    | Güncelleme| Kullanıcı bilgisi düzenle |
| DELETE | Silme    | Kullanıcı sil         |

---

## 🎯 OLUŞTURDUĞUMUZ API ENDPOİNTLERİ

### Users API
```
GET    /users          → Tüm kullanıcıları listele
POST   /users          → Yeni kullanıcı oluştur
GET    /users/{id}     → Tek kullanıcı getir
PUT    /users/{id}     → Kullanıcı güncelle
DELETE /users/{id}     → Kullanıcı sil
```

### Categories API
```
GET    /api/categories      → Tüm kategorileri listele
POST   /api/categories      → Yeni kategori oluştur
GET    /api/categories/{id} → Tek kategori getir
PUT    /api/categories/{id} → Kategori güncelle
DELETE /api/categories/{id} → Kategori sil
```

### Products API
```
GET    /api/products              → Tüm ürünleri listele
GET    /api/products?category=1   → Kategoriye göre filtrele
POST   /api/products              → Yeni ürün oluştur
GET    /api/products/{id}         → Tek ürün getir
PUT    /api/products/{id}         → Ürün güncelle
DELETE /api/products/{id}         → Ürün sil
```

---

## 📁 PROJE YAPISI

```
src/
├── Controller/
│   ├── MerhabaController.php    → User API endpoints
│   ├── ProductController.php    → Product API endpoints
│   └── CategoryController.php   → Category API endpoints
├── Entity/
│   ├── User.php                 → User tablosu
│   ├── Product.php              → Product tablosu
│   └── Category.php             → Category tablosu
├── Repository/
│   ├── UserRepository.php       → User sorguları
│   ├── ProductRepository.php    → Product sorguları
│   └── CategoryRepository.php   → Category sorguları
└── Service/
    └── UserService.php          → User iş mantığı
```

---

## 🧪 API TEST KOMUTLARI (curl)

```powershell
# Kullanıcı oluştur
curl.exe -X POST "http://127.0.0.1:8000/users" -H "Content-Type: application/json" -d '{\"name\":\"Filiz\",\"email\":\"filiz@example.com\"}'

# Kullanıcıları listele
curl.exe -X GET "http://127.0.0.1:8000/users"

# Kategori oluştur
curl.exe -X POST "http://127.0.0.1:8000/api/categories" -H "Content-Type: application/json" -d '{\"name\":\"Elektronik\",\"description\":\"Elektronik urunler\"}'

# Ürün oluştur
curl.exe -X POST "http://127.0.0.1:8000/api/products" -H "Content-Type: application/json" -d '{\"name\":\"iPhone 15\",\"price\":49999.99,\"stock\":10,\"categoryId\":1}'

# Ürünleri listele
curl.exe -X GET "http://127.0.0.1:8000/api/products"
```

---

## 📊 VERİTABANI DURUMU

**Tablolar:**
- `user` - Kullanıcılar
- `category` - Kategoriler
- `product` - Ürünler
- `doctrine_migration_versions` - Migration geçmişi

**Mevcut Veriler:**
- 1 Kullanıcı: Filiz (filiz@example.com)
- 1 Kategori: Elektronik
- 1 Ürün: iPhone 15 (49999.99 TL)

---

## 🔧 YARARLI KOMUTLAR

```powershell
# Symfony server başlat
symfony serve

# Arka planda başlat
symfony serve -d

# Route'ları listele
php bin/console debug:router

# Entity oluştur
php bin/console make:entity

# Migration oluştur ve çalıştır
php bin/console make:migration
php bin/console doctrine:migrations:migrate

# Cache temizle
php bin/console cache:clear
```

---

## ⚙️ YAPILANDIRMA

### .env Dosyası
```
DATABASE_URL="postgresql://app:app123@127.0.0.1:5432/app?serverVersion=18&charset=utf8"
```

### php.ini (C:\php-8.5.0\php.ini)
```ini
extension=pdo_pgsql  ; PostgreSQL için aktif edildi
```

---


1. **Angular CLI kurulumu**
   ```powershell
   npm install -g @angular/cli
   ng new frontend
   ```

2. **Component oluşturma**
   - ProductListComponent
   - ProductFormComponent
   - CategoryListComponent

3. **HttpClient ile API bağlantısı**
   ```typescript
   this.http.get('http://localhost:8000/api/products')
   ```

4. **CORS ayarları** (Symfony tarafında)

5. **Fullstack entegrasyon**

---

## 💡 ÖNEMLİ NOTLAR

1. **Symfony 8.0** kullanıyoruz (Attribute\Route, Annotation değil!)
2. **PostgreSQL 18.1** Windows'ta kurulu
3. **pgAdmin 4** ile veritabanını görsel olarak yönetebilirsin
4. API'ler JSON formatında veri döner
5. Her endpoint'te validation (doğrulama) yapılıyor

---

## 🏆 TAMAMLANAN HEDEFLER

- [x] PHP OOP temelleri
- [x] Symfony Controller/Service/DI
- [x] PostgreSQL kurulum ve yapılandırma
- [x] Doctrine Entity/Migration/Repository
- [x] CRUD API (Users, Products, Categories)
- [x] pgAdmin ile veritabanı görüntüleme
- [x] Angular frontend ✅
- [x] Fullstack entegrasyon ✅

---

## 📅 GÜN 4: ANGULAR FRONTEND (13 Aralık 2025)

### 6️⃣ Angular Kurulumu

```powershell
# Angular CLI kurulumu (global)
npm install -g @angular/cli

# Versiyon kontrolü
ng version
# Angular CLI: 21.0.3

# Yeni proje oluşturma
ng new frontend --routing --style=css --skip-git --skip-tests --ssr=false
```

### 7️⃣ Angular Proje Yapısı

```
frontend/
├── src/
│   ├── app/
│   │   ├── components/           # UI bileşenleri
│   │   │   ├── product-list/     # Ürün listesi
│   │   │   └── category-list/    # Kategori listesi
│   │   ├── models/               # TypeScript arayüzleri
│   │   │   └── product.model.ts
│   │   ├── services/             # API servisleri
│   │   │   └── product.service.ts
│   │   ├── app.ts                # Ana component
│   │   ├── app.html              # Ana template
│   │   ├── app.css               # Ana stiller
│   │   ├── app.routes.ts         # Routing tanımları
│   │   └── app.config.ts         # Uygulama config
│   └── main.ts                   # Bootstrap
└── angular.json                  # Angular ayarları
```

### 8️⃣ Angular Temel Kavramlar

#### Component (Bileşen)
UI'ın yapı taşı. Her component 3 dosyadan oluşur:
- `.ts` - Logic (TypeScript kodu)
- `.html` - Template (HTML görünümü)
- `.css` - Styles (CSS stilleri)

```typescript
@Component({
  selector: 'app-product-list',
  standalone: true,
  imports: [CommonModule, FormsModule],
  templateUrl: './product-list.component.html',
  styleUrl: './product-list.component.css'
})
export class ProductListComponent implements OnInit {
  products: Product[] = [];
  
  constructor(private productService: ProductService) {}
  
  ngOnInit(): void {
    this.loadProducts();
  }
}
```

#### Service (Servis)
API çağrıları ve iş mantığı için kullanılır:

```typescript
@Injectable({
  providedIn: 'root'  // Tüm uygulamada kullanılabilir
})
export class ProductService {
  private apiUrl = 'http://127.0.0.1:8000/api';

  constructor(private http: HttpClient) {}

  getProducts(): Observable<Product[]> {
    return this.http.get<ApiResponse<Product[]>>(`${this.apiUrl}/products`)
      .pipe(map(response => response.data));
  }
}
```

#### Routing (Yönlendirme)
Sayfa geçişleri için:

```typescript
export const routes: Routes = [
  { path: '', redirectTo: '/products', pathMatch: 'full' },
  { path: 'products', component: ProductListComponent },
  { path: 'categories', component: CategoryListComponent }
];
```

#### Template Syntax
Angular'ın özel HTML sözdizimi:

```html
<!-- Değişken gösterme -->
{{ product.name }}

<!-- Döngü (yeni syntax) -->
@for (product of products; track product.id) {
  <div>{{ product.name }}</div>
}

<!-- Koşul (yeni syntax) -->
@if (loading) {
  <div>Yükleniyor...</div>
}

<!-- Event binding -->
<button (click)="saveProduct()">Kaydet</button>

<!-- Two-way binding -->
<input [(ngModel)]="product.name">
```

### 9️⃣ HttpClient ile API Bağlantısı

```typescript
// app.config.ts'de HttpClient'ı etkinleştir
import { provideHttpClient } from '@angular/common/http';

export const appConfig: ApplicationConfig = {
  providers: [
    provideRouter(routes),
    provideHttpClient()  // Bu satır önemli!
  ]
};
```

### 🔟 CORS Ayarları (Symfony)

Angular (4200) ve Symfony (8000) farklı portlarda çalışır.
Tarayıcı güvenliği için CORS gerekli:

```powershell
# CORS bundle kur
composer require nelmio/cors-bundle
```

```yaml
# config/packages/nelmio_cors.yaml
nelmio_cors:
  defaults:
    allow_origin: ['%env(CORS_ALLOW_ORIGIN)%']
    allow_methods: ['GET', 'POST', 'PUT', 'DELETE']
    allow_headers: ['Content-Type']
```

```dotenv
# .env dosyasına ekle
CORS_ALLOW_ORIGIN='^https?://(localhost|127\.0\.0\.1)(:[0-9]+)?$'
```

---

## 🚀 SUNUCULARI BAŞLATMA

```powershell
# 1. PostgreSQL başlat
& "C:\Program Files\PostgreSQL\18\bin\pg_ctl.exe" -D "C:\Program Files\PostgreSQL\18\data" -l "$env:USERPROFILE\pg_logfile.txt" start

# 2. Symfony backend başlat (port 8000)
cd benim-projem
symfony serve --no-tls

# 3. Angular frontend başlat (port 4200)
cd frontend
ng serve --open
```

**Erişim:**
- 🌐 Angular: http://localhost:4200
- 🔌 API: http://127.0.0.1:8000/api/products

---

## 🎉 FULLSTACK PROJE TAMAMLANDI!

**Teknolojiler:**
- ✅ PHP 8.5 + Symfony 8.0 (Backend)
- ✅ PostgreSQL 18.1 (Veritabanı)
- ✅ Angular 21 (Frontend)
- ✅ TypeScript (Frontend dili)
- ✅ Doctrine ORM (Veritabanı katmanı)
- ✅ REST API (JSON iletişim)
- ✅ CORS (Cross-Origin güvenlik)

**Sonraki adımlar:**
- Authentication (JWT)
- Form validation
- Error handling
- Production deployment

---


### 📌 TEMEL TERİMLER VE KAVRAMLAR

#### 1. **Fullstack Nedir?**
Frontend (kullanıcının gördüğü) + Backend (sunucu tarafı) + Veritabanı = Fullstack

```
[Kullanıcı] → [Angular Frontend] → [Symfony Backend] → [PostgreSQL DB]
     ↑              ↓                    ↓                    ↓
   Ekran         Port 4200           Port 8000           Port 5432
```

---

#### 2. **Frontend (Angular) Terimleri**

| Terim | Açıklama | Örnek |
|-------|----------|-------|
| **Component** | Ekrandaki bir parça (sayfa, buton, form) | `ProductListComponent` |
| **Service** | API çağrılarını yapan sınıf | `ProductService` |
| **Observable** | Asenkron veri akışı (Promise gibi ama daha güçlü) | `getProducts().subscribe()` |
| **inject()** | Angular'a "bana şu servisi ver" demek | `inject(ProductService)` |
| **ChangeDetectorRef** | Ekranı manuel güncelle komutu | `cdr.detectChanges()` |
| **RouterLink** | Sayfa yönlendirme linki | `routerLink="/products"` |
| **ngOnInit** | Component yüklendiğinde çalışan metod | `ngOnInit() { this.loadData() }` |
| **[(ngModel)]** | Two-way binding (değer değişince hem ekran hem kod güncellenir) | `[(ngModel)]="product.name"` |

**Component Yapısı:**
```typescript
@Component({
  selector: 'app-product-list',    // HTML'de <app-product-list> olarak kullanılır
  standalone: true,                 // Bağımsız component (module gerektirmez)
  imports: [CommonModule],          // Kullandığı diğer modüller
  templateUrl: './product.html',    // HTML dosyası
  styleUrl: './product.css'         // CSS dosyası
})
export class ProductListComponent implements OnInit {
  // Değişkenler (state)
  products: Product[] = [];
  
  // Servisler inject edilir
  private productService = inject(ProductService);
  
  // Sayfa yüklendiğinde çalışır
  ngOnInit() {
    this.loadProducts();
  }
}
```

---

#### 3. **Backend (Symfony) Terimleri**

| Terim | Açıklama | Örnek |
|-------|----------|-------|
| **Controller** | HTTP isteklerini karşılayan sınıf | `ProductController` |
| **Route** | URL → Metod eşleştirmesi | `#[Route('/api/products')]` |
| **Entity** | Veritabanı tablosunun PHP karşılığı | `Product.php` |
| **Repository** | Veritabanı sorgularını yapan sınıf | `ProductRepository` |
| **EntityManager** | Veritabanına kaydetme/silme işlemleri | `$em->persist()`, `$em->flush()` |
| **Doctrine ORM** | PHP nesnelerini veritabanına çeviren araç | Object-Relational Mapping |
| **Migration** | Veritabanı şemasını güncelleyen dosya | `Version20251211.php` |
| **JsonResponse** | API'den JSON döndürme | `return $this->json([...])` |

**Controller Örneği:**
```php
#[Route('/api/products')]  // Bu controller /api/products URL'ini dinler
class ProductController extends AbstractController
{
    // Constructor Injection - Bağımlılıklar otomatik enjekte edilir
    public function __construct(
        private ProductRepository $productRepository,  // DB sorguları için
        private EntityManagerInterface $entityManager  // DB kaydetme için
    ) {}

    #[Route('', methods: ['GET'])]  // GET /api/products
    public function index(): JsonResponse
    {
        $products = $this->productRepository->findAll();
        return $this->json(['success' => true, 'data' => $products]);
    }

    #[Route('', methods: ['POST'])]  // POST /api/products
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        
        $product = new Product();
        $product->setName($data['name']);
        
        $this->entityManager->persist($product);  // Hazırla
        $this->entityManager->flush();            // Kaydet
        
        return $this->json(['success' => true], 201);
    }
}
```

---

#### 4. **HTTP ve REST API Kavramları**

| Kavram | Açıklama |
|--------|----------|
| **HTTP** | Web iletişim protokolü |
| **REST** | API tasarım standardı |
| **Endpoint** | API'nin erişim noktası (URL) |
| **Request** | Sunucuya giden istek |
| **Response** | Sunucudan gelen cevap |
| **JSON** | Veri formatı `{"name": "iPhone"}` |
| **Status Code** | İşlem sonucu kodu |

**HTTP Status Kodları:**
```
200 OK           → Başarılı
201 Created      → Oluşturuldu
400 Bad Request  → Hatalı istek (eksik/yanlış veri)
404 Not Found    → Bulunamadı
500 Server Error → Sunucu hatası
```

**CRUD Operasyonları:**
```
C - Create → POST   → Yeni kayıt oluştur
R - Read   → GET    → Kayıtları oku
U - Update → PUT    → Kayıt güncelle
D - Delete → DELETE → Kayıt sil
```

---

#### 5. **CORS (Cross-Origin Resource Sharing)**

**Sorun:** Tarayıcı güvenlik nedeniyle farklı portlar arası isteği engeller.
```
http://localhost:4200 (Angular) → http://localhost:8000 (Symfony)
          ↓
    ❌ CORS hatası!
```

**Çözüm:** Backend'e izin ekledik:
```yaml
# config/packages/nelmio_cors.yaml
nelmio_cors:
    defaults:
        allow_origin: ['^https?://(localhost|127\.0\.0\.1)(:[0-9]+)?$']
        allow_methods: ['GET', 'POST', 'PUT', 'DELETE']
```

---

#### 6. **Veritabanı İlişkileri**

**ManyToOne İlişkisi:** Bir kategorinin birden fazla ürünü olabilir.
```php
// Product.php
#[ORM\ManyToOne(targetEntity: Category::class)]
#[ORM\JoinColumn(nullable: false)]
private ?Category $category = null;
```

```
Category (1) ←──────── (N) Product
    │                       │
    └─ Elektronik           ├─ iPhone
                            ├─ Samsung TV
                            └─ Kulaklık
```

---

### 🔄 VERİ AKIŞI (Data Flow)

```
1. Kullanıcı "Ürünler" butonuna tıklar
        ↓
2. Angular Router → ProductListComponent'e yönlendirir
        ↓
3. ngOnInit() çalışır → loadProducts() çağrılır
        ↓
4. ProductService.getProducts() → HTTP GET isteği
        ↓
5. http://localhost:8000/api/products
        ↓
6. Symfony ProductController.index() çalışır
        ↓
7. ProductRepository.findAll() → Veritabanı sorgusu
        ↓
8. PostgreSQL → SELECT * FROM product
        ↓
9. Sonuç JSON olarak döner
        ↓
10. Angular subscribe() ile veriyi alır
        ↓
11. this.products = data
        ↓
12. HTML'de @for ile ürünler listelenir
```

---

### 🐛 ÇÖZDÜĞÜMÜZ SORUNLAR

#### 1. **2 Kere Tıklama Sorunu**
**Sorun:** Butonlara 2 kere tıklamak gerekiyordu.
**Sebep:** Angular async işlemlerden sonra ekranı güncellemiyordu.
**Çözüm:** `ChangeDetectorRef` ile manuel güncelleme:
```typescript
observable.subscribe({
  next: (data) => {
    this.products = data;
    this.cdr.detectChanges();  // ← Ekranı güncelle
  }
});
```

#### 2. **400 Bad Request Hatası**
**Sorun:** Ürün eklenemiyor, API 400 hatası veriyordu.
**Sebep:** Frontend `categoryId`, Backend `category_id` bekliyordu.
**Çözüm:** Service'de dönüşüm yaptık:
```typescript
const payload = {
  name: product.name,
  category_id: product.categoryId  // categoryId → category_id
};
```

#### 3. **Türkçe Karakter Sorunu**
**Sorun:** "Ürünler" yerine "�r�nler" görünüyordu.
**Sebep:** Dosya encoding sorunu.
**Çözüm:** HTML dosyasını UTF-8 olarak düzelttik.

---

### 💡 SORULARA HAZIRLIK

**S: Neden Angular ve Symfony kullandınız?**
C: Angular güçlü bir SPA (Single Page Application) framework'ü. Symfony ise PHP'nin en popüler enterprise framework'ü. İkisi birlikte profesyonel fullstack geliştirme için ideal.

**S: API neden ayrı?**
C: Separation of Concerns - Her katman kendi işini yapar. Frontend değişse bile backend aynı kalır. Mobil uygulama da aynı API'yi kullanabilir.

**S: Observable nedir, Promise'dan farkı ne?**
C: Promise tek seferlik, Observable sürekli veri akışı sağlar. Angular HTTP Client Observable döner. `.subscribe()` ile dinlenir.

**S: Doctrine ORM neden kullanılır?**
C: SQL yazmadan PHP nesneleriyle veritabanı işlemi yapmak için. `$product->setName()` diyoruz, Doctrine SQL'e çeviriyor.

**S: CORS nedir?**
C: Tarayıcı güvenlik mekanizması. Farklı origin'ler (domain/port) arası istekleri kontrol eder. Backend'de izin vermemiz gerekti.

---

### 📋 QUICK REFERENCE

```bash
# Backend başlat
symfony serve --no-tls

# Frontend başlat
cd frontend && ng serve

# Migration oluştur
php bin/console make:migration

# Migration çalıştır
php bin/console doctrine:migrations:migrate

# Cache temizle
php bin/console cache:clear
```

**URL'ler:**
- Frontend: http://localhost:4200
- Backend API: http://localhost:8000/api/products
- Symfony Profiler: http://localhost:8000/_profiler

---

## 🚀 PROJEYİ BAŞLATMA KOMUTLARI

Her seferinde projeyi açmak için bu komutları **sırasıyla** çalıştır:

### 1️⃣ PostgreSQL Veritabanı (Port 5432)
```powershell
& "C:\Program Files\PostgreSQL\18\bin\pg_ctl.exe" -D "C:\Program Files\PostgreSQL\18\data" -l "$env:USERPROFILE\pg_logfile.txt" start
```

### 2️⃣ Symfony Backend (Port 8000)
```powershell
cd "c:\Users\filiz\OneDrive\Masaüstü\SymfonyProjeler\benim-projem"
symfony serve --no-tls
```

### 3️⃣ Angular Frontend (Port 4200)
**Yeni terminal aç ve:**
```powershell
cd "c:\Users\filiz\OneDrive\Masaüstü\SymfonyProjeler\benim-projem\frontend"
ng serve
```

---

## 🛑 DURDURMA KOMUTLARI

### PostgreSQL Durdur:
```powershell
& "C:\Program Files\PostgreSQL\18\bin\pg_ctl.exe" -D "C:\Program Files\PostgreSQL\18\data" stop
```

### Symfony/Angular Durdur:
Terminalde `Ctrl+C` tuşlarına bas.

---

## 🌐 ERİŞİM URL'LERİ

| Servis | URL | Açıklama |
|--------|-----|----------|
| **Frontend** | http://localhost:4200 | Angular uygulaması |
| **Backend API** | http://localhost:8000/api/products | Ürün listesi API |
| **Kategoriler API** | http://localhost:8000/api/categories | Kategori listesi API |
| **Symfony Profiler** | http://localhost:8000/_profiler | Debug aracı |




