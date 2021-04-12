import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import {NavigationComponent} from "./navigation/navigation.component";
import {WidgetComponent} from "./widget/widget.component";
import {ThemeComponent} from "./theme/theme.component";

const routes: Routes = [
  {
    path: "navigation",
    component: NavigationComponent,
    data: {title: "导航"}
  },
  {
    path: "widget",
    component: WidgetComponent,
    data: {title: "小挂件"}
  },
  {
    path: "theme",
    component: ThemeComponent,
    data: {title: "主题"}
  },
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class DecorationRoutingModule { }
