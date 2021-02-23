import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import {UploadComponent} from './upload.component';
import {DialogComponent} from './dialog/dialog.component';
import {NbButtonModule, NbCardModule, NbIconModule, NbListModule, NbProgressBarModule} from '@nebular/theme';
import {UploadService} from '../../../@core/services/upload.service';



@NgModule({
  declarations: [UploadComponent, DialogComponent],
  exports: [
    UploadComponent
  ],
  imports: [
    CommonModule,
    NbButtonModule,
    NbProgressBarModule,
    NbListModule,
    NbCardModule,
    NbIconModule
  ],
  providers: [
    UploadService
  ]
})
export class UploadModule { }
