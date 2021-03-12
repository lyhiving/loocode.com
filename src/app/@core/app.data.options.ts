export interface AppResponseDataOptions {
    code?: number;
    message?: string;
    data?: any[] | null | any;
}

export interface WindowContent {
    actionName?: string;
    mode: string;
}

export interface AppConfiguration {
  timestamp?: number
}

export interface AppMenuItem {
  icon?: string;
  title?: string;
  path?: string;
  hidden?: boolean;
  sort?: number;
  link?: string;
  action?: string;
  children: AppMenuItem[];
  level: number;
  home?: boolean;
}

export interface Posts {
  id: number;
  title: string;
  termId: number;
  cover: string;
  origin: string;
  originLink: string;
  author: string;
  summary: string;
  content: string;
  views: number;
  status: number;
}

export interface SystemConfigure {
  option_id: number;
  option_name: string;
  option_value: any | any[];
  type: number;
  description?: string;
}

export interface Region {
  id: number;
  name: string;
  keyword: string;
  configure: any;
  weight: number;
  status: number;
  updated_time: string;
  created_time: string;
}

export interface Item {
  id: number;
  aid: number;
  title: string;
  subTitle: string;
  cover: string;
  coverSize: string;
  type: number;
  content: string;
  startTime: number;
  endTime: number;
  forceLogin: number;
  terminal: number;
  weight: number;
  bgColor: string;
  status: number;
}



export interface Manager {
  id: number;
  email: string;
  password: string;
  avatar: string;
  roles: number[];
  lastedIp: string;
  lastedDate: string;
}

export interface  Role {
  id?: number;
  name: string;
  permissions: number[];
  createdDate?: string;
  updatedDate?: string;
}
