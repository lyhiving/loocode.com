import { Component } from '@angular/core';
import {USER_MEMBER_PROFILE, USER_UPDATE_MEMBER_META} from '../../../@core/app.interface.data';
import {AppResponseDataOptions} from '../../../@core/app.data.options';
import {BaseComponent} from '../../../@core/base.component';
import {ActivatedRoute} from "@angular/router";

@Component({
  selector: 'app-member-profile',
  templateUrl: './profile.component.html',
  styleUrls: ['./profile.component.scss']
})
export class ProfileComponent extends BaseComponent {

  items: any = {};
  searchValue: string;
  id = '0';

  constructor(
    private activateRoute: ActivatedRoute
  ) {
    super(activateRoute);
  }

  init() {
    this.activateRoute.paramMap.subscribe(paramMap => {
      this.id = paramMap.get('id');
      if (this.id !== undefined && this.id !== null) {
        this.profile(parseInt(this.id, 10));
      }
    });
  }

  private profile(id: number) {
    this.http.get(USER_MEMBER_PROFILE.replace('{id}', id.toString())).subscribe((res: AppResponseDataOptions) => {
      if (res.code === 200 && res.data) {
        this.items = res.data;
      }
    });
  }

  onCloseDialogCallback() {
  }

  find(value: string) {
    if (value) {
      this.profile(parseInt(value, 10));
    }
  }

  update(item: any, value: string, index: number[]) {
    if (value) {
      this.http.post(USER_UPDATE_MEMBER_META.replace('{id}', this.id), {
        name: item.property, value: value
      }).subscribe((res: AppResponseDataOptions) => {
          this.toastService.showResponseToast(res.code, item.name, res.message);
          if (res.code === 200) {
              this.items.options[index[0]][index[1]].value = value;
          }
      });
    }
  }
}
