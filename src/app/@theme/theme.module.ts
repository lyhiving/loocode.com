import { ModuleWithProviders, NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import {zhCN} from 'date-fns/locale';

import {
  NbActionsModule,
  NbCardModule,
  NbLayoutModule,
  NbMenuModule,
  NbRouteTabsetModule,
  NbSearchModule,
  NbSidebarModule,
  NbTabsetModule,
  NbThemeModule,
  NbUserModule,
  NbCheckboxModule,
  NbPopoverModule,
  NbContextMenuModule,
  NbProgressBarModule,
  NbCalendarModule,
  NbCalendarRangeModule,
  NbStepperModule,
  NbButtonModule,
  NbInputModule,
  NbAccordionModule,
  NbDatepickerModule,
  NbDialogModule,
  NbWindowModule,
  NbListModule,
  NbToastrModule,
  NbAlertModule,
  NbSpinnerModule,
  NbRadioModule,
  NbSelectModule,
  NbChatModule,
  NbTooltipModule,
  NbCalendarKitModule,
  NbBadgeModule,
  NbIconModule,
  NbAutocompleteModule,
  NbTimepickerModule, NbTagModule, NbFormFieldModule,
} from '@nebular/theme';

import { NbEvaIconsModule } from '@nebular/eva-icons';

import {NbSecurityModule} from '@nebular/security';
import { LayoutComponent } from './layouts/layout.component';
import { HeaderComponent } from './components/header/header.component';
import { FooterComponent } from './components/footer/footer.component';
import {NbDateFnsDateModule} from '@nebular/date-fns';
import { CustomTableOperationComponent } from './components/custom-table-operation/custom-table-operation.component';
import {CookieService} from 'ngx-cookie-service';
import {DynamicScriptLoaderService} from '../@core/services/dynamic.script.loader.service';
import {ToastService} from '../@core/services/toast.service';


import { DEFAULT_THEME } from './styles/theme.default';
import { COSMIC_THEME } from './styles/theme.cosmic';
import { CORPORATE_THEME } from './styles/theme.corporate';
import { DARK_THEME } from './styles/theme.dark';
import {TreeViewComponent, TreeViewItemComponent} from './components/treeview/tree-view.component';
import {BaseComponent} from '../@core/base.component';
import {UploadService} from '../@core/services/upload.service';
import {UploadModule} from './components/upload/upload.module';


const BASE_MODULES = [
  CommonModule,
  FormsModule,
  ReactiveFormsModule
];

const NB_MODULES = [
  NbCardModule,
  NbLayoutModule,
  NbTabsetModule,
  NbRouteTabsetModule,
  NbMenuModule,
  NbUserModule,
  NbActionsModule,
  NbSearchModule,
  NbSidebarModule,
  NbCheckboxModule,
  NbPopoverModule,
  NbContextMenuModule,
  NbSecurityModule, // *nbIsGranted directive,
  NbProgressBarModule,
  NbCalendarModule,
  NbCalendarRangeModule,
  NbStepperModule,
  NbButtonModule,
  NbListModule,
  NbToastrModule,
  NbInputModule,
  NbAccordionModule,
  NbDatepickerModule,
  NbDialogModule,
  NbWindowModule,
  NbAlertModule,
  NbSpinnerModule,
  NbRadioModule,
  NbSelectModule,
  NbChatModule,
  NbTooltipModule,
  NbCalendarKitModule,
  NbBadgeModule,
  NbIconModule,
  NbEvaIconsModule,
  NbDateFnsDateModule,
  NbAutocompleteModule,
  NbTagModule,
  NbFormFieldModule,
  UploadModule,
];

const COMPONENTS = [
  // BaseComponent,
  LayoutComponent,
  HeaderComponent,
  FooterComponent,
  CustomTableOperationComponent,

  TreeViewComponent,
  TreeViewItemComponent,
];

const ENTRY_COMPONENTS = [

];

const PIPES = [

];

let NB_THEME_PROVIDERS: any[];
NB_THEME_PROVIDERS = [
    CookieService,
    DynamicScriptLoaderService,
    ToastService,
    ...NbThemeModule.forRoot(
        {
            name: 'default',
        },
        [DEFAULT_THEME, COSMIC_THEME, CORPORATE_THEME, DARK_THEME],
    ).providers,
    ...NbSidebarModule.forRoot().providers,
    ...NbMenuModule.forRoot().providers,
    ...NbDatepickerModule.forRoot().providers,
    ...NbTimepickerModule.forRoot({
      format: 'HH:mm',
      twelveHoursFormat: false,
    }).providers,
    ...NbDialogModule.forRoot().providers,
    ...NbWindowModule.forRoot().providers,
    ...NbToastrModule.forRoot().providers,
    ...NbChatModule.forRoot({
        messageGoogleMapKey: 'AIzaSyA_wNuCzia92MAmdLRzmqitRGvCF7wCZPY',
    }).providers,
    ...NbDateFnsDateModule.forRoot({
        format: 'yyyy-MM-dd HH:mm:ss',
        parseOptions: {
            awareOfUnicodeTokens: true,
            locale: zhCN
        },
        formatOptions: {
            awareOfUnicodeTokens: true,
            locale: zhCN
        },
    }).providers,
];

@NgModule({
  imports: [...BASE_MODULES, ...NB_MODULES],
  exports: [...BASE_MODULES, ...NB_MODULES, ...COMPONENTS, ...PIPES],
  declarations: [...COMPONENTS, ...PIPES],
  entryComponents: [...ENTRY_COMPONENTS],
})
export class ThemeModule {
  static forRoot(): ModuleWithProviders<ThemeModule> {
    return {
      ngModule: ThemeModule,
      providers: [...NB_THEME_PROVIDERS],
    };
  }
}
