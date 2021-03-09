import {ChangeDetectorRef, Component, Directive, EventEmitter, Inject, Injectable, OnInit, Output, TemplateRef} from '@angular/core';
import {ActivatedRoute, Router} from '@angular/router';
import {HttpClient} from '@angular/common/http';
import {
  NB_DATE_ADAPTER,
  NbComponentType,
  NbDatepickerAdapter,
  NbDialogService,
  NbSidebarService,
  NbWindowRef,
  NbWindowService,
  NbWindowState
} from '@nebular/theme';
import {ToastService} from './services/toast.service';
import {UploadService} from './services/upload.service';
import {DomSanitizer, Title} from '@angular/platform-browser';
import {environment} from '../../environments/environment';
import {FormBuilder} from '@angular/forms';
import {WindowContent, AppConfiguration} from './app.data.options';
import {ServerDataSource} from './services/server.data.source';
import {ReplaySubject} from 'rxjs';
import {DynamicScriptLoaderService} from './services/dynamic.script.loader.service';
import {ConfigurationService} from './services/configuration.service';

@Component({
  template: ``,
  selector: 'app-base'
})
export abstract class BaseComponent implements OnInit {

  loading = false;

  title: string;

  mode = {create: '创建', editor: '编辑', delete: '删除', preview: '预览'};

  currentMode = 'create';

  source: ServerDataSource;

  protected nbWindowRef: NbWindowRef;

  protected storeWindow: TemplateRef<any> | NbComponentType<any>;

  protected serviceSourceConf = new ReplaySubject<object>(1);

  submitted: boolean;

  finderFileChoose = new EventEmitter<any>();
  appConfig: AppConfiguration;

  constructor(
      public activateRoute: ActivatedRoute,
      public http: HttpClient,
      public windowService: NbWindowService,
      public toastService: ToastService,
      public uploadService: UploadService,
      public titleService: Title,
      public formBuilder: FormBuilder,
      public domSanitizer: DomSanitizer,
      public loadScript: DynamicScriptLoaderService,
      public dialogService: NbDialogService,
      private config: ConfigurationService,
      public cd: ChangeDetectorRef,
      public sidebarService: NbSidebarService,
      public router: Router,
      @Inject(NB_DATE_ADAPTER) protected datepickerAdapters: NbDatepickerAdapter<any>[]
  ) {
  }

  ngOnInit(): void {
    this.appConfig = this.config.appConfig;
    this.serviceSourceConf.subscribe((serviceSourceConf) => {
      this.source = new ServerDataSource(this.http, serviceSourceConf);
    });
    this.activateRoute.data.subscribe((data) => {
      this.title = data.name;
      this.titleService.setTitle(
        this.title
        + ' - ' +
        environment.project_name
      );
    });
    this.init();
  }

  /**
   *
   * @param message 消息内容
   */
  successToast(message: string) {
    this.toastService.showToast(
      'success',
      this.operationSubject(),
      message
    );
    return true;
  }

  /**
   *
   * @param message 消息内容
   */
  failureToast(message: string) {
    this.toastService.showToast(
      'danger',
      this.operationSubject(),
      message
    );
    return true;
  }

  /**
   * 当前操作主题
   */
  operationSubject(): string {
    return this.withOperationSubject(this.currentMode);
  }

  /**
   *
   * 当前操作名称
   */
  operationName(): string {
    return this.mode[this.currentMode];
  }

  /**
   *
   * @param mode 模式
   */
  withOperationName(mode: string): string {
    return this.mode[mode];
  }

  /**
   *
   * @param mode 械式
   */
  withOperationSubject(mode): string {
    return this.mode[mode] + this.title;
  }

  /**
   *
   * @param mode 操作模式 编辑或创建
   * @param windowClass window class name
   * @param data 传递的参数
   */
  popupOperationDialog(mode: string, windowClass: string = 'col-lg-6', data: object = {}) {
    this.currentMode = mode;
    const content = data || {};
    (<WindowContent>content).mode = mode;
    this.nbWindowRef = this.windowService.open(this.storeWindow, {
      title: this.operationSubject(),
      windowClass: windowClass || 'col-lg-6',
      context: content,
      initialState: NbWindowState.FULL_SCREEN
    });
    this.nbWindowRef.onClose.subscribe(() => {
      this.onCloseDialogCallback();
    });
  }
  openCKFinderPopup(element: string, resourceType = 'Images', multi: boolean = true) {
    const _this = this;
    // @ts-ignore
    window.CKFinder.modal({
      language: 'zh-cn',
      resourceType: resourceType,
      chooseFiles: true,
      selectMultiple: multi,
      onInit: function (finder) {
        finder.on('files:choose', function (evt) {
          const files: {name: string, url: string, pixel: string, size: number, extension: string}[] = [];
          evt.data.files.forEach( (item, index) => {
            files[index] = {
              name: item.attributes.name,
              url: item.attributes.url,
              size: item.attributes.size,
              extension: item._extenstion,
              pixel: item.attributes.hasOwnProperty('imageResizeData')
                ? item.attributes.imageResizeData.attributes.originalSize
                : ''
            };
          });
          _this.finderFileChoose.emit(files);
          document.dispatchEvent(new MouseEvent('click'));
          if (element) {
            (<HTMLInputElement>document.getElementById(element)).value = evt.data.files.last().getUrl();
          }
        });
      }
    });
  }

  init() {
  }
  onCloseDialogCallback() {
  }
}
