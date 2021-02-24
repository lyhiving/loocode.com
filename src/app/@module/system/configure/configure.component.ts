import {Component, Input, OnInit, TemplateRef, ViewChild} from '@angular/core';
import {SYSTEM_CONFIGURE, SYSTEM_CONFIGURE_INIT, SYSTEM_CONFIGURE_STORE, SYSTEM_CONFIGURE_UPDATE} from '../../../@core/app.interface.data';
import {AppResponseDataOptions, SystemConfigure} from '../../../@core/app.data.options';
import {TableSourceService} from '../../../@core/services/table.source.service';
import {NbWindowRef} from '@nebular/theme';
import {Row} from 'ng2-smart-table/lib/lib/data-set/row';
import {BaseComponent} from '../../../@core/base.component';

@Component({
  selector: 'app-configure',
  templateUrl: './configure.component.html',
  styleUrls: ['./configure.component.scss']
})
export class ConfigureComponent extends BaseComponent {

  @Input() itemConfigure: SystemConfigure = {
    option_id: 0, option_name: '', option_value: null, type: 5, description: ''
  };

  dataType = [
    {t: 1, n: '开关类型'},
    {t: 2, n: '数组类型'},
    {t: 3, n: '键值类型'},
    {t: 4, n: '数组键值类型'},
    {t: 5, n: '值类型'},
    {t: 6, n: '多行值类型'},
  ];

  @ViewChild('storeWindow', {static: false}) storeWindow: TemplateRef<any>;

  settings = {
    actions: {
      position: 'right',
      add: true,
      edit: true,
      delete: false,
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
    pager: {
      perPage: 30,
    },
    mode: 'external',
    columns: {
      option_id:  {
        type: 'number',
        title: 'ID',
        sort: true,
        filter: false,
      },
      option_name: {
        type: 'string',
        title: '名称',
        sort: false,
      },
      option_value: {
        type: 'html',
        title: '配置',
        valuePrepareFunction: (value: any) => {
          if (this.isArray(value)) {
            return `<pre>` + JSON.stringify(value, undefined, 2) + `</pre>`;
          }
          if (this.isObject(value)) {
            return `<pre>` + JSON.stringify(value, undefined, 2) + `</pre>`;
          }
          return value;
        },
        sort: false,
        filter: false,
      }
    }
  };

  private windowRef: NbWindowRef;

  editable = false;

  trackByFn(index: any, item: any) {
    return index;
  }

  isArray(obj: any) {
      return Array.isArray(obj);
  }

  isObject(obj: any) {
      return obj instanceof Object;
  }

  initialize() {
    this.http.request('post', SYSTEM_CONFIGURE_INIT, {})
    .subscribe((res: AppResponseDataOptions) => {
      this.toastService.showResponseToast(res.code, '初始化配置', res.message);
      this.source.refresh();
    });
    return true;
  }

  create() {
    this.popupOperationDialog('create', 'col-lg-10');
  }

  store() {
    if (this.itemConfigure.option_name.trim() === '') {
      this.toastService.showToast('danger', '保存配置', '配置名称不能为空');
      return ;
    }
    // if (this.itemConfigure.description.trim() === '') {
    //   this.toastService.showToast('danger', '保存配置', '配置描述不能为空');
    //   return ;
    // }
    if (this.itemConfigure.option_name.match(/\W/)) {
      this.toastService.showToast('danger', '保存配置', '名称只能是英文数字组合');
      return ;
    }
    const data = {...this.itemConfigure};
    if (this.isArray(this.itemConfigure.option_value)) {
      if (this.itemConfigure.type === 3) {
        const maps = {};
        (<SystemConfigure>data).option_value.forEach((item, index) => {
          if (item.name.trim()) {
            maps[item.name] = item.value;
          }
        });
        data.option_value = maps;
      }
      if (this.itemConfigure.type === 4) {
        const maps = [];
        (<SystemConfigure>data).option_value.forEach((item, index) => {
          item.forEach((endItem, endIndex) =>  {
            if (endItem.name.trim()) {
              if (maps[index] === undefined) {
                maps[index] = {};
              }
              maps[index][endItem.name] =  endItem.value;
            }
          });
        });
        data.option_value = maps;
      }
    }
    let url = SYSTEM_CONFIGURE_STORE
    if (data.option_id > 0) {
      url = SYSTEM_CONFIGURE_UPDATE.replace("{id}", data.option_id.toString());
    }
    this.http.request('post', url, {body: data})
      .subscribe((res: AppResponseDataOptions) => {
      this.toastService.showResponseToast(res.code, '配置操作', res.message);
      if (res.code !== 200) {
        return ;
      }
      this.source.refresh();
      this.windowRef.close();
    });
    return true;
  }

  init() {
    this.serviceSourceConf.next(TableSourceService.getServerSourceConf(SYSTEM_CONFIGURE));
  }

  edit($event: Row) {
    const data = {...<SystemConfigure>$event.getData()};
    const maps = [];
    let i = 0;
    this.itemConfigure = data;
    switch (data.type) {
      case 3:
         const keys =  Object.keys(data.option_value);
        for (const key in keys) {
          if (keys[key]) {
            maps[i] = {name: keys[key], value: data.option_value[keys[key]]};
            i++;
          }
        }
        this.itemConfigure.option_value = maps;
        break;
      case 4:
        const len = data.option_value.length;
        for (let j = 0; j < len; j++) {
          i = 0;
          maps[j] = [];
          const mapKeys = Object.keys(data.option_value[j]);
          for (const key in mapKeys) {
            if (mapKeys[key]) {
              maps[j][i] = {name: mapKeys[key], value: data.option_value[j][mapKeys[key]]};
              i++;
            }
          }
        }
        this.itemConfigure.option_value = maps;
        break;
    }
    this.editable = true;
    this.popupOperationDialog('editor', 'col-lg-10');
  }

  selectValueType($event) {
    switch ($event) {
      case 1:
        this.itemConfigure.option_value = true;
        break;
      case 2:
        this.itemConfigure.option_value = [''];
        break;
      case 3:
        this.itemConfigure.option_value = [
          {name: '', value: ''},
        ];
        break;
      case 4:
        this.itemConfigure.option_value = [
          [
            {name: '', value: ''},
          ],
        ];
        break;
      case 5:
        this.itemConfigure.option_value = '';
        break;
    }
    this.itemConfigure.type = $event;
  }

  createRow(i: number, j: number) {
    const len = this.itemConfigure.option_value.length;
    switch (this.itemConfigure.type) {
      case 2:
        this.itemConfigure.option_value[len] = '';
        break;
      case 3:
        this.itemConfigure.option_value[len] = {name: '', value: '', create: true};
        break;
      case 4:
        if (j === -1) {
          this.itemConfigure.option_value[len] = [
            {name: '', value: '', create: true}
          ];
        } else {
          const nextLen = this.itemConfigure.option_value[i].length;
          this.itemConfigure.option_value[i][nextLen] = {name: '', value: '', create: true};
        }
        break;
    }
  }

  deleteRow(i: number, j: number) {
    if (i > -1  &&  j < 0) {
      this.itemConfigure.option_value.splice(i, 1);
    }
    if (i > -1  && j > -1) {
      this.itemConfigure.option_value[i].splice(j, 1);
    }
  }

  onCloseDialogCallback() {
    this.itemConfigure = {option_id: 0, option_name: '', option_value: null, type: 5, description: ''};
    this.editable = false;
  }
}
