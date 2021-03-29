import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import {NavigationComponent} from "./navigation/navigation.component";
import {WidgetComponent} from "./widget/widget.component";
import {ThemeComponent} from "./theme/theme.component";

const routes: Routes = [
  {
    path: "navigation",
    component: NavigationComponent
  },
  {
    path: "widget",
    component: WidgetComponent
  },
  {
    path: "theme",
    component: ThemeComponent
  },
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class DecorationRoutingModule { }
