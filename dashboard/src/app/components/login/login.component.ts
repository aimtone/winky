import { Component, OnInit } from '@angular/core';
import { UserService } from 'src/app/services/user.service';
import { Router } from '@angular/router';
import { TranslateService } from '@ngx-translate/core';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css'],
})
export class LoginComponent implements OnInit {
  email: string;
  password: string;
  messageError: string = '';

  constructor(
    public userService: UserService,
    public router: Router,
    private translate: TranslateService
  ) {
    translate.setDefaultLang('en');
    this.userService.headers = this.userService.headers.append(
      'X-localization',
      'en'
    );
  }

  ngOnInit(): void {}

  changeLocale(language: string) {
    this.messageError = '';
    this.translate.use(language);
    this.userService.headers = this.userService.headers.set(
      'X-localization',
      language
    );
  }

  login() {
    const user = { email: this.email, password: this.password };
    this.userService.login(user).subscribe(
      (data) => {
        console.log(data);
        if (data.ok) {
          this.userService.setToken(data.data.token);
          this.router.navigateByUrl('/');
        } else {
          this.messageError = data.message;
        }
      },
      (error) => {
        console.log(error);
      }
    );
  }
}
