import { Component} from '@angular/core';
import {environment} from "../../../../environments/environment";
import * as ClassicEditor from "../../../../ckeditor5/build/ckeditor";
import {BaseComponent} from "../../../@core/base.component";

@Component({
  selector: 'app-page-new',
  templateUrl: './new.component.html',
  styleUrls: ['./new.component.scss']
})
export class NewComponent extends BaseComponent {
  editorMode: string;
  post: any = {};
  title: string;

  Editor = ClassicEditor;
  editor;
  editorOptions: any = {
    image: {
      resizeOptions: [
        {
          name: 'resizeImage:original',
          label: 'Original',
          value: null
        },
        {
          name: 'resizeImage:20',
          label: '20%',
          value: '20'
        },
        {
          name: 'resizeImage:50',
          label: '50%',
          value: '50'
        },
        {
          name: 'resizeImage:75',
          label: '75%',
          value: '75'
        }
      ],
      toolbar: [
        'imageStyle:full',
        'imageStyle:alignLeft',
        'imageStyle:alignRight',
        '|',
        'resizeImage',
        '|',
        'imageTextAlternative'
      ]
    },
    language: 'zh-cn',
    toolbar: [
      'ckfinder', 'code', 'codeBlock', 'undo', 'redo', 'alignment:left', 'alignment:right', 'alignment:center', 'alignment:justify',
      'alignment', 'fontSize', 'fontFamily', 'removeHighlight', 'highlight', 'bold', 'italic',
      'blockQuote', 'imageTextAlternative', 'heading', 'indent', 'outdent', 'link',
      'numberedList', 'bulletedList', 'mediaEmbed', 'insertTable', 'tableColumn', 'tableRow', 'mergeTableCells'
    ],
    placeholder: "在这里撰写你的内容",
    ckfinder: {
      uploadUrl: environment.gateway + '/ckfinder/connector?command=QuickUpload&type=Files&responseType=json',
    }
  };

  init(): void {
    this.editorMode = this.appConfig.editor || 'markdown';
  }
  onReady(editor) {
    this.editor = editor;
    editor.ui.getEditableElement().parentElement.insertBefore(
      editor.ui.view.toolbar.element,
      editor.ui.getEditableElement()
    );
  }

  action(publish: string) {

  }
}
