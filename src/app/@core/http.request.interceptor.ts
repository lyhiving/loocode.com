import { Injectable } from '@angular/core';
import {
  HttpEvent, HttpInterceptor, HttpHandler, HttpRequest, HttpErrorResponse
} from '@angular/common/http';

import {Observable} from 'rxjs';
import {tap} from 'rxjs/operators';
import {Router} from '@angular/router';
import {environment} from "../../environments/environment";

/** Inject With Credentials into the request */
@Injectable()
export class HttpRequestInterceptor implements HttpInterceptor {

  public constructor(public router: Router) {
  }

  intercept(req: HttpRequest<any>, next: HttpHandler): Observable<HttpEvent<any>> {
    console.log('interceptor: ' + req.url);

    req = req.clone({
      url: req.url.indexOf('http') == 0 ? req.url : environment.gateway + req.url,
      withCredentials: false,
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
