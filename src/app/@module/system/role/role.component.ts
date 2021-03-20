import {Component} from '@angular/core';
import {TableSourceService} from '../../../@core/services/table.source.service';
import {MANAGER_DELETE, MENU_REFRESH, MENUS, ROLE_DELETE, ROLE_STORE, ROLE_UPDATE, ROLES} from '../../../@core/app.interface.data';
import {AppResponseDataOptions} from '../../../@core/app.data.options';
import {Row} from 'ng2-smart-table/lib/lib/data-set/row';
import {BaseComponent} from '../../../@core/base.component';
import {mergeMap} from 'rxjs/operators';
import {of} from 'rxjs';

@Component({
  selector: 'app-role',
  templateUrl: './role.component.html',
  styleUrls: ['./role.component.scss']
})
export class RoleComponent extends BaseComponent {

  settings = {
    actions: {
      position: 'right',
      add: false,
      columnTitle: '操作',
    },
    add: {
      addButtonContent: '<i class="nb-plus"></i>',
      createButtonContent: '<i class="nb-checkmark"></i>',
      cancelButtonContent: '<i class="nb-close"></i>',
    },
    edit: {
      editButtonContent: '<i class="nb-edit"></i>',
      saveButtonContent: '<i class="nb-checkmark"></i>',
      cancelButtonContent: '<i class="nb-close"></i>',
    },
    delete: {
      deleteButtonContent: '<i class="nb-trash"></i>',
      confirmDelete: true,
    },
    pager: {
      perPage: 30,
    },
    mode: 'external',
    columns: {
      id: {
        title: 'ID',
        type: 'number',
        sort: true,
        filter: false,
      },
      name: {
        title: '名称',
        type: 'string',
        sort: false,
        filter: true,
      },
      updated_date: {
        title: '更新时间',
        type: 'string',
        sort: false,
        filter: false,
      }
    },
  };
  menus = [];
  role: {[key: string]: any} = {
    id: 0,
    name: '',
    permission: [],
  };

  private selection(item: any, permissions: number[]) {
    item.checked = permissions.includes(item.id)
    if (Array.isArray(item.children) && item.children.length >  0) {
      let checked = null;
      for (const child of item.children) {
        if (checked === null) {
          checked = permissions.includes(child.id);
        } else if (checked !== permissions.includes(child.id)) {
          checked = undefined;
        }
      }
      if (checked === null) {
        item.checked = false;
      }
      if (item.checked !== checked) {
        item.checked = checked;
      }
      for (const child of item.children) {
        this.selection(child, permissions);
      }
    }
  }


  init() {
    this.serviceSourceConf.next(TableSourceService.getServerSourceConf(ROLES));

    this.http.get(MENUS).subscribe((res: AppResponseDataOptions) => {
      if (res.code === 200) {
        this.menus = res.data;
      }
    });
  }

  edit($event: Row) {
    this.currentMode = 'editor';
    this.role = $event.getData();
    this.menus.forEach((item) => {
      this.selection(item, this.role.permission);
    });
    this.menus = [...this.menus]
  }

  delete($event: any) {
    if (confirm('确定删除---' + $event.getData().name)) {
      this.http.delete(ROLE_DELETE.replace('{id}', $event.getData().id))
        .subscribe((res: AppResponseDataOptions) => {
          this.toastService.showResponseToast(res.code, this.title, res.message);
          if (res.code === 200) {
            this.source.refresh();
          }
        });
      return true;
    }
  }

  action($event: any) {
    if (this.role.name.trim() === '') {
      return this.failureToast('名称不能为空');
    }
    let url = ROLE_STORE;
    if (this.role.id > 0) {
      url = ROLE_UPDATE.replace('{id}', this.role.id.toString())
    }
    this.http.post(url, this.role)
      .subscribe((res: AppResponseDataOptions) => {
        this.toastService.showResponseToast(res.code, this.title, res.message);
        if (res.code !== 200) {
          return ;
        }
        this.source.refresh();
      });
    return true;
  }

  refreshMenu() {
    this.http.post(MENU_REFRESH, {}).pipe(
      mergeMap((res: AppResponseDataOptions) => {
        if (res.code === 200) {
          return this.http.get(MENUS);
        }
        return of(res);
      })
    ).subscribe((res: AppResponseDataOptions) => {
      if (res.code === 200) {
        this.menus = Array.isArray(res.data) ? res.data : [];
      }
      this.toastService.showResponseToast(res.code, '刷新菜单', res.message);
    });
  }

  permissionSelected(permissions: any[]) {
    this.role.permission = permissions;
  }
}
