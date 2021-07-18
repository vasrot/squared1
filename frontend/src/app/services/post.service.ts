import { HttpHeaders, HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { environment } from 'src/environments/environment';
import { FormBuilder } from '@angular/forms';
import { AuthService } from './auth.service';

@Injectable({
  providedIn: 'root',
})
export class PostService {
  private URL = environment.api_url + '/post';
  private headers = new HttpHeaders()
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + this.authService.getToken());

  constructor(private http: HttpClient, private authService: AuthService) {}

  getPosts(filter = '') {
    const headers = this.headers;
    return this.http.get<any>(`${this.URL}?${filter}`, {
      headers,
    });
  }

  addPost(form: FormBuilder) {
    const headers = this.headers;
    return this.http.post<any>(this.URL, form, {
      headers,
    });
  }
}
