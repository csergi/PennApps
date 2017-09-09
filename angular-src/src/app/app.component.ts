import { Component } from '@angular/core';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})
export class AppComponent {
  private title : string;
  private name : string = "Zong";
  private username : string = "No initialized value for username";

  click(){
    this.name = "Bob";
  }

  displayUsername(){
    console.log(this.username);
  }

}
