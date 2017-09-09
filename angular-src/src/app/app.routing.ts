import { Routes, RouterModule } from '@angular/router';
import { HomeComponent } from './components/home/home.component';
import { QuestionDetailComponent } from './components/question-detail/question-detail.component';

const ROUTES : Routes = [
  { path: "", redirectTo: "/home", pathMatch: "full" },
  { path: "home", component: HomeComponent },
  { path: "question/details/:id", component: QuestionDetailComponent }
];

export const APP_ROUTING = RouterModule.forRoot(ROUTES);
