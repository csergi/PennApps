import { Component, OnInit } from '@angular/core';
import { QuestionService } from '../../services/questionservice.service';

@Component({
  selector: 'app-ask-question',
  templateUrl: './ask-question.component.html',
  styleUrls: ['./ask-question.component.css']
})
export class AskQuestionComponent implements OnInit {

  private warning : boolean = true;
  private questionTitle : string;
  private questionBody : string;
  private tagsString : string;
  private tagsArray = [];
  private prevWord : string;

  constructor(private qService : QuestionService) { }

  ngOnInit() {
  }

  separateTags(event){
    if(event.keyCode == 32){
      if(this.prevWord){
        this.tagsString.indexOf(this.prevWord);
        this.tagsArray.push(this.tagsString);
        this.prevWord = this.tagsArray[this.tagsArray.length - 1];
      }
    }
    console.log(this.tagsArray);
  }

  askQuestion(){
    console.log(this.tagsString);
    var questionObj = {
      "request" : "post",
      "body" : this.questionBody,
      "type" : 0
    };
  }

}
