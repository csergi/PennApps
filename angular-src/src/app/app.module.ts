import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { HttpModule } from '@angular/http';
import { FormsModule } from '@angular/forms';
import { RouterModule, Routes } from '@angular/router';
import { AppComponent } from './app.component';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';

import { APP_ROUTING } from './app.routing';
import { HomeComponent } from './components/home/home.component';
import { NavbarComponent } from './components/navbar/navbar.component';
import { QuestionItemComponent } from './components/question-item/question-item.component';
import { QuestionDetailComponent } from './components/question-detail/question-detail.component';
import { AskQuestionComponent } from './components/ask-question/ask-question.component';
import { QuestionService } from './services/questionservice.service';

@NgModule({
  declarations: [
    AppComponent,
    HomeComponent,
    NavbarComponent,
    QuestionItemComponent,
    QuestionDetailComponent,
    AskQuestionComponent
  ],
  imports: [
    BrowserModule,
    APP_ROUTING,
    HttpModule,
    FormsModule,
    BrowserAnimationsModule,
  ],
  providers: [ QuestionService, ],
  bootstrap: [AppComponent]
})
export class AppModule { }
