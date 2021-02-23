import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import {UserComponent} from './user.component';
import {UserMemberComponent} from './member/user.member.component';
import {ProfileComponent} from './profile/profile.component';

const routes: Routes = [{
  path: '',
  component: UserComponent,
  children: [
    {
      path: 'members',
      component: UserMemberComponent,
      data: {'name': '会员管理'}
    },
    {
      path: 'member',
      component: ProfileComponent,
      data: {'name': '会员信息'},
    },
    {
      path: 'member/:id',
      component: ProfileComponent,
      data: {'name': '会员信息'},
    }
  ],
}];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class UserRoutingModule { }
