import {Component, EventEmitter, Input, OnChanges, OnInit, Output, SimpleChanges} from '@angular/core';
import {DialogComponent} from './dialog/dialog.component';
import {NbDialogService} from '@nebular/theme';

@Component({
  selector: 'app-upload',
  templateUrl: './upload.component.html',
  styleUrls: ['./upload.component.scss']
})
export class UploadComponent implements OnChanges {

  @Input() multiple = false;
  @Input() text = '上传';

  @Output() finish: EventEmitter<string[]> = new EventEmitter();

  constructor(public dialog: NbDialogService) { }

  openUploadDialog() {
    const dialogRef = this.dialog.open(DialogComponent, {
      closeOnBackdropClick: false,
      closeOnEsc: false,
      dialogClass: 'col-8'
    });
    dialogRef.componentRef.instance.multiple = this.multiple;
    dialogRef.componentRef.instance.finish = this.finish;
  }

  ngOnChanges(changes: SimpleChanges): void {

  }
}
