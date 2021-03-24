import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import {SystemComponent} from './system.component';
import {ConfigureComponent} from './configure/configure.component';
import {SystemManagerComponent} from './manager/system.manager.component';
import {RoleComponent} from './role/role.component';
import {SiteComponent} from "./site/site.component";
import {GeneralComponent} from "./site/general/general.component";
import {AdComponent} from "./site/ad/ad.component";

const routes: Routes = [{
  path: '',
  component: SystemComponent,
  children: [
    {
      path: 'configuration',
      component: ConfigureComponent,
      data: {'title': '全局'}
    },
    {
      path: 'site',
      component: SiteComponent,
      data: {'title': '站点'},
      children: [
        {
          path: '',
          redirectTo: 'general',
          pathMatch: 'full',
        },
        {
          path: 'general',
          component: GeneralComponent,
        },
        {
          path: 'ad',
          component: AdComponent
        }
      ]
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
