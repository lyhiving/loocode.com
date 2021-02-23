import { BrowserModule } from '@angular/platform-browser';
import {APP_INITIALIZER, NgModule} from '@angular/core';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import {BrowserAnimationsModule} from "@angular/platform-browser/animations";
import {HttpClientModule} from "@angular/common/http";
import {ThemeModule} from "./@theme/theme.module";
import {CoreModule} from "./@core/core.module";
import {AuthGuard} from "./@core/services/auth.guard";
import {ConfigurationService} from "./@core/services/configuration.service";
import {APP_BASE_HREF} from "@angular/common";

const declarations = [
  AppComponent,
];


@NgModule({
  declarations: declarations,
  imports: [
    BrowserModule,
    BrowserAnimationsModule,
    HttpClientModule,
    AppRoutingModule,
    ThemeModule.forRoot(),
    CoreModule.forRoot()
  ],
  bootstrap: [AppComponent],
  providers: [
    AuthGuard,
    ConfigurationService,
    {
      provide: APP_INITIALIZER,
      useFactory: (cf: ConfigurationService) => {
        return () => cf.load();
      },
      deps: [ConfigurationService],
      multi: true
    },
    { provide: APP_BASE_HREF, useValue: '/' },
  ],
  entryComponents: [
  ]
})
export class AppModule { }
