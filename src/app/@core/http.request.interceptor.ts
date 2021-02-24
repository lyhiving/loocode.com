import { Injectable } from '@angular/core';
import {
  HttpEvent, HttpInterceptor, HttpHandler, HttpRequest, HttpErrorResponse
} from '@angular/common/http';
import {NbAuthJWTToken, NbAuthService, NbAuthToken} from '@nebular/auth';

import {Observable} from 'rxjs';
import {tap} from 'rxjs/operators';
import {Router} from '@angular/router';
import {environment} from "../../environments/environment";

/** Inject With Credentials into the request */
@Injectable()
export class HttpRequestInterceptor implements HttpInterceptor {

  token: string

  public constructor(
    public router: Router,
    public authService: NbAuthService
  ) {
    this.authService.onTokenChange().subscribe((token: NbAuthToken) => {
      this.token = token.toString()
    })
  }

  intercept(req: HttpRequest<any>, next: HttpHandler): Observable<HttpEvent<any>> {
    console.log('interceptor: ' + req.url);

    req = req.clone({
      url: req.url.indexOf('http') == 0 ? req.url : environment.gateway + req.url,
      withCredentials: false,
      setHeaders: {
        'X-Requested-With': 'XMLHttpRequest',
        'Authorization': this.token,
      }
    });
    return next.handle(req).pipe(
      tap(
        () => {},
        (error: any) => {
          if (error instanceof  HttpErrorResponse) {
            if (error.status !== 401) {
              return;
            }
            this.router.navigate(['/auth/login']).then(r => {
              console.log(r);
            });
          }
        }
      )
    );
  }
}
