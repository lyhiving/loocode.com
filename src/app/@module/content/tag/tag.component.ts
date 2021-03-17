import {Component, TemplateRef, ViewChild} from '@angular/core';
import {TableSourceService} from "../../../@core/services/table.source.service";
import {CATEGORY_DELETE, TAG_DELETE, TAG_STORE, TAG_UPDATE, TAGS} from "../../../@core/app.interface.data";
import {BaseComponent} from "../../../@core/base.component";
import {AppResponseDataOptions} from "../../../@core/app.data.options";
import {Row} from "ng2-smart-table/lib/lib/data-set/row";

@Component({
  selector: 'app-tag',
  templateUrl: './tag.component.html',
  styleUrls: ['./tag.component.scss']
})
export class TagComponent extends BaseComponent {

  @ViewChild('updateWindow', {static: false}) storeWindow: TemplateRef<any>;

  tag: {[key: string]: any} = {
    id: 0,
    name: "",
    slug: "",
    description: ""
  }

  settings = {
    actions: {
      position: 'right',
      add: false,
      columnTitle: '操作',
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

  edit($event: any) {
    this.currentMode = "editor"
    const data = $event.data
    this.tag = {
      id: data.term_taxonomy_id,
      name: data.term.name,
      slug: data.term.slug,
      description: data.description,
    };
  }

  delete($event: any) {
    const data = $event.data;
    if (window.confirm("确定删除" + this.title + ": " + data.term.name.replaceAll("— ", "").trim())) {
      this.http.delete(TAG_DELETE.replace("{id}", data.term_taxonomy_id)).subscribe((res:AppResponseDataOptions) => {
        this.toastService.showResponseToast(res.code, this.title, res.message);
        if (res.code == 200) {
          this.source.refresh();
        }
      });
    }
  }

  action($event: any) {
    if (this.tag.name.trim() == "") {
      return this.toastService.showToast('danger', this.title, "名称不能为空");
    }
    this.tag.taxonomy = "post_tag";
    let url = TAG_STORE
    if (this.tag.id > 0) {
      url = TAG_UPDATE.replace("{id}", this.tag.id.toString())
    }
    this.http.post(url, this.tag).subscribe((res:AppResponseDataOptions) => {
      this.toastService.showResponseToast(res.code, this.title, res.message);
      if (res.code == 200) {
        this.source.refresh();
      }
    })
  }
}
