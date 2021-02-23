import {
  AfterViewInit,
  Component,
  DoCheck,
  EventEmitter,
  Input,
  OnChanges,
  OnDestroy,
  OnInit,
  Output,
  SimpleChanges,
} from '@angular/core';
import {Mode, TreeViewItem, TreeViewOptions} from './tree-view';
import {isArray, isNil, isBoolean, concat} from 'lodash';

@Component({
  selector: 'app-tree',
  templateUrl: './tree-view.component.html',
})
export class TreeViewComponent implements OnInit, OnChanges {

  constructor() { }

  @Input() items: any[];

  filterItems: TreeViewItem[] = [];

  @Input() options: TreeViewOptions;

  @Output() selectedChange = new EventEmitter<any[]>();

  private defaultOptions: TreeViewOptions = {
    textField: 'name',
    childrenField: 'children',
    valueField: 'id',
    mode: Mode.Checkbox,
    collapsed: true,
    checked: false,
  };

  ngOnInit() {
  }

  ngOnChanges(changes: SimpleChanges): void {
    const itemsChange = changes['items'];
    const options = changes['options'];
    if (isNil(options)) {
        this.options = this.defaultOptions;
    }
    if (isNil(this.options.mode)) {
      this.options.mode = Mode.Checkbox;
    }
    if (isNil(this.options.collapsed) || !isBoolean(this.options.collapsed)) {
      this.options.collapsed = true;
    }
    if (isNil(this.options.checked) || !isBoolean(this.options.checked)) {
      this.options.collapsed = false;
    }

    if (!isNil(itemsChange) && !isNil(this.items)) {
      this.items.forEach((item) => {
        this. rebuildItem(item, null);
      });
    }
  }

  private rebuildItem(item, prevChildren) {
    const rebuildChildren: TreeViewItem[] = [];
    const rebuildItem: TreeViewItem = {
      name: item[this.options.textField],
      value: item[this.options.valueField],
      children: rebuildChildren,
      collapsed: isBoolean(item.collapsed) ? item.collapsed : this.options.collapsed,
      checked: isBoolean(item.checked)
        ? item.checked
        : item.hasOwnProperty('checked') && item.checked === undefined ? undefined : this.options.checked,
    };
    const children = item[this.options.childrenField];
    if (!isNil(children) && isArray(children) && children.length > 0) {
      children.forEach((child) => {
        this.rebuildItem(child, rebuildChildren);
      });
    }
    if (prevChildren !== null) {
      prevChildren.push(rebuildItem);
    } else {
      this.filterItems.push(rebuildItem);
    }
  }

  onChildCheckedChange(item: TreeViewItem, $event: boolean) {
    const selection = this.getSelection();
    this.selectedChange.emit(selection.checkedItems);
  }

  private getSelection() {
    let checkedItems: TreeViewItem[] = [];
    let uncheckedItems: TreeViewItem[] = [];
    if (!isNil(this.filterItems)) {
      const selection = this.concatSelection(this.filterItems, checkedItems, uncheckedItems);
      checkedItems = selection.checked;
      uncheckedItems = selection.unchecked;
    }
    return {
      checkedItems: checkedItems,
      uncheckedItems: uncheckedItems
    };
  }
  private concatSelection(items: TreeViewItem[], checked: TreeViewItem[], unchecked: TreeViewItem[]): { [k: string]: TreeViewItem[] } {
    for (const item of items) {
      console.log(item);
      console.log(item.checked);
      if (item.checked || item.checked === undefined) {
        checked.push(item.value);
      } else {
        unchecked.push(item.value);
      }
      if (item.children.length > 0) {
        this.concatSelection(item.children, checked, unchecked);
      }
    }
    return {
      checked: checked,
      unchecked: unchecked
    };
  }
}

@Component({
  selector: 'app-tree-item',
  templateUrl: './tree-item.component.html',
  styleUrls: ['./tree-item.component.scss']
})
export class TreeViewItemComponent implements DoCheck, AfterViewInit, OnDestroy {

  @Input() item: TreeViewItem;

  @Input() options: TreeViewOptions;

  @Output() checkedChange = new EventEmitter<boolean>();

  ngAfterViewInit(): void {

  }

  ngDoCheck(): void {
  }

  ngOnDestroy(): void {
  }

  onCollapseExpand() {
    const children = this.item[this.options.childrenField];
    if (!isNil(children) && isArray(children) && children.length > 0) {
      this.item.collapsed = !this.item.collapsed;
    }

  }

  onCheckedChange(checked: boolean) {
    this.item.children.forEach((child) => {
      this.setCheckedRecursive(child, checked);
    });
    this.checkedChange.emit(checked);
  }


  onChildCheckedChange(child, checked: boolean) {
    let itemChecked: boolean = null;
    const children = this.item[this.options.childrenField];
    if (!isNil(children) && isArray(children) && children.length > 0) {
      for (const childItem of children) {
        if (itemChecked === null) {
          itemChecked = childItem.checked;
        } else if (itemChecked !== childItem.checked) {
          itemChecked = undefined;
          break;
        }
      }
      if (itemChecked === null) {
        itemChecked = false;
      }
      if (this.item.checked !== itemChecked) {
        this.item.checked = itemChecked;
      }
    }
    this.checkedChange.emit(checked);
  }

  get indeterminate(): boolean {
    return this.item.checked === undefined;
  }

  private setCheckedRecursive(item: TreeViewItem, checked: boolean) {
    item.checked = checked;
    if (item.children.length > 0) {
      item.children.forEach((child) => {
        this.setCheckedRecursive(child, checked);
      });
    }
  }
}

