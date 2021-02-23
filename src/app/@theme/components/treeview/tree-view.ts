

export interface TreeViewOptions {
  textField: string;
  childrenField: string;
  valueField: string;
  mode?: Mode;
  collapsed?: boolean;
  checked?: boolean;
}

export interface TreeViewItem {
  value: any;
  name: string;
  children?: TreeViewItem[];
  collapsed?: boolean;
  checked?: boolean;
}

export enum Mode {
  Normal,
  Checkbox,
  File
}
