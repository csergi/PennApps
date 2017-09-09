import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-question-item',
  templateUrl: './question-item.component.html',
  styleUrls: ['./question-item.component.css']
})
export class QuestionItemComponent implements OnInit {

  private questionTitle : string = "Multi-threading in Java";
  private views : number = 150;
  private answers : number = 2;
  private votes : number = 0;
  private inquirer : string = "Jake Doe";
  private id : number = 123456;

  constructor() { }

  ngOnInit() {

  }

}
