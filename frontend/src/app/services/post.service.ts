import { HttpHeaders, HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { environment } from 'src/environments/environment';
import { FormBuilder } from '@angular/forms';

@Injectable({
  providedIn: 'root',
})
export class PostService {
  private URL = environment.api_url + '/post';
  private headers = new HttpHeaders().set('Accept', 'application/json');

  constructor(private http: HttpClient) {}

  getPosts() {
    const headers = this.headers;
    return this.http.get<any>(this.URL, {
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
