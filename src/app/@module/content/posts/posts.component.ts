import {Component, OnInit, ViewChild} from '@angular/core';
import {BaseComponent} from "../../../@core/base.component";
import {TableSourceService} from "../../../@core/services/table.source.service";
import {POSTS} from "../../../@core/app.interface.data";
import {Router} from "@angular/router";
import {Row} from "ng2-smart-table/lib/lib/data-set/row";

@Component({
  selector: 'app-posts',
  templateUrl: './posts.component.html',
  styleUrls: ['./posts.component.scss']
})
export class PostsComponent extends BaseComponent {
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
        filter: true,
      },
      post_author: {
        title: '作者',
        type: 'string',
        sort: false,
        filter: true,
      },
      post_title: {
        title: '标题',
        type: 'string',
        sort: false,
        filter: true,
      },
      post_status: {
        title: '状态',
        type: 'string',
        sort: false,
        filter: true,
      },
      post_modified: {
        title: '时间',
        type: 'string',
        sort: false,
        filter: false,
      }
    },
  };
  constructor(
    private router: Router
  ) {
    super();
  }

  init() {
    this.serviceSourceConf.next(TableSourceService.getServerSourceConf(POSTS));
  }

  create($event: any) {
    this.router.navigateByUrl("/app/content/post-new");
  }

  edit($event: Row) {
    this.router.navigateByUrl("/app/content/post-editing/"+$event.getData().id);
  }

  delete($event: any) {

  }
}
