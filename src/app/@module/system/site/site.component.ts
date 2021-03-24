import {Component} from '@angular/core';
import {BaseComponent} from "../../../@core/base.component";

@Component({
  selector: 'app-site',
  templateUrl: './site.component.html',
  styleUrls: ['./site.component.scss']
})
export class SiteComponent extends BaseComponent {

  tabs: any[] = [
    {
      title: '常规',
      icon: 'settings',
      route: './general',
    },
    {
      title: "广告统计",
      icon: "trending-up",
      route: "./ad",
    }
  ];
  init() {
  }
}
