import { Injectable } from '@angular/core';
import {
  HttpClient,
  HttpHeaders,
  HttpErrorResponse,
} from '@angular/common/http';
import { Observable, throwError } from 'rxjs';
import { CookieService } from 'ngx-cookie-service';
import { catchError } from 'rxjs/operators';
import { TranslateService } from '@ngx-translate/core';

@Injectable({
  providedIn: 'root',
})
export class UserService {
  headers = new HttpHeaders({
    'Accept': 'application/json'
  });

  constructor(
    private http: HttpClient,
    private cookies: CookieService,
    private translate: TranslateService
  ) {}

  login(user: any): Observable<any> {
    return this.http.post('http://192.168.1.94:8081/api/v1/user/login', user, {
      headers: this.headers,
    });
  }
  register(user: any): Observable<any> {
    return this.http.post('http://192.168.1.94:8081/api/v1/user/register', user);
  }
  setToken(token: string) {
    this.cookies.set('token', 'Bearer ' + token);
  }
  getToken() {
    return this.cookies.get('token');
  }
  getUser(): Observable<any> {
    return this.http
      .get<any>('http://192.168.1.94:8081/api/v1/getUser', {
        headers: new HttpHeaders({
          'Accept': 'application/json',
          'Authorization' : this.getToken()
        }),
      })
      .pipe(catchError(this.handleError));
  }
  logout(): Observable<any> {
    this.headers.set('Authorization', this.getToken());
    return this.http
      .get<any>('http://192.168.1.94:8081/api/v1/logout', {
        headers: new HttpHeaders({
          'Accept': 'application/json',
          'Authorization' : this.getToken()
        }),
      })
      .pipe(catchError(this.handleError));
  }
  public handleError(error: HttpErrorResponse) {
    if (error.error instanceof ErrorEvent) {
      console.error('An error occurred:', error.error.message);
    } else {
      console.error(error.status);
    }
    return throwError('An unexpected error has ocurred');
  }
}
