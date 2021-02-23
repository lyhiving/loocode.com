import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {CONFIGURATION} from '../app.interface.data';
import {AppConfiguration, AppResponseDataOptions} from '../app.data.options';

@Injectable()
export class ConfigurationService {
  appConfig: AppConfiguration = null;
  constructor(private http: HttpClient) {
  }
  load(): Promise<any>  {
    return this.http.get(CONFIGURATION)
      .toPromise()
      .then((res: AppResponseDataOptions) => {
        if (res.code === 200) {
          this.appConfig = res.data;
        }
        return res.data;
      });
  }
}
