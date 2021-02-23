import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import {ContentComponent} from "./content.component";
import {PostsComponent} from "./posts/posts.component";
import {PostsActionComponent} from "./posts-action/posts-action.component";
import {CategoryComponent} from "./category/category.component";
import {TagComponent} from "./tag/tag.component";

const routes: Routes = [{
  path: '',
  component: ContentComponent,
  children: [
    {
      path: 'posts',
      component: PostsComponent,
      data: {'name': '主题管理'}
    },
    {
      path: 'posts-action',
      component: PostsActionComponent,
      data: {'name': '创建更新主题'}
    },
    {
      path: 'category',
      component: CategoryComponent,
      data: {'name': '分类管理'}
    },
    {
      path: 'tag',
      component: TagComponent,
      data: {'name': '标签管理'}
    }
  ],
}];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class ContentRoutingModule { }
