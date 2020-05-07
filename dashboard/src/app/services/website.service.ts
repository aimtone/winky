import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { UserService } from './user.service';
import { catchError } from 'rxjs/operators';
import { TranslateService } from '@ngx-translate/core';

@Injectable({
  providedIn: 'root'
})
export class WebsiteService {

  /*headers = new HttpHeaders({
    'Accept': 'application/json'
  });
  
  constructor(private http: HttpClient, public userService: UserService, private translate: TranslateService) { }

  getUserWebsites(): Observable<any> {
    return this.http
      .get<any>('http://127.0.0.1:8081/api/v1/getUser', {
        headers: new HttpHeaders({
          'Accept': 'application/json',
          'Authorization' : this.userService.getToken(),
          'X-localization' : this.translate.getDefaultLang()
        }),
      })
      .pipe(catchError(this.userService.handleError));
  }*/
}
