import { Component } from '@angular/core';
import {DASHBOARD} from '../../@core/app.interface.data';
import {AppResponseDataOptions} from '../../@core/app.data.options';
import {BaseComponent} from '../../@core/base.component';

@Component({
  selector: 'app-dashboard',
  templateUrl: './dashboard.component.html',
  styleUrls: ['./dashboard.component.scss']
})
export class DashboardComponent extends BaseComponent {
  todaySummary: {name: string, value: number}[];
  yesterdaySummary: {name: string, value: number}[];

  changeTab($event: any) {
  }


  init() {
    this.http.request('get', DASHBOARD, {})
      .subscribe((res: AppResponseDataOptions) => {
        if (res.code === 200) {
            this.todaySummary = res.data.today;
            this.yesterdaySummary = res.data.yesterday;
        }
      });
  }

  onCloseDialogCallback() {
  }
}
