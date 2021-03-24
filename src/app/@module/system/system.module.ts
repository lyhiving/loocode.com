import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { SystemRoutingModule } from './system-routing.module';
import {SystemComponent} from './system.component';
import {ConfigureComponent} from './configure/configure.component';
import {ThemeModule} from '../../@theme/theme.module';
import {NbToggleModule, NbWindowService} from '@nebular/theme';
import {SystemManagerComponent} from './manager/system.manager.component';
import {Ng2SmartTableModule} from 'ng2-smart-table';
import { RoleComponent } from './role/role.component';
import { SiteComponent } from './site/site.component';
import { GeneralComponent } from './site/general/general.component';
import { AdComponent } from './site/ad/ad.component';

@NgModule({
  declarations: [
    SystemComponent,
    ConfigureComponent,
    SystemManagerComponent,
    RoleComponent,
    SiteComponent,
    GeneralComponent,
    AdComponent,
  ],
  imports: [
    CommonModule,
    SystemRoutingModule,
    ThemeModule,
    Ng2SmartTableModule,
    NbToggleModule,
  ],
  providers: [
      NbWindowService
  ]
})
export class SystemModule { }
