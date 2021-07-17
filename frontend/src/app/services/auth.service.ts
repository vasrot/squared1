import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { environment } from '../../environments/environment';
import { FormBuilder } from '@angular/forms';

const API_URL = environment.api_url;
const headers = new HttpHeaders();

@Injectable({
  providedIn: 'root'
})
export class AuthService {

  constructor(
    private http: HttpClient,
  ) {
  }

  getToken() {
    return localStorage.getItem("token");
  }

  register(form: FormBuilder) {
    return this.http.post(`${API_URL}/register`, form, {
      headers: headers
    });
  }

  login(form: FormBuilder) {
    return this.http.post(`${API_URL}/login`, form, {
      headers: headers
    });
  }

}
