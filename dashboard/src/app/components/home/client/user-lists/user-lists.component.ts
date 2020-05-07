import { Component, OnInit, Input } from '@angular/core';
import { SocketService } from 'src/app/services/socket.service';

@Component({
  selector: 'app-user-lists',
  templateUrl: './user-lists.component.html',
  styleUrls: ['./user-lists.component.css']
})
export class UserListsComponent implements OnInit {
  @Input() class: string;
  constructor(public socketService: SocketService,) { }

  ngOnInit(): void {
  }

  selectConversation(data) {
    console.log(data);
    this.socketService.getConversation = data;
  }

}
