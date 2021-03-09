import { Component } from '@angular/core';
import {TableSourceService} from "../../../@core/services/table.source.service";
import {CATEGORIES, CATEGORY_STORE} from "../../../@core/app.interface.data";
import {BaseComponent} from "../../../@core/base.component";
import {AppResponseDataOptions} from "../../../@core/app.data.options";

@Component({
  selector: 'app-category',
  templateUrl: './category.component.html',
  styleUrls: ['./category.component.scss']
})
export class CategoryComponent extends BaseComponent {
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
      name: {
        title: '名称',
        type: 'string',
        sort: false,
        filter: true,
        valuePrepareFunction: (avatar: string, row: any) => {
          return row.term.name;
        },
      },
      description: {
        title: '内容描述',
        type: 'string',
        sort: false,
        filter: false,
      },
      slug: {
        title: '别名',
        type: 'string',
        sort: false,
        filter: false,
        valuePrepareFunction: (avatar: string, row: any) => {
          return row.term.slug;
        },
      },
      count: {
        title: '总数',
        type: 'string',
        sort: true,
        filter: false,
      }
    },
  };
  category: any = {
    name: "",
    slug: "",
    parent: 0,
    description: "",
    taxonomy: "category"
  };

  categories: any[] = [];

  init() {
    this.serviceSourceConf.next(TableSourceService.getServerSourceConf(CATEGORIES));
    this.source.rawData.subscribe((res) => {
      if (res.code == 200) {
        this.categories = res.data;
      }
    })
  }

  create($event: any) {
    if (this.category.name.trim() == "") {
      return this.failureToast("名称不能为空");
    }
    this.submitted = true;
    this.http.post(CATEGORY_STORE, this.category).subscribe((res: AppResponseDataOptions) => {
      this.toastService.showResponseToast(res.code, this.operationSubject(), res.message);
      this.submitted = false;
      if (res.code == 200) {
        this.source.refresh();
      }
    });
  }

  edit($event: any) {

  }

  delete($event: any) {

  }

}
