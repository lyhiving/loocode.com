import {Component, ElementRef, OnInit, ViewChild} from '@angular/core';

import '@ckeditor/ckeditor5-build-decoupled-document/build/translations/zh-cn';
import * as DocumentEditor from '@ckeditor/ckeditor5-build-decoupled-document';
import {environment} from "../../../../environments/environment";
import {BaseComponent} from "../../../@core/base.component";
import {NbTagComponent, NbTagInputDirective} from "@nebular/theme";
import {FormControl} from "@angular/forms";
import {POSTS_STORE} from "../../../@core/app.interface.data";
import {AppResponseDataOptions} from "../../../@core/app.data.options";

@Component({
  selector: 'app-posts-action',
  templateUrl: './posts-action.component.html',
  styleUrls: ['./posts-action.component.scss']
})
export class PostsActionComponent extends BaseComponent {

  posts: any = {
    post_title: "",
    post_content: ""
  };



  Editor = DocumentEditor;

  editorOptions: any = {
    language: 'zh-cn',
    toolbar: ['ckfinder', 'undo', 'redo', 'alignment:left', 'alignment:right', 'alignment:center', 'alignment:justify',
      'alignment', 'fontSize', 'fontFamily', 'removeHighlight', 'highlight', 'bold', 'italic', 'strikethrough',
      'underline', 'blockQuote', 'imageTextAlternative', 'heading',
      'imageStyle:full', 'imageStyle:alignLeft', 'imageStyle:alignRight', 'indent', 'outdent', 'link',
      'numberedList', 'bulletedList', 'mediaEmbed', 'insertTable', 'tableColumn', 'tableRow', 'mergeTableCells'
    ],
    placeholder: "在这里撰写你的内容",
    ckfinder: {
      uploadUrl: environment.gateway + '/ckfinder/entry?command=QuickUpload&type=Files&responseType=json',
    }
  };
  tags: Set<string> = new Set<string>();
  options: string[] = [];

  inputFormControl: FormControl = new FormControl();

  @ViewChild(NbTagInputDirective, { read: ElementRef }) tagInput: ElementRef<HTMLInputElement>;

  init() {
    this.inputFormControl.valueChanges.subscribe(value => {

    });
  }


  onTagRemove(tagToRemove: NbTagComponent): void {
    this.tags.delete(tagToRemove.text);
    this.options.push(tagToRemove.text);
  }

  onTagAdd(value: string): void {
    if (value) {
      this.tags.add(value);
      this.options = this.options.filter(o => o !== value);
    }
    this.tagInput.nativeElement.value = '';
  }

  onReady(editor) {
    editor.ui.getEditableElement().parentElement.insertBefore(
      editor.ui.view.toolbar.element,
      editor.ui.getEditableElement()
    );
  }

  action() {
    this.http.post(POSTS_STORE, this.posts).subscribe((res: AppResponseDataOptions) => {

    })
  }
}
