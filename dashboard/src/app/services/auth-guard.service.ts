import { Injectable } from '@angular/core';
import { CanActivate, Router } from '@angular/router';
import { UserService } from './user.service';

@Injectable({
  providedIn: 'root'
})
export class AuthGuardService implements CanActivate {

  loggedUser : any = null;
  selectedWebsite: any = null;

  constructor(public userService: UserService, public router: Router) { }

  async canActivate(): Promise<boolean> {
    try {
      let data : any = await this.userService.getUser().toPromise();
      if(!data.ok) {
        this.router.navigate(['login']);
        return false;
      }
      this.loggedUser = data.data;
      if(data.data.websites.length >= 1) {
        this.selectedWebsite = data.data.websites[0];
      }
      return true;
    } catch (error) {
      this.router.navigate(['login']);
      return false;
    }
    
    
  }
}
