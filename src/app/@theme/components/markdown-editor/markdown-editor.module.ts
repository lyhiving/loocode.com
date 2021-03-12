import {CUSTOM_ELEMENTS_SCHEMA, NgModule} from '@angular/core';
import { CommonModule } from '@angular/common';

import { MarkdownEditorComponent } from './markdown-editor.component';
import '@github/markdown-toolbar-element';
import {FormsModule, ReactiveFormsModule} from "@angular/forms";
import { TextFieldModule } from "@angular/cdk/text-field";
import {MarkdownModule, MarkedOptions} from "ngx-markdown";
import {ThemeModule} from "../../theme.module";

@NgModule({
  declarations: [MarkdownEditorComponent],
  imports: [
    CommonModule,
    FormsModule,
    ReactiveFormsModule,
    TextFieldModule,
    MarkdownModule.forRoot({
      markedOptions: {
        provide: MarkedOptions,
        useFactory: () :MarkedOptions => {
          return {
            gfm: true,
            breaks: false,
            pedantic: false,
            smartLists: true,
            smartypants: false,
          }
        }
      }
    }),
    ThemeModule,
  ],
  exports: [
    MarkdownEditorComponent
  ],
  schemas: [
    CUSTOM_ELEMENTS_SCHEMA
  ]
})
export class MarkdownEditorModule { }
