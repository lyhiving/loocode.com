import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import {SystemComponent} from './system.component';
import {ConfigureComponent} from './configure/configure.component';
import {SystemManagerComponent} from './manager/system.manager.component';
import {RoleComponent} from './role/role.component';

const routes: Routes = [{
  path: '',
  component: SystemComponent,
  children: [
    {
      path: 'configuration',
      component: ConfigureComponent,
      data: {'title': '站点配置'}
    },
    {
        path: 'managers',
        component: SystemManagerComponent,
        data: {'title': '管理员'}
    },
    {
      path: 'roles',
      component: RoleComponent,
      data: {'title': '角色'}
    },
  ],
}];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class SystemRoutingModule { }
