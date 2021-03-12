import {Component} from '@angular/core';
import {Title} from "@angular/platform-browser";
import {NbThemeService} from "@nebular/theme";
import {CookieService} from "ngx-cookie-service";
import {environment} from "../environments/environment";
import {registerLocaleData} from "@angular/common";
import zh from "@angular/common/locales/zh";
import {ActivatedRoute, NavigationEnd, Router} from "@angular/router";
import {filter, map} from "rxjs/operators";

@Component({
  selector: 'app-root',
  template: `<router-outlet></router-outlet>`,
})
export class AppComponent {
  title: string = environment.project_name;
  constructor(
    protected titleService: Title,
    protected themeService: NbThemeService,
    protected cookieService: CookieService,
    public activatedRoute: ActivatedRoute,
    public router: Router,
  ) {
  }
  ngOnInit(): void {
    const theme = this.cookieService.get('theme');
    if (theme !== '' && theme !== 'default') {
      this.themeService.changeTheme(theme);
    }
    this.router.events.pipe(
      filter(event => event instanceof NavigationEnd),
      map(() => {
        let child = this.activatedRoute.firstChild;
        while (child.firstChild) {
          child = child.firstChild;
        }
        if (child.snapshot.data['title']) {
          return child.snapshot.data['title'] + " - " + this.title;
        }
        return this.title;
      })
    ).subscribe((ttl: string) => {
      this.titleService.setTitle(ttl);
    });
    registerLocaleData(zh)
  }
}
