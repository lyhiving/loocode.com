import {Component} from '@angular/core';
import {Title} from "@angular/platform-browser";
import {NbThemeService} from "@nebular/theme";
import {CookieService} from "ngx-cookie-service";
import {environment} from "../environments/environment";

@Component({
  selector: 'app-root',
  template: `<router-outlet></router-outlet>`,
})
export class AppComponent {
  constructor(
    protected titleService: Title,
    protected themeService: NbThemeService,
    protected cookieService: CookieService
  ) {
  }
  ngOnInit(): void {
    this.titleService.setTitle(environment.project_name);
    const theme = this.cookieService.get('theme');
    if (theme !== '' && theme !== 'default') {
      this.themeService.changeTheme(theme);
    }
  }
}
