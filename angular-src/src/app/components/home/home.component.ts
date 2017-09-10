import { Component, OnInit } from '@angular/core';
import { QuestionService } from '../../services/questionservice.service';
import { Router, ActivatedRoute, Params } from '@angular/router';

@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.css']
})
export class HomeComponent implements OnInit {

  private userID : string;
  private questionArray = [];

  constructor(private qService : QuestionService, private activatedRoute : ActivatedRoute) { }

  ngOnInit() {
    this.qService.getQuestionList().subscribe(res => {
      if(res.json().success){
        console.log(res.json());
        this.questionArray = res.json().data;
      }
    });


    if(this.checkLoggedIn()){
      console.log("True");
    }
    let uid = this.activatedRoute.snapshot.queryParams["uid"];
    if(uid){
      this.userID = uid;
      console.log(uid);
      this.qService.queryUserInfo(this.userID);
    }
  }

  checkLoggedIn(){
    this.qService.requestUserInfo().subscribe(res => {
      console.log(res.json());
      if (res.json().success){
        console.log(res.json());
        return true;
      } else {
        console.log("Success is false; did not sign in with Google");
        return false;
      }
    });
  }
}
