// Ürün modeli - API'den gelen veri yapısını tanımlar
export interface Product {
  id: number;
  name: string;
  description: string;
  price: string;
  stock: number;
  category: Category;
  createdAt: string;
}

// Kategori modeli
export interface Category {
  id: number;
  name: string;
  description?: string;
}

// API yanıt yapısı
export interface ApiResponse<T> {
  success: boolean;
  data: T;
  count?: number;
  message?: string;
}
