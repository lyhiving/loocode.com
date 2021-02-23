import {Component, OnDestroy } from '@angular/core';
import {StateService} from '../../@core/services/state.service';
import {takeWhile} from 'rxjs/operators';
import {environment} from '../../../environments/environment';

@Component({
  selector: 'app-layout',
  templateUrl: './layout.component.html',
  styleUrls: ['./layout.component.scss']
})
export class LayoutComponent implements OnDestroy {

  layout: any = {};

  sidebar: any = {};

  name: string;

  private alive = true;

  constructor(protected stateService: StateService) {
      this.stateService.onSidebarState()
      .pipe(takeWhile(() => this.alive))
      .subscribe((sidebar: string) => {
          this.sidebar = sidebar;
      });
      this.name = environment.project_name;
  }

  ngOnDestroy(): void {
      this.alive = false;
  }

}
