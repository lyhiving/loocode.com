import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { SystemRoutingModule } from './system-routing.module';
import {SystemComponent} from './system.component';
import {ConfigureComponent} from './configure/configure.component';
import {ThemeModule} from '../../@theme/theme.module';
import {NbWindowService} from '@nebular/theme';
import {SystemManagerComponent} from './manager/system.manager.component';
import {Ng2SmartTableModule} from 'ng2-smart-table';
import { RoleComponent } from './role/role.component';

@NgModule({
  declarations: [
    SystemComponent,
    ConfigureComponent,
    SystemManagerComponent,
    RoleComponent,
  ],
  imports: [
      CommonModule,
      SystemRoutingModule,
      ThemeModule,
      Ng2SmartTableModule,
  ],
  providers: [
      NbWindowService
  ]
})
export class SystemModule { }
