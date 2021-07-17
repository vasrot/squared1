import { Component, DoCheck, OnInit } from '@angular/core';

@Component({
  selector: 'app-navbar',
  templateUrl: './navbar.component.html',
  styleUrls: ['./navbar.component.scss']
})
export class NavbarComponent implements OnInit, DoCheck {

  is_auth = false;

  constructor() { }

  ngOnInit(): void {
  }

  ngDoCheck(): void {
    if (localStorage.getItem('token') && localStorage.getItem('user_id')) {
      this.is_auth = true;
    }
  }

  logout() {
    localStorage.removeItem("token");
    localStorage.removeItem("user_id");
    window.location.reload();
  }

}
