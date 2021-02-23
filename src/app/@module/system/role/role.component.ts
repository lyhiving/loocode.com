import {Component, Input, TemplateRef, ViewChild} from '@angular/core';
import {TableSourceService} from '../../../@core/services/table.source.service';
import {MENU_REFRESH, MENUS, ROLE_STORE, ROLES, USER_MANAGER_STORE} from '../../../@core/app.interface.data';
import {AppResponseDataOptions, Role} from '../../../@core/app.data.options';
import {Row} from 'ng2-smart-table/lib/lib/data-set/row';
import {BaseComponent} from '../../../@core/base.component';
import {mergeMap} from 'rxjs/operators';
import {of} from 'rxjs';
import {isArray, includes} from 'lodash';

@Component({
  selector: 'app-role',
  templateUrl: './role.component.html',
  styleUrls: ['./role.component.scss']
})
export class RoleComponent extends BaseComponent {

  settings = {
    actions: {
      position: 'right',
      add: true,
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
      createdDate: {
        title: '创建时间',
        type: 'string',
        sort: false,
        filter: false,
      },
      updatedDate: {
        title: '更新时间',
        type: 'string',
        sort: false,
        filter: false,
      }
    },
  };

  menus = [];

  @ViewChild('storeWindow', {static: false}) protected storeWindow: TemplateRef<any>;

  @Input() role: Role = {
    name: '',
    permissions: [],
  };

  private selection(item: any, permissions: number[]) {
    if (includes(permissions, item.id)) {
      item.checked = true;
    }
    if (isArray(item.children) && item.children.length >  0) {
      let checked = null;
      for (const child of item.children) {
        if (checked === null) {
          checked = includes(permissions, child.id);
        } else if (checked !== includes(permissions, child.id)) {
          checked = undefined;
        }
      }
      if (checked === null) {
        item.checked = false;
      }
      if (item.checked !== checked) {
        item.checked = checked;
      }
    }

    if (isArray(item.children) && item.children.length > 0) {
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

  onCloseDialogCallback() {
    this.role = {
      name: '',
      permissions: [],
    };
    this.menus.forEach((item) => {
      this.resetSelection(item);
    });
  }

  private resetSelection(item) {
    item.checked = false;
    if (isArray(item.children)) {
      item.children.forEach((child) => {
        this.resetSelection(child);
      });
    }
  }

  edit($event: Row) {
    this.role = $event.getData();
    this.menus.forEach((item) => {
      this.selection(item, this.role.permissions);
    });
    this.popupOperationDialog('editor', 'col-lg-6');
  }

  create($event: any) {
    this.popupOperationDialog('create', 'col-lg-6');
  }

  delete($event: any) {

  }

  action() {
    if (this.role.name.trim() === '') {
      return this.failureToast('名称不能为空');
    }
    this.http.post(ROLE_STORE, this.role)
      .subscribe((res: AppResponseDataOptions) => {
        this.toastService.showResponseToast(res.code, this.operationSubject(), res.message);
        if (res.code !== 200) {
          return ;
        }
        this.nbWindowRef.close();
        if (this.role.id > 0) {
          this.source.refresh();
        } else {
          this.source.append(res.data);
        }
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
        this.menus = isArray(res.data) ? res.data : [];
      }
      this.toastService.showResponseToast(res.code, '刷新菜单', res.message);
    });
  }

  permissionSelected(permissions: any[]) {
    this.role.permissions = permissions;
  }
}
