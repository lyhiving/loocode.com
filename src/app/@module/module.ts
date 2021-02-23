import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { ModuleRoutingModule } from './module-routing.module';

import { ModuleComponent } from './module.component';
import { ThemeModule } from '../@theme/theme.module';
import { DashboardModule } from './dashboard/dashboard.module';

const PAGES_COMPONENTS = [
  ModuleComponent,
];

@NgModule({
  imports: [
    ModuleRoutingModule,
    CommonModule,
    DashboardModule,
    ThemeModule,
  ],
  declarations: [
    ...PAGES_COMPONENTS,
  ],
  entryComponents: [
  ]
})
export class Module { }
