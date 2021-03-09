import {AfterViewInit, ChangeDetectionStrategy, Component, ElementRef, ViewChild} from '@angular/core';

import * as ClassicEditor from "../../../../ckeditor5/build/ckeditor";
import {environment} from "../../../../environments/environment";
import {BaseComponent} from "../../../@core/base.component";
import {NbAccordionComponent, NbDatepickerComponent, NbDateTimePickerComponent, NbTagComponent, NbTagInputDirective} from "@nebular/theme";
import {FormControl} from "@angular/forms";
import {CATEGORIES, POST_STORE, TAGS} from "../../../@core/app.interface.data";
import {AppResponseDataOptions} from "../../../@core/app.data.options";
import {debounceTime, distinctUntilChanged, filter, startWith, switchMap} from "rxjs/operators";


@Component({
  selector: 'app-posts-action',
  templateUrl: './posts-action.component.html',
  styleUrls: ['./posts-action.component.scss'],
  // changeDetection: ChangeDetectionStrategy.OnPush,
})
export class PostsActionComponent extends BaseComponent implements AfterViewInit {

  @ViewChild('accordionComponent', {static: false}) accordion: NbAccordionComponent;
  @ViewChild('eyeButton', { read: ElementRef }) eyeButton:ElementRef;
  post: any = {
    post_title: "",
    post_excerpt: "",
    post_content: "",
    post_status: "draft", // "draft",
    post_type: "post",
    comment_status: "open",
    ping_status: "open",
    categories: [],
    tags: [],
    password: "",
    post_date: "",
    meta: {
      keyword: "",
      description: "",
      featured_media: "",
    }
  };
  eye: string = "open";
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
  tags: Map<string, string> = new Map<string, string>();
  filteredTags$: any[] = [];
  inputFormControl: FormControl = new FormControl();
  categories: any[] = [];
  @ViewChild(NbTagInputDirective, { read: ElementRef }) tagInput: ElementRef<HTMLInputElement>;

  init() {
    this.inputFormControl.valueChanges.pipe(
      startWith(''),
      filter(value => value != ""),
      debounceTime(400),
      distinctUntilChanged(),
      switchMap(value => {
        if (value) {
          return this.http.get(TAGS + '?name=' + value);
        }
      })
    ).subscribe((res:AppResponseDataOptions) => {
      if (res.data.data.length > 0) {
        this.filteredTags$ = res.data.data;
      }
    });
    this.loadScript.loadCKfinder();
    this.http.get(CATEGORIES).subscribe((res: AppResponseDataOptions) => {
      if (res.code == 200) {
        res.data.forEach((item) => {
          item.checked = false;
        });
        this.categories = res.data
      }
    });
    this.finderFileChoose.subscribe((res) => {
      this.post.meta.featured_media = res[0].url;
    });
    setTimeout(() => this.sidebarService.toggle(false, 'menu-sidebar'), 0);
  }

  @ViewChild("dateTimePicker") datepicker: NbDateTimePickerComponent<any>;
  @ViewChild("inputBtnElement", {read: ElementRef}) inputBtnElement;
  ngAfterViewInit(): void {
    this.accordion.openAll();
    this.datepicker.attach(this.inputBtnElement)
    this.datepicker.valueChange.subscribe((value) => {
      const datepickerAdapter = this.datepickerAdapters.find(({ picker }) => this.datepicker instanceof picker);
      value = datepickerAdapter.format(value, null);
      this.inputBtnElement.nativeElement.innerHTML = value;
      this.datepicker.hide();
      this.post.post_date = value;
    });
    this.cd.detectChanges();
  }



  onTagRemove(tagToRemove: NbTagComponent): void {
    this.tags.delete(tagToRemove.text);
  }

  onTagAdd(value: string): void {
    if (value) {
      let isFilteredTag = false;
      this.filteredTags$.forEach((item) => {
        if (item.term_taxonomy_id == value) {
          this.tags.set(item.term.name, item.term_taxonomy_id);
          isFilteredTag = true;
        }
      })
      if (isFilteredTag) {
        this.filteredTags$ = this.filteredTags$.filter(o => o.term.name !== value);
      } else {
        this.tags.set(value, value);
      }
    }
    this.tagInput.nativeElement.value = '';
  }

  onReady(editor) {
    this.editor = editor;
    console.log(Array.from( editor.ui.componentFactory.names() ));
    editor.ui.getEditableElement().parentElement.insertBefore(
      editor.ui.view.toolbar.element,
      editor.ui.getEditableElement()
    );
  }

  action(status: string) {
    if (status == 'publish') {
      if (!this.post.post_title) {
        return this.toastService.showToast("danger", "写文章","标题不能为空");
      }
    }
    if (this.tags.size > 0) {
      const iterator = this.tags.values()
      let v = iterator.next()
      while (v.value) {
        this.post.tags.push(v.value)
        v = iterator.next()
      }
    }
    if (this.categories.length > 0) {
      this.categories.forEach((item) => {
        if (item.checked) {
          this.post.categories.push(item.term_taxonomy_id);
        }
      })
    }
    if (this.eye == "private") {
      status = "private";
    }
    this.post.post_status = status;
    this.http.post(POST_STORE, this.post).subscribe((res: AppResponseDataOptions) => {
      this.toastService.showResponseToast(res.code, this.operationSubject(), res.message);
      this.submitted = false;
    });
  }

  preview() {

  }

  commentStatus(status: boolean) {
    this.post.comment_status = status ? "open": "closed";
  }

  pingStatus(status: boolean) {
    this.post.ping_status = status ? "open": "closed";
  }

  onEye($event) {
    let name = "公开";
    switch ($event) {
      case "private":
        name = "私密"
        break;
      case "password":
        if (this.post.password.length > 0) {
          name = "密码保护";
        }
        break;
    }
    if ($event !== "password") {
      this.post.password = "";
    }
    this.eyeButton.nativeElement.innerHTML = name;
  }
  datetime($event: MouseEvent) {
    this.datepicker.show();
  }

  popoverChange($event: { isShown: boolean }) {
    if ($event.isShown == false && this.post.password < 1 && this.eye == "password") {
      this.eye = "open";
    }
  }
}
