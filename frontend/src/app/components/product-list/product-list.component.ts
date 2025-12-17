import { Component, OnInit, inject, ChangeDetectorRef } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { ProductService } from '../../services/product.service';
import { Product, Category } from '../../models/product.model';

@Component({
  selector: 'app-product-list',
  standalone: true,
  imports: [CommonModule, FormsModule],
  templateUrl: './product-list.component.html',
  styleUrl: './product-list.component.css'
})
export class ProductListComponent implements OnInit {
  private productService = inject(ProductService);
  private cdr = inject(ChangeDetectorRef);
  
  // Veriler
  products: Product[] = [];
  categories: Category[] = [];
  
  // Form verileri
  showForm = false;
  editMode = false;
  currentProduct: any = {
    name: '',
    description: '',
    price: 0,
    stock: 0,
    categoryId: 0
  };
  editingProductId: number | null = null;

  // Filtre
  selectedCategoryId: number | null = null;

  // Durum
  loading = false;
  error = '';
  successMessage = '';

  constructor() {}

  ngOnInit(): void {
    this.loadProducts();
    this.loadCategories();
  }

  // ===== VERİ YÜKLEME =====
  
  loadProducts(): void {
    this.loading = true;
    this.error = '';
    
    const observable = this.selectedCategoryId
      ? this.productService.getProductsByCategory(this.selectedCategoryId)
      : this.productService.getProducts();

    observable.subscribe({
      next: (data) => {
        this.products = data;
        this.loading = false;
        this.cdr.detectChanges();
      },
      error: (err) => {
        this.error = 'Ürünler yüklenirken hata oluştu: ' + err.message;
        this.loading = false;
        this.cdr.detectChanges();
      }
    });
  }

  loadCategories(): void {
    this.productService.getCategories().subscribe({
      next: (data) => {
        this.categories = data;
        this.cdr.detectChanges();
      },
      error: (err) => {
        console.error('Kategoriler yüklenemedi:', err);
      }
    });
  }

  // ===== FİLTRELEME =====

  filterByCategory(): void {
    this.loadProducts();
  }

  clearFilter(): void {
    this.selectedCategoryId = null;
    this.loadProducts();
  }

  // ===== FORM İŞLEMLERİ =====

  openAddForm(): void {
    this.showForm = true;
    this.editMode = false;
    this.currentProduct = {
      name: '',
      description: '',
      price: 0,
      stock: 0,
      categoryId: this.categories.length > 0 ? this.categories[0].id : 0
    };
  }

  openEditForm(product: Product): void {
    this.showForm = true;
    this.editMode = true;
    this.editingProductId = product.id;
    this.currentProduct = {
      name: product.name,
      description: product.description,
      price: parseFloat(product.price),
      stock: product.stock,
      categoryId: product.category.id
    };
  }

  closeForm(): void {
    this.showForm = false;
    this.editMode = false;
    this.editingProductId = null;
    this.currentProduct = { name: '', description: '', price: 0, stock: 0, categoryId: 0 };
  }

  // ===== CRUD İŞLEMLERİ =====

  saveProduct(): void {
    if (!this.currentProduct.name || !this.currentProduct.categoryId) {
      this.error = 'Ürün adı ve kategori zorunludur!';
      this.cdr.detectChanges();
      return;
    }

    this.loading = true;

    if (this.editMode && this.editingProductId) {
      // Güncelleme
      this.productService.updateProduct(this.editingProductId, this.currentProduct).subscribe({
        next: () => {
          this.successMessage = 'Ürün güncellendi!';
          this.closeForm();
          this.loadProducts();
          this.cdr.detectChanges();
          setTimeout(() => { this.successMessage = ''; this.cdr.detectChanges(); }, 3000);
        },
        error: (err) => {
          this.error = 'Güncelleme hatası: ' + err.message;
          this.loading = false;
          this.cdr.detectChanges();
        }
      });
    } else {
      // Yeni ekleme
      this.productService.createProduct(this.currentProduct).subscribe({
        next: () => {
          this.successMessage = 'Ürün eklendi!';
          this.closeForm();
          this.loadProducts();
          this.cdr.detectChanges();
          setTimeout(() => { this.successMessage = ''; this.cdr.detectChanges(); }, 3000);
        },
        error: (err) => {
          this.error = 'Ekleme hatası: ' + err.message;
          this.loading = false;
          this.cdr.detectChanges();
        }
      });
    }
  }

  deleteProduct(id: number): void {
    if (confirm('Bu ürünü silmek istediğinize emin misiniz?')) {
      this.productService.deleteProduct(id).subscribe({
        next: () => {
          this.successMessage = 'Ürün silindi!';
          this.loadProducts();
          this.cdr.detectChanges();
          setTimeout(() => { this.successMessage = ''; this.cdr.detectChanges(); }, 3000);
        },
        error: (err) => {
          this.error = 'Silme hatası: ' + err.message;
          this.cdr.detectChanges();
        }
      });
    }
  }

  // Kategori adını ID'den bul
  getCategoryName(categoryId: number): string {
    const category = this.categories.find(c => c.id === categoryId);
    return category ? category.name : '';
  }
}
