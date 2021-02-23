import { Component, OnInit } from '@angular/core';

import { MENU_ITEMS } from './main.menu';
import {MenuService} from '../@core/services/menu.service';

@Component({
  selector: 'app-module',
  template: `
    <app-layout>
      <nb-menu [items]="menu"></nb-menu>
      <router-outlet></router-outlet>
    </app-layout>
  `,
  providers: [MenuService]
})
export class ModuleComponent implements OnInit {
  menu = MENU_ITEMS;

  constructor(private dynamicMenu: MenuService) {

  }

  ngOnInit() {
    this.dynamicMenu.getMenu().subscribe(res => {
      this.menu = res;
    });
  }

}
