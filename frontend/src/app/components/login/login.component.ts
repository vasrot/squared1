import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { Router } from '@angular/router';
import { AuthService } from 'src/app/services/auth.service';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss']
})
export class LoginComponent implements OnInit {

  loginForm: FormGroup;
  dataLoading: boolean = false;
  server_response: string = '';

  constructor(
    private fb: FormBuilder,
    private authService: AuthService,
    private router: Router,
  ) {
    this.loginForm = this.fb.group({
      email: [ '', [Validators.required, Validators.minLength(3), Validators.email]],
      password: [ '', [Validators.required]]
    });
    this.server_response = '';
  }

  ngOnInit(): void {
    this.server_response = '';
  }

  loginUser() {
    if (this.loginForm.invalid) { return }
    this.dataLoading = true;
    this.authService.login(this.loginForm.value).subscribe(
      (response:any) => {
        console.log(response);
        localStorage.setItem('token', response.token);
        localStorage.setItem('user_id', response.user.id);
        this.dataLoading = false;
        this.router.navigate(['private']);
      },
      error => {
        console.log(error);
        this.dataLoading = false;
        this.server_response = error.error;
      }
    );
  }

}
