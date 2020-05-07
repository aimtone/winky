import { Component, OnInit } from '@angular/core';
import { AuthGuardService } from 'src/app/services/auth-guard.service';
import { UserService } from 'src/app/services/user.service';
import { Router } from '@angular/router';
import { SocketService } from 'src/app/services/socket.service';

@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.css'],
})
export class HomeComponent implements OnInit {
  loggedUser = null;
  selectedWebsite = null;

  constructor(
    private socketService: SocketService,
    public authGuardService: AuthGuardService,
    public userService: UserService,
    public router: Router
  ) {
    this.loggedUser = authGuardService.loggedUser;
    this.selectedWebsite = authGuardService.selectedWebsite;
  }

  async ngOnInit() {
    let addClient = await this.socketService.addClient(
      this.selectedWebsite.uuid
    );
  }

  selectWebsite(website) {
    this.selectedWebsite = website;
  }
  async logout() {
    await this.userService.logout().toPromise();
    this.router.navigate(['login']);
  }
}
