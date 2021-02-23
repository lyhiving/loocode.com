import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { ModuleComponent } from './module.component';
import { DashboardComponent } from './dashboard/dashboard.component';

const routes: Routes = [{
  path: '',
  component: ModuleComponent,
  children: [
    {
      path: 'dashboard',
      component: DashboardComponent,
      data: {'name': 'Dashboard'},
    },
    {
      path: 'system',
      loadChildren: () => import('./system/system.module').then(m => m.SystemModule),
    },
    {
      path: 'user',
      loadChildren: () => import('./user/user.module').then(m => m.UserModule),
    },
    {
      path: 'content',
      loadChildren: () => import('./content/content.module').then(m => m.ContentModule),
    }
  ],
}];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class ModuleRoutingModule { }
