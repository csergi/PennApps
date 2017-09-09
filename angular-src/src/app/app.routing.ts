import { Routes, RouterModule } from '@angular/router';
import { HomeComponent } from './components/home/home.component';

const ROUTES : Routes = [
  { path: "", redirectTo: "/home", pathMatch: "full" },
  { path: "home", component: HomeComponent }
];

export const APP_ROUTING = RouterModule.forRoot(ROUTES);
