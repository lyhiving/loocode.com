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
      data: {'title': '文章'}
    },
    {
      path: 'post-new',
      component: PostsActionComponent,
      data: {'title': '写文章'}
    },
    {
      path: 'post-editing/:id',
      component: PostsActionComponent,
      data: {'title': '编辑文章'}
    },
    {
      path: 'category',
      component: CategoryComponent,
      data: {'title': '分类目录'}
    },
    {
      path: 'tag',
      component: TagComponent,
      data: {'title': '标签'}
    }
  ],
}];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class ContentRoutingModule { }
