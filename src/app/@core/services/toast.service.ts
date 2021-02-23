import {NbGlobalPhysicalPosition, NbGlobalPosition, NbToastrService} from '@nebular/theme';
import {NbComponentStatus} from '@nebular/theme/components/component-status';
import {Injectable} from '@angular/core';

@Injectable()
export class ToastService {

  index = 1;
  destroyByClick = true;
  duration = 3500;
  hasIcon = true;
  position: NbGlobalPosition = NbGlobalPhysicalPosition.TOP_RIGHT;
  preventDuplicates = false;

  constructor(protected toastService: NbToastrService) {

  }

  showToast(type: NbComponentStatus, title: string, body: string, position?: NbGlobalPosition) {
    this.makeToast(type, title, body, position);
  }

  showResponseToast(state: number, title: string, body: string, position?: NbGlobalPosition) {
      this.makeToast(state === 200 ? 'success' : 'danger', title, body, position);
  }

  private makeToast(type: NbComponentStatus, title: string, body: string, position?: NbGlobalPosition) {
    const config = {
      status: type,
      destroyByClick: this.destroyByClick,
      duration: this.duration,
      hasIcon: this.hasIcon,
      position: position === undefined ? this.position : position,
      preventDuplicates: this.preventDuplicates,
    };

    this.index += 1;
    this.toastService.show(body, title, config);
  }
}
