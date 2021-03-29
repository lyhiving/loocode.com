import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { DecorationRoutingModule } from './decoration-routing.module';
import { NavigationComponent } from './navigation/navigation.component';
import { WidgetComponent } from './widget/widget.component';
import { ThemeComponent } from './theme/theme.component';


@NgModule({
  declarations: [NavigationComponent, WidgetComponent, ThemeComponent],
  imports: [
    CommonModule,
    DecorationRoutingModule
  ]
})
export class DecorationModule { }
