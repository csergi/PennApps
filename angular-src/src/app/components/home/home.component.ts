import { Component, OnInit } from '@angular/core';
import { QuestionService } from '../../services/questionservice.service';

@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.css']
})
export class HomeComponent implements OnInit {

  constructor(private qService : QuestionService) { }

  ngOnInit() {
    if(this.checkLoggedIn()){
      console.log("True");
    }
  }

  checkLoggedIn(){
    this.qService.requestUserInfo().subscribe(res => {
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
