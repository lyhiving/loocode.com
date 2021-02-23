import {Component, ViewChild} from '@angular/core';
import {BaseComponent} from "../../../@core/base.component";
import {TableSourceService} from "../../../@core/services/table.source.service";
import {CONTENT_POSTS} from "../../../@core/app.interface.data";
import {ComponentType} from "@angular/cdk/overlay";
import {PostsActionComponent} from "../posts-action/posts-action.component";

@Component({
  selector: 'app-posts',
  templateUrl: './posts.component.html',
  styleUrls: ['./posts.component.scss']
})
export class PostsComponent extends BaseComponent {

  storeWindow: ComponentType<PostsActionComponent> = PostsActionComponent;

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

  init() {
    this.serviceSourceConf.next(TableSourceService.getServerSourceConf(CONTENT_POSTS));
  }

  create($event: any) {
    this.popupOperationDialog('create', 'col-12');
  }

  edit($event: any) {

  }

  delete($event: any) {

  }

  action() {

  }
}
