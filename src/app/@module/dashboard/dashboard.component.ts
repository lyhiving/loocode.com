import {
  AfterViewInit,
  Compiler,
  Component,
  ComponentFactory, Injector, Input,
  NgModule,
  NgModuleRef, OnDestroy,
  QueryList,
  ViewChildren,
  ViewContainerRef
} from '@angular/core';
import {DASHBOARD} from '../../@core/app.interface.data';
import {AppResponseDataOptions} from '../../@core/app.data.options';
import {BaseComponent} from '../../@core/base.component';
import {ActivatedRoute, RouterModule} from "@angular/router";
import {CommonModule} from "@angular/common";
import {ThemeModule} from "../../@theme/theme.module";
import {LocalDataSource, Ng2SmartTableModule} from "ng2-smart-table";



export async function createComponentFactory(compiler: Compiler, template: string): Promise<ComponentFactory<any>> {
  // const cmpClass = class DynamicComponent {};
  @Component({
    selector: 'dynamic-selector',
    template: template,
  })
  class DynamicComponent {
    @Input() settings;
    @Input() source;
  }

  // IMPORT ALL MODULES HERE!!!
  @NgModule({ imports: [
      CommonModule,
      RouterModule,
      ThemeModule,
      Ng2SmartTableModule,
      /* All other modules including components that can be use with renderer */
    ],
    declarations: [DynamicComponent] })
  class DynamicHtmlModule {}

  const moduleWithComponentFactory = await compiler.compileModuleAndAllComponentsAsync(DynamicHtmlModule);
  return moduleWithComponentFactory.componentFactories.find(x => x.componentType === DynamicComponent);
}

@Component({
  selector: 'app-dashboard',
  templateUrl: './dashboard.component.html',
  styleUrls: ['./dashboard.component.scss']
})
export class DashboardComponent extends BaseComponent implements AfterViewInit, OnDestroy {
  card: any[] = [];
  @ViewChildren("ref", { read: ViewContainerRef})  public dynComponents: QueryList<ViewContainerRef>;

  cmpRef: any[] = [];

  constructor(
    private readonly r: ActivatedRoute,
    private moduleRef: NgModuleRef<any>,
    private compiler: Compiler,
              ) {
    super(r);
  }
  init() {
    this.http.request('get', DASHBOARD, {})
      .subscribe((res: AppResponseDataOptions) => {
        if (res.code === 200) {
          this.card = res.data;
        }
      });
  }

  protected createComponent(ref: ViewContainerRef, card: {[key:string]:any}) {
    createComponentFactory(this.compiler, card["body"]).then(factory => {
      const injector = Injector.create({ providers: [], parent: ref.injector });
      const cmpRef = ref.createComponent(factory, 0, injector, []);
      if (card["type"] == "table") {
        // @ts-ignore
        cmpRef.instance.settings = card["data"]["settings"];
        cmpRef.instance.source = new LocalDataSource(card["data"]["data"]);
      }
      this.cmpRef.push(cmpRef);
    });
  }
  ngAfterViewInit() {
    console.log(this.dynComponents);
    this.dynComponents.changes.subscribe(() => {
      this.dynComponents.forEach((ref, index) => {
        this.createComponent(ref, this.card[index])
      })
    });
  }

  ngOnDestroy(): void {
    if (this.cmpRef.length > 0) {
      this.cmpRef.forEach((item) => {
        item.destroy();
      })
    }
  }
}
