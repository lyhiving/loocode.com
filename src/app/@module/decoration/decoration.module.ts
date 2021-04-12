import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { DecorationRoutingModule } from './decoration-routing.module';
import { NavigationComponent } from './navigation/navigation.component';
import { WidgetComponent } from './widget/widget.component';
import { ThemeComponent } from './theme/theme.component';
import {ThemeModule} from "../../@theme/theme.module";
import {DragDropModule} from "@angular/cdk/drag-drop";


@NgModule({
  declarations: [NavigationComponent, WidgetComponent, ThemeComponent],
  imports: [
    CommonModule,
    DecorationRoutingModule,
    ThemeModule,
    DragDropModule,
  ]
})
export class DecorationModule { }
