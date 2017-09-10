import { Routes, RouterModule } from '@angular/router';
import { HomeComponent } from './components/home/home.component';
import { QuestionDetailComponent } from './components/question-detail/question-detail.component';
import { AskQuestionComponent } from './components/ask-question/ask-question.component';

const ROUTES : Routes = [
  { path: "", component: HomeComponent },
  { path: "home", component: HomeComponent },
  { path: "question/details/:id", component: QuestionDetailComponent },
  { path: "askquestion", component: AskQuestionComponent }
];

export const APP_ROUTING = RouterModule.forRoot(ROUTES);
