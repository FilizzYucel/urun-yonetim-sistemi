import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { map } from 'rxjs/operators';
import { Product, Category, ApiResponse } from '../models/product.model';

@Injectable({
  providedIn: 'root'
})
export class ProductService {
  // Symfony backend URL
  private apiUrl = 'http://localhost:8000/api';

  constructor(private http: HttpClient) {}

  // ===== ÜRÜN İŞLEMLERİ =====

  // Tüm ürünleri getir
  getProducts(): Observable<Product[]> {
    return this.http.get<ApiResponse<Product[]>>(`${this.apiUrl}/products`)
      .pipe(map(response => response.data));
  }

  // Tek ürün getir
  getProduct(id: number): Observable<Product> {
    return this.http.get<ApiResponse<Product>>(`${this.apiUrl}/products/${id}`)
      .pipe(map(response => response.data));
  }

  // Kategoriye göre ürün filtrele
  getProductsByCategory(categoryId: number): Observable<Product[]> {
    return this.http.get<ApiResponse<Product[]>>(`${this.apiUrl}/products?category=${categoryId}`)
      .pipe(map(response => response.data));
  }

  // Yeni ürün oluştur
  createProduct(product: {
    name: string;
    description: string;
    price: number;
    stock: number;
    categoryId: number;
  }): Observable<Product> {
    // Backend category_id bekliyor
    const payload = {
      name: product.name,
      description: product.description,
      price: product.price,
      stock: product.stock,
      category_id: product.categoryId
    };
    return this.http.post<ApiResponse<Product>>(`${this.apiUrl}/products`, payload)
      .pipe(map(response => response.data));
  }

  // Ürün güncelle
  updateProduct(id: number, product: Partial<{
    name: string;
    description: string;
    price: number;
    stock: number;
    categoryId: number;
  }>): Observable<Product> {
    // Backend category_id bekliyor
    const payload: any = { ...product };
    if (product.categoryId !== undefined) {
      payload.category_id = product.categoryId;
      delete payload.categoryId;
    }
    return this.http.put<ApiResponse<Product>>(`${this.apiUrl}/products/${id}`, payload)
      .pipe(map(response => response.data));
  }

  // Ürün sil
  deleteProduct(id: number): Observable<any> {
    return this.http.delete<ApiResponse<any>>(`${this.apiUrl}/products/${id}`);
  }

  // ===== KATEGORİ İŞLEMLERİ =====

  // Tüm kategorileri getir
  getCategories(): Observable<Category[]> {
    return this.http.get<ApiResponse<Category[]>>(`${this.apiUrl}/categories`)
      .pipe(map(response => response.data));
  }

  // Tek kategori getir
  getCategory(id: number): Observable<Category> {
    return this.http.get<ApiResponse<Category>>(`${this.apiUrl}/categories/${id}`)
      .pipe(map(response => response.data));
  }

  // Yeni kategori oluştur
  createCategory(category: { name: string; description?: string }): Observable<Category> {
    return this.http.post<ApiResponse<Category>>(`${this.apiUrl}/categories`, category)
      .pipe(map(response => response.data));
  }

  // Kategori güncelle
  updateCategory(id: number, category: Partial<{ name: string; description: string }>): Observable<Category> {
    return this.http.put<ApiResponse<Category>>(`${this.apiUrl}/categories/${id}`, category)
      .pipe(map(response => response.data));
  }

  // Kategori sil
  deleteCategory(id: number): Observable<any> {
    return this.http.delete<ApiResponse<any>>(`${this.apiUrl}/categories/${id}`);
  }
}
