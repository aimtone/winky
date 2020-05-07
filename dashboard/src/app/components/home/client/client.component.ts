import { Component, OnInit } from '@angular/core';
import { SocketService } from 'src/app/services/socket.service';
import { v4 as uuidv4 } from 'uuid';
import { Socket } from 'ngx-socket-io';
import { AuthGuardService } from 'src/app/services/auth-guard.service';

@Component({
  selector: 'app-client',
  templateUrl: './client.component.html',
  styleUrls: ['./client.component.css'],
})
export class ClientComponent implements OnInit {
  selectedConversation = null;
  getConversation = null;
  message = '';
  constructor(
    public socketService: SocketService,
    private socket: Socket,
    public authGuardService: AuthGuardService
  ) {}

  ngOnInit(): void {
    this.socketService.getClientsConnected();

    this.socket.on('new user', (data) => {
      this.socketService.connectedClients.push(data);
    });

    this.socket.on('new client message', (data) => {
      let client = this.socketService.connectedClients.filter(
        (element) => element.ip == data.username.ip
      );

      if (client.length != 0) {
        if (client[0].conversation.length == 0) {
          client[0].conversation.push(data.data);
        } else {
          let search_time = client[0].conversation.filter(
            (element) => data.data.time == element.time
          );
          if (search_time.length != 0) {
            search_time[0].messages.push(data.data.messages[0]);
          } else {
            client[0].conversation.push(data.data);
          }
        }
      }
    });
  }

 

  async sendMessage(e) {
    if (e.type == 'click') {
      //presiona enbootn
    }
    if (e.type == 'keypress' && e.keyCode == 13) {
      if (this.message != '') {
        let payload = {
          messages: [
            {
              text: this.message,
              guid: uuidv4(),
              status: 1,
            },
          ],
          sender: 'operator',
          name:
            this.authGuardService.loggedUser.name +
            ' ' +
            this.authGuardService.loggedUser.lastname,
          time: '',
          type: 'message',
          image: '',
          guid: uuidv4(),
          to: this.socketService.getConversation.ip,
        };

        this.socketService.getConversation.conversation.push(payload);

        this.message = '';

        let data = await this.socketService.sendMessage(payload);
        if (data['success']) {
          console.log(data)
          e = this.socketService.getConversation.conversation.filter(
            (element) => element.guid == data['data'].guid
          );
          let time_exist = this.socketService.getConversation.conversation.filter(
            (element) => element.time == data['data'].time
          );

          setTimeout(() => {
            if (time_exist.length == 0) {
              Object.assign(e[0], data['data']);
            } else {
              let index = this.socketService.getConversation.conversation.findIndex(
                (element) => element.guid == data['data'].guid
              );
              this.socketService.getConversation.conversation.splice(index, 1);
              time_exist[0].messages.push(data['data'].messages[0]);
            }
          }, 1000);
        }
      }
    }
  }
}
