import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { PostService } from 'src/app/services/post.service';
import { UserService } from 'src/app/services/user.service';

@Component({
  selector: 'app-private',
  templateUrl: './private.component.html',
  styleUrls: ['./private.component.scss']
})
export class PrivateComponent implements OnInit {

  posts: any;
  postForm: FormGroup;
  dataLoading: boolean = false;
  server_response: string = '';

  constructor(
    private userService: UserService,
    private postService: PostService,
    private fb: FormBuilder,
  ) {
    this.postForm = this.fb.group({
      title: [ '', [Validators.required, ]],
      description: [ '', [Validators.required]]
    });
    this.server_response = '';
  }

  ngOnInit(): void {
    this.getPosts('sort_by_desc=publication_date');
  }

  getPosts(filter = '') {
    let user_id = localStorage.getItem('user_id');
    let id = user_id ? parseInt(user_id) : 1;
    this.userService.getPosts(id, filter).subscribe(
      response => {
        this.posts = response;
        console.log('User posts ->', this.posts);
      }
    );
  }

  post() {
    if (this.postForm.invalid) { return }
    this.dataLoading = true;
    this.postService.addPost(this.postForm.value).subscribe(
      (response:any) => {
        console.log(response);
        this.dataLoading = false;
        window.location.reload();
      },
      error => {
        console.log(error);
        this.dataLoading = false;
        this.server_response = error.error;
      }
    );
  }

}
