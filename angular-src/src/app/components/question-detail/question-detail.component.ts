import { Component, OnInit } from '@angular/core';
import { slideUpDown } from '../../_animations/slideup.animation';

@Component({
  selector: 'app-question-detail',
  templateUrl: './question-detail.component.html',
  styleUrls: ['./question-detail.component.css'],
  animations: [slideUpDown],
  host: {
    "[@slideUpDown]" : ""
  }
})
export class QuestionDetailComponent implements OnInit {

  private qThumbCount : number = 13;
  private hasThumbed : boolean = false;


  private questionTitle : string = "Multi-threading in Java";
  private questionBody : string = "The following is my layout xml. The problem now is that the BottomNavigationView is overlapping the FrameLayout. I wanted the FrameLayout to stretch to the top of the BottomNavigationView. I tried with trick such as adding paddingBottom in the FrameLayout but I wonder if there is other better solution. Thanks. The following is my layout xml. The problem now is that the BottomNavigationView is overlapping the FrameLayout. I wanted the FrameLayout to stretch to the top of the BottomNavigationView. I tried with trick such as adding paddingBottom in the FrameLayout but I wonder if there is other better solution. Thanks. The following is my layout xml. The problem now is that the BottomNavigationView is overlapping the FrameLayout. I wanted the FrameLayout to stretch to the top of the BottomNavigationView. I tried with trick such as adding paddingBottom in the FrameLayout but I wonder if there is other better solution. Thanks. I tried with trick suchas ad";
  
  private starred : boolean = false;

  private ansArray = [];

  private user : string = "Jane Doe";
  private qNumGold : number = 14;
  private qNumSilver : number = 5;
  private qNumBronze : number = 9;

  private dateInquired : string = "Oct. 10th, 2016";
  private numViews : number = 14327;
  private lastActive : number = 2;

  private numAns : number = 3;

  private submittedAns : string;

  constructor() { }

  ngOnInit() {
    this.ansArray.push("I'm not sure whether this is a complete answer to this question, but my problem was very similar - I had to process back button press and bring user to previous tab where he was. So, maybe my solution will be useful for somebody: Please, keep in mind that if user press other navigation tab BottomNavigationView won't clear currently selected item, so you need to call this method in your onNavigationItemSelected after processing of navigation action: I'm not sure whether this is a complete answer to this question, but my problem was very similar - I had to process back button press and bring user to previous tab where he was. So, maybe my solution will be useful for somebody:");

    this.ansArray.push("HELLO HELLO HELLO HELLO HELLO - I had to process back button press and bring user to previous tab where he was. So, maybe my solution will be useful for somebody: Please, keep in mind that if user press other navigation tab BottomNavigationView won't clear currently selected item, so you need to call this method in your onNavigationItemSelected after processing of navigation action: I'm not sure whether this is a complete answer to this question, but my problem was very similar - I had to process back button press and bring user to previous tab where he was. So, maybe my solution will be useful for somebody:");

  }

  submitAnswer(){
    this.ansArray.push(this.submittedAns);
  }

}
