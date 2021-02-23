
export class TableSourceService {
  static getServerSourceConf(endPoint: string): object {
     return {
        endPoint: endPoint,
        dataKey: 'data.data',
        totalKey: 'data.total',
        pagerPageKey: 'data.current_page',
        pagerLimitKey: 'data.per_page'
     };
  }
}
