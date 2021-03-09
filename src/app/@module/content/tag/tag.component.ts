import { Component } from '@angular/core';
import {TableSourceService} from "../../../@core/services/table.source.service";
import {TAGS} from "../../../@core/app.interface.data";
import {BaseComponent} from "../../../@core/base.component";

@Component({
  selector: 'app-tag',
  templateUrl: './tag.component.html',
  styleUrls: ['./tag.component.scss']
})
export class TagComponent extends BaseComponent {

  tag = {
    name: "",
    slug: "",
    description: "",
    taxonomy: "post_tag"
  }

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

  init() {
    this.serviceSourceConf.next(TableSourceService.getServerSourceConf(TAGS));
  }

  create($event: any) {

  }

  edit($event: any) {

  }

  delete($event: any) {

  }
}
