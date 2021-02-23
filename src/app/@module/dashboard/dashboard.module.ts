import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import {DashboardComponent} from './dashboard.component';
import {ThemeModule} from '../../@theme/theme.module';
import {ToastService} from '../../@core/services/toast.service';
import {NbWindowService} from '@nebular/theme';
import {UploadService} from '../../@core/services/upload.service';

@NgModule({
  declarations: [
    DashboardComponent
  ],
  imports: [
    CommonModule,
    ThemeModule
  ],
  providers: [
    ToastService,
    UploadService,
    NbWindowService
  ]
})
export class DashboardModule { }
