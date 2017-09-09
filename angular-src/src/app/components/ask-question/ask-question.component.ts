import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-ask-question',
  templateUrl: './ask-question.component.html',
  styleUrls: ['./ask-question.component.css']
})
export class AskQuestionComponent implements OnInit {

  private warning : boolean = true;
  private questionTitle : string;
  private questionBody : string;

  constructor() { }

  ngOnInit() {
  }

}
