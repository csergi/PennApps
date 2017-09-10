import { Component, OnInit } from '@angular/core';
import { QuestionService } from '../../services/questionservice.service';
import { Router, ActivatedRoute, Params } from '@angular/router';


@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.css']
})
export class HomeComponent implements OnInit {

  constructor(private qService : QuestionService, private activatedRoute : ActivatedRoute) { }

  ngOnInit() {
    if(this.checkLoggedIn()){
      console.log("True");
    }
    this.activatedRoute.params.subscribe((params : Params) => {
      let uidVal =  params['uid'];
      console.log(uidVal);
    })
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
