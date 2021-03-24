import {Component, OnInit, ViewChild} from '@angular/core';
import {Observable, of} from "rxjs";
import {SITE_OPTION_SAVE, SITE_OPTIONS} from "../../../../@core/app.interface.data";
import {AppResponseDataOptions} from "../../../../@core/app.data.options";
import {map} from "rxjs/operators";
import {HttpClient} from "@angular/common/http";
import {ToastService} from "../../../../@core/services/toast.service";

@Component({
  selector: 'app-general',
  templateUrl: './general.component.html',
  styleUrls: ['./general.component.scss']
})
export class GeneralComponent implements OnInit {

  option: {[key: string]: any} = {};

  timezone: string[];
  filteredOptions$: Observable<string[]>;
  @ViewChild('autoInput') input;
  submitted: boolean;
  constructor(
    public http: HttpClient,
    public toastService: ToastService
  ) { }

  ngOnInit(): void {
    this.http.get(SITE_OPTIONS).subscribe((res: AppResponseDataOptions) => {
      if (res.code === 200) {
        this.timezone = res.data.timezone;
        this.option = res.data.option;
        this.filteredOptions$ = of(res.data.timezone)
      }
    });
  }
  action($event: any) {
    this.submitted = true
    this.http.post(SITE_OPTION_SAVE, this.option).subscribe((res: AppResponseDataOptions) => {
      this.submitted = false
      this.toastService.showResponseToast(res.code, "站点", res.message)
    }, _ => {
      this.submitted = false
    });
  }

  private filter(value: string): string[] {
    const filterValue = value.toLowerCase();
    return this.timezone.filter(optionValue => optionValue.toLowerCase().includes(filterValue));
  }

  getFilteredOptions(value: string): Observable<string[]> {
    return of(value).pipe(
      map(filterString => this.filter(filterString)),
    );
  }

  onChange() {
    this.filteredOptions$ = this.getFilteredOptions(this.input.nativeElement.value);
  }

  onSelectionChange($event) {
    this.filteredOptions$ = this.getFilteredOptions($event);
  }
}
