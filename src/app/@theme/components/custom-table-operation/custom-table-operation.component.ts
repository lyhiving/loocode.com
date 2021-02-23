import {Component, EventEmitter, OnInit, Output} from '@angular/core';
import {ViewCell} from 'ng2-smart-table';

@Component({
  selector: 'app-custom-table-operation',
  template: `
    <button nbButton size="small" status="info" (click)="onAction('view')">详情</button>
    <button class="my-2" size="small" nbButton status="primary" (click)="onAction('editor')">编辑</button>
    <button size="small" nbButton status="danger" (click)="onAction('delete')">删除</button>
  `
})
export class CustomTableOperationComponent implements OnInit, ViewCell {


  rowData: any;
  value: string | number;

  @Output() save: EventEmitter<any> = new EventEmitter();

  constructor() { }

  ngOnInit() {
  }

  onAction(event: string) {
    this.save.emit({
      action: event,
      data: this.rowData,
    });
  }
}
