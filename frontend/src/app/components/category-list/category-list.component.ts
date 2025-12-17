import { Component, OnInit, inject, ChangeDetectorRef } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { ProductService } from '../../services/product.service';
import { Category } from '../../models/product.model';

@Component({
  selector: 'app-category-list',
  standalone: true,
  imports: [CommonModule, FormsModule],
  templateUrl: './category-list.component.html',
  styleUrl: './category-list.component.css'
})
export class CategoryListComponent implements OnInit {
  private productService = inject(ProductService);
  private cdr = inject(ChangeDetectorRef);

  categories: Category[] = [];
  
  showForm = false;
  editMode = false;
  currentCategory: { name: string; description: string } = { name: '', description: '' };
  editingCategoryId: number | null = null;

  loading = false;
  error = '';
  successMessage = '';

  ngOnInit(): void {
    this.loadCategories();
  }

  loadCategories(): void {
    this.loading = true;
    this.productService.getCategories().subscribe({
      next: (data) => {
        this.categories = data;
        this.loading = false;
        this.cdr.detectChanges();
      },
      error: (err) => {
        this.error = 'Kategoriler yüklenirken hata: ' + err.message;
        this.loading = false;
        this.cdr.detectChanges();
      }
    });
  }

  openAddForm(): void {
    this.showForm = true;
    this.editMode = false;
    this.currentCategory = { name: '', description: '' };
  }

  openEditForm(category: Category): void {
    this.showForm = true;
    this.editMode = true;
    this.editingCategoryId = category.id;
    this.currentCategory = {
      name: category.name,
      description: category.description || ''
    };
  }

  closeForm(): void {
    this.showForm = false;
    this.editMode = false;
    this.editingCategoryId = null;
    this.currentCategory = { name: '', description: '' };
  }

  saveCategory(): void {
    if (!this.currentCategory.name) {
      this.error = 'Kategori adı zorunludur!';
      this.cdr.detectChanges();
      return;
    }

    this.loading = true;

    if (this.editMode && this.editingCategoryId) {
      this.productService.updateCategory(this.editingCategoryId, this.currentCategory).subscribe({
        next: () => {
          this.successMessage = 'Kategori güncellendi!';
          this.closeForm();
          this.loadCategories();
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
      this.productService.createCategory(this.currentCategory).subscribe({
        next: () => {
          this.successMessage = 'Kategori eklendi!';
          this.closeForm();
          this.loadCategories();
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

  deleteCategory(id: number): void {
    if (confirm('Bu kategoriyi silmek istediğinize emin misiniz?')) {
      this.productService.deleteCategory(id).subscribe({
        next: () => {
          this.successMessage = 'Kategori silindi!';
          this.loadCategories();
          this.cdr.detectChanges();
          setTimeout(() => { this.successMessage = ''; this.cdr.detectChanges(); }, 3000);
        },
        error: (err) => {
          this.error = 'Silme hatası: ' + (err.error?.message || err.message);
          this.cdr.detectChanges();
        }
      });
    }
  }
}
