import { Component, OnInit } from '@angular/core';
import {HttpClient} from "@angular/common/http";
import {AppResponseDataOptions} from "../../../@core/app.data.options";
import {ToastService} from "../../../@core/services/toast.service";
import {ActivatedRoute} from "@angular/router";

@Component({
  selector: 'app-theme',
  templateUrl: './theme.component.html',
  styleUrls: ['./theme.component.scss']
})
export class ThemeComponent implements OnInit {

  themes: any[] = [];

  title: string;

  constructor(
    private http: HttpClient,
    private toast: ToastService,
    private route: ActivatedRoute
  ) {

  }

  ngOnInit(): void {
    this.http.get("/themes").subscribe((res:AppResponseDataOptions) => {
      if (res.code === 200) {
        this.themes = res.data;
      }
    });
    this.route.data.subscribe((data) => {
      this.title = data.title;
    });
  }

  enable(token: string) {
    this.http.post('/theme/enable', {theme: token}).subscribe((res:AppResponseDataOptions) => {
      this.toast.showResponseToast(res.code, this.title, res.message);
    })
  }
}
