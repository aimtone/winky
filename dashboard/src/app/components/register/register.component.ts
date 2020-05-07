import { Component, OnInit } from '@angular/core';
import { UserService } from 'src/app/services/user.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-register',
  templateUrl: './register.component.html',
  styleUrls: ['./register.component.css']
})
export class RegisterComponent implements OnInit {
  name: string;
  lastname: string;
  email: string;
  password: string;


  constructor(public userService: UserService, public router: Router) { }

  ngOnInit(): void {
  }

  register() {
    const user = {name: this.name, lastname : this.lastname, email: this.email, password: this.password };
    this.userService.register(user).subscribe(data => {
      console.log(data);
      this.userService.setToken(data.data.token);
      this.router.navigateByUrl('/');
    }, error => {
      console.log(error);
    });
  }
}
