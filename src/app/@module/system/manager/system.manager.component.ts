import {Component, Input, OnInit, TemplateRef, ViewChild} from '@angular/core';
import {TableSourceService} from '../../../@core/services/table.source.service';
import {ALL_ROLES, SYSTEM_MANAGER, USER_MANAGER_DELETE, USER_MANAGER_STORE} from '../../../@core/app.interface.data';
import {Row} from 'ng2-smart-table/lib/lib/data-set/row';
import {AppResponseDataOptions, Manager} from '../../../@core/app.data.options';
import {BaseComponent} from '../../../@core/base.component';

@Component({
  selector: 'app-user-manager',
  templateUrl: './system.manager.component.html',
  styleUrls: ['./system.manager.component.scss']
})
export class SystemManagerComponent extends BaseComponent {
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
      },
      email: {
        title: '邮箱',
        type: 'string',
        sort: false,
      },
      avatar: {
        title: '头像',
        type: 'html',
        valuePrepareFunction: (avatar: string) => {
            return `<img src="${avatar}" width="100px" />`;
        },
        filter: false,
        sort: false,
      },
      roleNames: {
        title: '角色',
        type: 'string',
        filter: false,
        sort: false,
      },
      lastedDate: {
        title: '最后登录时间',
        type: 'number',
        sort: false,
      },
      lastedIp: {
        title: '最后登录IP',
        type: 'number',
        sort: false,
      }
    },
  };


  @ViewChild('storeWindow', {static: false}) protected storeWindow: TemplateRef<any>;

  @Input() manager: Manager = {
    id: 0,
    email: '',
    password: '',
    avatar: '',
    roles: [],
    lastedIp: '',
    lastedDate: '',
  };

  roles = [];

  delete($event: Row) {
    if (confirm('确定删除---' + $event.getData().email)) {
      this.http.request('post', USER_MANAGER_DELETE.replace('{id}', $event.getData().id) , {})
          .subscribe((res: AppResponseDataOptions) => {
            this.toastService.showResponseToast(res.code, '删除管理员', res.message);
            if (res.code === 0) {
              this.source.refresh();
            }
          });
      return true;
    }
  }

  edit($event: Row) {
    this.manager = $event.getData();
    console.log(this.manager);
    this.popupOperationDialog('editor', 'col-lg-6');
  }

  create($event: any) {
    this.popupOperationDialog('create', 'col-lg-6');
  }

  action() {
    if (this.manager.email.trim() === '') {
      return this.failureToast('邮箱不能为空');
    }
    if (this.currentMode === 'create' && this.manager.password.trim() === '') {
      return this.failureToast('密码不能为空');
    }

    if (this.manager.password && this.manager.password.length < 6) {
      return this.failureToast('密码不能小于6位数');
    }

    this.http.request('post', USER_MANAGER_STORE, {body: this.manager})
      .subscribe((res: AppResponseDataOptions) => {
        this.toastService.showResponseToast(res.code, this.operationSubject(), res.message);
        if (res.code !== 200) {
          return ;
        }
        this.nbWindowRef.close();
        if (this.manager.id > 0) {
            this.source.refresh();
        } else {
            this.source.append(res.data);
        }
      });
    return true;
  }

  init() {
    this.serviceSourceConf.next(TableSourceService.getServerSourceConf(SYSTEM_MANAGER));
    this.http.get(ALL_ROLES).subscribe((res: AppResponseDataOptions) => {
      if (res.code === 200) {
        this.roles = res.data;
      }
    });
  }

  onCloseDialogCallback() {
    this.manager = {
      id: 0,
      email: '',
      password: '',
      avatar: '',
      roles: [],
      lastedIp: '',
      lastedDate: '',
    };
  }
}
