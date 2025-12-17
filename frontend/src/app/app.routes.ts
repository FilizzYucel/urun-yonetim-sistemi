import { Routes } from '@angular/router';
import { ProductListComponent } from './components/product-list/product-list.component';
import { CategoryListComponent } from './components/category-list/category-list.component';

export const routes: Routes = [
  { path: '', redirectTo: '/products', pathMatch: 'full' },
  { path: 'products', component: ProductListComponent },
  { path: 'categories', component: CategoryListComponent }
];
