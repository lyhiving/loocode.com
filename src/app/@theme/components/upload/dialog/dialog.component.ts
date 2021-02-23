import {Component, EventEmitter, Input, OnInit, Output, ViewChild} from '@angular/core';
import { forkJoin } from 'rxjs';
import {UploadService} from '../../../../@core/services/upload.service';
import {NbDialogRef} from '@nebular/theme';

@Component({
  selector: 'app-dialog',
  templateUrl: './dialog.component.html',
  styleUrls: ['./dialog.component.scss']
})
export class DialogComponent implements OnInit {

  constructor(public dialogRef: NbDialogRef<DialogComponent>, public uploadService: UploadService) { }
  @ViewChild('file', { static: false }) file;

  public files: Set<File> = new Set();

  @Input() multiple: boolean;

  @Output() finish: EventEmitter<string[]>;

  progress;
  canBeClosed = true;
  primaryButtonText = '上传';
  showCancelButton = true;
  uploading = false;
  uploadSuccessful = false;
  messages: {[key: string]: string} = {};
  errorMessage = '';

  results = [];

  ngOnInit() {}

  onFilesAdded() {
    const files: { [key: string]: File } = this.file.nativeElement.files;
    for (const key in files) {
      if (!isNaN(parseInt(key, 10))) {
        this.files.add(files[key]);
      }
    }
    this.errorMessage = '';
  }

  addFiles() {
    this.file.nativeElement.click();
  }

  closeDialog() {
    // if everything was uploaded already, just close the dialog
    if (this.uploadSuccessful) {
      this.finish.emit(this.results);
      return this.dialogRef.close();
    }
    if (this.files.size < 1) {
      this.errorMessage = '选择需要上传的文件!';
      return ;
    }

    // set the component state to "uploading"
    this.uploading = true;

    // start the upload and save the progress map
    this.progress = this.uploadService.upload(this.files);
    console.log(this.progress);
    for (const key in this.progress) {
      this.progress[key].progress.subscribe(val => console.log(val));
      this.progress[key].response.subscribe(res => {
        if (res.hasOwnProperty('statusText')) {
          this.messages[key] = res.statusText;
        } else if (res.hasOwnProperty('code') && res.code != 200) {
          this.messages[key] = res.message;
        } else {
          this.results.push(res.data[0]);
        }
      });
    }

    // convert the progress map into an array
    const allProgressObservables = [];
    for (const key in this.progress) {
      allProgressObservables.push(this.progress[key].progress);
    }

    // Adjust the state variables

    // The OK-button should have the text "Finish" now
    this.primaryButtonText = '完成';

    // The dialog should not be closed while uploading
    this.canBeClosed = false;
    // this.dialogRef.disableClose = true;

    // Hide the cancel-button
    this.showCancelButton = false;

    // When all progress-observables are completed...
    forkJoin(allProgressObservables).subscribe(end => {
      // ... the dialog can be closed again...
      this.canBeClosed = true;
      // this.dialogRef.disableClose = false;

      // ... the upload was successful...
      this.uploadSuccessful = true;

      // ... and the component is no longer uploading
      this.uploading = false;
    });
  }
}
