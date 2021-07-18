import { Component, OnInit } from '@angular/core';
import { PostService } from 'src/app/services/post.service';

@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.scss']
})
export class HomeComponent implements OnInit {

  public posts: any;

  constructor(
    private postService: PostService
  ) { }

  ngOnInit(): void {
    this.getPosts('sort_by_desc=publication_date');
  }

  getPosts(filter = '') {
    this.postService.getPosts(filter).subscribe(
      response => {
        this.posts = response;
        console.log('Posts ->', this.posts);
      }
    );
  }

}
