import { Injectable } from '@angular/core';
import { Socket } from 'ngx-socket-io';
import { map } from 'rxjs/operators';

@Injectable({
  providedIn: 'root',
})
export class SocketService {

  connectedClients = null;
  getConversation = null;

  constructor(private socket: Socket) {}

  addClient(room: string) {
    return new Promise((resolve, reject) => {
      try {
        this.socket.emit(
          'add user',
          { room: room, operator: true },
          async (callback: unknown) => {
            resolve(callback);
          }
        );
      } catch (error) {
        reject(error);
      }
    });
  }

  sendMessage(data: any) {
    return new Promise((resolve, reject) => {
      try {
        this.socket.emit('new message', data, async (callback: unknown) => {
          resolve(callback);
        });
      } catch (error) {
        reject(error);
      }
    });
  }

  getClientsConnected() {
    this.socket.emit('get connected clients', async (callback: unknown) => {
      this.connectedClients = callback;
    });
  }
}
