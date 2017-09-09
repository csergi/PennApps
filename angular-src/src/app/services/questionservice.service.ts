import { Injectable } from '@angular/core';
import { Http, Headers } from '@angular/http';
import 'rxjs/add/operator/map';

@Injectable()
export class QuestionService {

  constructor(private http : Http) { }

  askQuestion(questionObj){
    let headers = new Headers();
    headers.append("Content-Type", "application/json");
    return this.http.post("http://????", questionObj, { headers: headers });
  }

  replyToQuestion(replyObj){
    let headers = new Headers();
    headers.append("Content-Type", "application/json");
    return this.http.post("http://????", replyObj, { headers: headers });
  }

}
