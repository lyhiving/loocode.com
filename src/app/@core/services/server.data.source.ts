import {Injectable} from '@angular/core';
import {ServerDataSource as NgServerDataSource } from 'ng2-smart-table';
import {Observable, Subject} from 'rxjs';
import {map} from 'rxjs/operators';
import {AppResponseDataOptions} from '../app.data.options';

@Injectable()
export class ServerDataSource extends NgServerDataSource {

    public rawData = new Subject<AppResponseDataOptions>();

    protected requestElements(): Observable<any> {
      const httpParams = this.createRequesParams();
      return this.http.get(this.conf.endPoint, { params: httpParams, observe: 'response' }).pipe(
        map((res)  => {
          this.rawData.next(res.body);
          return res;
        })
      );
    }
}
