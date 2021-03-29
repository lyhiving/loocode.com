import { Component, OnInit } from '@angular/core';
import {HttpClient} from "@angular/common/http";
import {AppResponseDataOptions} from "../../../../@core/app.data.options";
import {SITE_AD_OPTION_SAVE, SITE_AD_OPTIONS} from "../../../../@core/app.interface.data";
import {ToastService} from "../../../../@core/services/toast.service";

@Component({
  selector: 'app-ad',
  templateUrl: './ad.component.html',
  styleUrls: ['./ad.component.scss']
})
export class AdComponent implements OnInit {
  option: {[key:string]: any} = {};
  submitted: boolean;
  constructor(
    private http:HttpClient,
    private toast: ToastService
  ) { }
  ngOnInit(): void {
    this.http.get(SITE_AD_OPTIONS).subscribe((res:AppResponseDataOptions) => {
      if (res.code === 200) {
        this.option = res.data;
      }
    });
  }
  action($event: any) {
    this.submitted = true
    this.http.post(SITE_AD_OPTION_SAVE, this.option).subscribe((res: AppResponseDataOptions) => {
      this.submitted = false;
      this.toast.showResponseToast(res.code, '广告与统计', res.message);
    }, _ => this.submitted = false);
  }

  adOpen($event: any) {
    this.option.ad_open = $event ? 'on' : 'off';
  }
}
