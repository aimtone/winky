import { Injectable } from '@angular/core';
import { Socket } from 'ngx-socket-io';
import 'rxjs/Rx';

@Injectable({
  providedIn: 'root',
})
export class SocketService {
  constructor(private socket: Socket) {}

  addClient() {
    return new Promise((resolve, reject) => {
      try {
        this.socket.emit("add user", async (callback) => {
          resolve(callback);
        })
      } catch (error) {
        reject(error);
      }
      
    });
  }

  sendMessage(data: any) {
    return new Promise((resolve, reject) => {
      try {
        this.socket.emit("new message", data, async (callback) => {
          resolve(callback);
        })
      } catch (error) {
        reject(error);
      }
    });
  }

  getMessage() {
    return this.socket.fromEvent('message').map((data) => data);
  }
}
