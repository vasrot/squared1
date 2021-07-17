import { HttpHeaders, HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { FormBuilder } from '@angular/forms';
import { environment } from 'src/environments/environment';
import { AuthService } from './auth.service';

@Injectable({
  providedIn: 'root',
})
export class UserService {
  private URL = environment.api_url + '/user';
  private headers = new HttpHeaders()
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + this.authService.getToken());

  constructor(private http: HttpClient, private authService: AuthService) {}

  /**
   *
   * @param user User id
   * @returns observable
   */
  getPosts(user: number) {
    const headers = this.headers;
    return this.http.get<any>(`${this.URL}/${user}/posts`, {
      headers,
    });
  }
}
