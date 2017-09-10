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
  private googleUrl : string;

  constructor(private qService : QuestionService) { }

  ngOnInit() {
    if(!this.isLoggedIn()){
      this.getGoogleLink();
    }
  }

  // Still need to separate these tags
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

  isLoggedIn(){
    this.qService.requestUserInfo().subscribe(result => {
      console.log(typeof result);
      if(result.json().success == true){
        return true; // Can get user info
      } else {
        return false; // Cannot get user info
      }
    });
  }

  getGoogleLink(){
    this.qService.getGoogleAccLink().subscribe(resObj => {
      if(resObj.json().success){
        console.log(resObj.json());
        this.googleUrl = resObj.json().url;
        window.location.href = this.googleUrl;
      } else {
        console.log("Something went wrong; the API for the Google Sign returned 'success' : 'false'");
      }
    });
  }
}
