import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})
export class WinkyService {

  constructor(private http: HttpClient) { }

  configUrl = 'http://192.168.1.94:8081/api/v1/website/';

  getData(uuid: string) {
    return this.http.get(`${this.configUrl}${uuid}`);
  }

  
}
