import { Component, OnInit } from '@angular/core';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';
import { Router } from '@angular/router';
import { AuthService } from 'src/app/services/auth.service';

@Component({
  selector: 'app-register',
  templateUrl: './register.component.html',
  styleUrls: ['./register.component.scss']
})
export class RegisterComponent implements OnInit {

  registerForm: FormGroup;
  dataLoading: boolean = false;
  server_response: string = '';

  constructor(
    private fb: FormBuilder,
    private authService: AuthService,
    private router: Router,
  ) {
    this.registerForm = this.fb.group({
      name: [ '', [Validators.required, ]],
      email: [ '', [Validators.required, Validators.minLength(3), Validators.email]],
      password: [ '', [Validators.required, Validators.minLength(6)]],
      password_confirmation: [ '', [Validators.required, Validators.minLength(6)]],
    });
    this.server_response = '';
  }

  ngOnInit(): void {
    this.server_response = '';
  }

  register() {
    if (this.registerForm.invalid) { return }
    this.dataLoading = true;
    this.authService.register(this.registerForm.value).subscribe(
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
