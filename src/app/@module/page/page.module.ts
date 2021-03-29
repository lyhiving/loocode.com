import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { PageRoutingModule } from './page-routing.module';
import {NewComponent} from "./new/new.component";
import {PageComponent} from "./page.component";
import {ThemeModule} from "../../@theme/theme.module";
import {MarkdownEditorModule} from "../../@theme/components/markdown-editor/markdown-editor.module";
import {CKEditorModule} from "@ckeditor/ckeditor5-angular";


@NgModule({
  declarations: [
    NewComponent,
    PageComponent,
  ],
  imports: [
    CommonModule,
    PageRoutingModule,
    ThemeModule,
    MarkdownEditorModule,
    CKEditorModule
  ]
})
export class PageModule { }
