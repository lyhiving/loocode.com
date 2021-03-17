import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import {Observable} from 'rxjs';
import {map} from 'rxjs/operators';
import {NbMenuItem} from '@nebular/theme';
import {AppMenuItem, AppResponseDataOptions} from '../app.data.options';

@Injectable()
export class MenuService {

  menu: NbMenuItem[] = [];

  constructor(private http: HttpClient) {

  }

  getMenu(): Observable<NbMenuItem[]> {
      return this.http.request('get','/open/menu', {body: {}, observe: 'response'})
      .pipe(
          map((res) => {
            const response: AppResponseDataOptions = <AppResponseDataOptions> res.body;
            if (response.code === 200 && response.data) {
              response.data.forEach((item: AppMenuItem, index) => {
                const nbMenuItem =  this.menuHandle(item);
                if (nbMenuItem) {
                  this.menu.push(nbMenuItem);
                }
              });
            }
            return this.menu;
          })
      );
  }

  private menuHandle(item: AppMenuItem): NbMenuItem {
      if (item.children.length === 0 && item.link === '') {
          return;
      }
      const child: NbMenuItem = {
        title: item.title,
        icon: item.icon,
        link: item.link,
        hidden: item.hidden
      };
      if (item.home) {
          child.home = true;
      }
      if (item.children && item.children.length > 0) {
          child.children = [];
          item.children.forEach((menu) => {
              const parent: NbMenuItem = this.menuHandle(menu);
              if (parent) {
                  child.children.push(parent);
              }
          });
          if (child.children.length === 0) {
              delete child.children; // 如果有这个字段，URL不会显示出来
          }
      }
      return child;
  }
}
