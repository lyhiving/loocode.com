import {Component, Inject, OnInit} from '@angular/core';
import {ActivatedRoute} from "@angular/router";
import {HttpClient} from "@angular/common/http";
import {AppResponseDataOptions} from "../../../@core/app.data.options";
import {DOCUMENT} from "@angular/common";
import {ToastService} from "../../../@core/services/toast.service";


@Component({
  selector: 'app-navigation',
  templateUrl: './navigation.component.html',
  styleUrls: ['./navigation.component.scss']
})
export class NavigationComponent implements OnInit {
  title: string;
  page: any[] = [];
  post: any[] = [];
  category: any[] = [];
  // tag: any[] = [];
  menu: any[] = [];
  editorId = 0;

  nodes: TreeNode[] = [
    {
      id: 'demo',
      name: '示例1',
      type: 'custom',
      children:[
      ]
    },
  ]

  // ids for connected drop lists
  dropTargetIds = [];
  nodeLookup = {};
  dropActionTodo: DropInfo = null;

  custom = {
    link: "",
    name: "",
  }
  typeMapName = {
    'page': '页面',
    'post': '文章',
    'custom': '自定义链接',
    'category': '分类',
  };
  nav:{[key: string]: any} = {
    id: 0,
    name: "",
  };


  constructor(
    private route: ActivatedRoute,
    private http: HttpClient,
    @Inject(DOCUMENT) private document: Document,
    private toast: ToastService
  ) {
  }

  ngOnInit(): void {
    this.route.data.subscribe((data) => {
      this.title = data.title;
    })
    this.http.get('/navigate/struct/data').subscribe((res: AppResponseDataOptions) => {
      if (res.code === 200) {
        this.page = res.data.page;
        this.post = res.data.post;
        this.category = res.data.categories;
        this.menu = res.data.menu;
      }
    });
    this.prepareDragDrop(this.nodes);
  }

  editor() {
    this.http.get("/navigate/" + this.editorId).subscribe((res:AppResponseDataOptions) => {
      if (res.code === 200) {
        this.editorId = res.data.id;
        this.nav.id = res.data.id;
        this.nav.name  = res.data.name;
        this.nodes = res.data.nodes;
        this.dropTargetIds = [];
        this.nodeLookup = {};
        this.prepareDragDrop(this.nodes);
      }
    });
  }

  prepareDragDrop(nodes: TreeNode[]) {
    nodes.forEach(node => {
      if (!this.dropTargetIds.includes(node.id)) {
        this.dropTargetIds.push(node.id);
      }
      this.nodeLookup[node.id] = node;
      this.prepareDragDrop(node.children);
    });
  }


  // @debounce(50)
  dragMoved(event) {
    let e = this.document.elementFromPoint(event.pointerPosition.x,event.pointerPosition.y);

    if (!e) {
      this.clearDragInfo();
      return;
    }
    let container = e.classList.contains("node-item") ? e : e.closest(".node-item");
    if (!container) {
      this.clearDragInfo();
      return;
    }
    this.dropActionTodo = {
      targetId: container.getAttribute("data-id")
    };
    const targetRect = container.getBoundingClientRect();
    const oneThird = targetRect.height / 3;

    if (event.pointerPosition.y - targetRect.top < oneThird) {
      // before
      this.dropActionTodo["action"] = "before";
    } else if (event.pointerPosition.y - targetRect.top > 2 * oneThird) {
      // after
      this.dropActionTodo["action"] = "after";
    } else {
      // inside
      this.dropActionTodo["action"] = "inside";
    }
    this.showDragInfo();
  }


  drop(event) {
    if (!this.dropActionTodo) return;
    const draggedItemId = event.item.data;
    const parentItemId = event.previousContainer.id;
    const targetListId = this.getParentNodeId(this.dropActionTodo.targetId, this.nodes, 'main');
    console.log(
      '\nmoving\n[' + draggedItemId + '] from list [' + parentItemId + ']',
      '\n[' + this.dropActionTodo.action + ']\n[' + this.dropActionTodo.targetId + '] from list [' + targetListId + ']');

    const draggedItem = this.nodeLookup[draggedItemId];

    const oldItemContainer = parentItemId != 'main' ? this.nodeLookup[parentItemId].children : this.nodes;
    const newContainer = targetListId != 'main' ? this.nodeLookup[targetListId].children : this.nodes;

    let i = oldItemContainer.findIndex(c => c.id === draggedItemId);
    oldItemContainer.splice(i, 1);

    switch (this.dropActionTodo.action) {
      case 'before':
      case 'after':
        const targetIndex = newContainer.findIndex(c => c.id === this.dropActionTodo.targetId);
        if (this.dropActionTodo.action == 'before') {
          newContainer.splice(targetIndex, 0, draggedItem);
        } else {
          newContainer.splice(targetIndex + 1, 0, draggedItem);
        }
        break;

      case 'inside':
        this.nodeLookup[this.dropActionTodo.targetId].children.push(draggedItem)
        this.nodeLookup[this.dropActionTodo.targetId].isExpanded = true;
        break;
    }

    this.clearDragInfo(true)
  }
  getParentNodeId(id: string, nodesToSearch: TreeNode[], parentId: string): string {
    for (let node of nodesToSearch) {
      if (node.id == id) return parentId;
      let ret = this.getParentNodeId(id, node.children, node.id);
      if (ret) return ret;
    }
    return null;
  }
  showDragInfo() {
    this.clearDragInfo();
    if (this.dropActionTodo) {
      this.document.getElementById("node-" + this.dropActionTodo.targetId).classList.add("drop-" + this.dropActionTodo.action);
    }
  }
  clearDragInfo(dropped = false) {
    if (dropped) {
      this.dropActionTodo = null;
    }
    this.document
      .querySelectorAll(".drop-before")
      .forEach(element => element.classList.remove("drop-before"));
    this.document
      .querySelectorAll(".drop-after")
      .forEach(element => element.classList.remove("drop-after"));
    this.document
      .querySelectorAll(".drop-inside")
      .forEach(element => element.classList.remove("drop-inside"));
  }

  delete() {
    this.http.delete("/navigate/" + this.editorId + "/delete")
      .subscribe((res:AppResponseDataOptions) => {
        this.toast.showResponseToast(res.code, this.title, res.message);
      })
  }

  save() {
    this.http.post("/navigate/save", {
      name: this.nav.name,
      id: this.editorId,
      nodes: this.nodes
    }).subscribe((res:AppResponseDataOptions) => {
      this.toast.showResponseToast(res.code, this.title, res.message);
    })
  }

  checked($event: boolean, i: number, type: string) {
    this[type][i].checked = $event;
  }

  addToNav(type: string) {
    switch (type) {
      case 'custom':
        let id = "custom-" + this.custom.name;
        let node = {
          id: this.custom.name,
          name: this.custom.name,
          type: type,
          url: this.custom.link,
          children: [],
        }
        this.custom.name = "";
        this.custom.link = "";
        this.nodes.push(node);
        this.dropTargetIds.push(id);
        this.nodeLookup[id] = node;
        break;
      case 'page':
      case 'post':
      case 'category':
        this[type].forEach((item) => {
          if (item.checked) {
            let id = 'node-' + type + '-' + (item.ID || item.term_taxonomy_id);
            let node = {
              id: id,
              name: item.post_title || item.term.name,
              type: type,
              objectId: (item.ID || item.term_taxonomy_id),
              children: [],
            };
            this.nodes.push(node);
            this.dropTargetIds.push(id);
            this.nodeLookup[id] = node;
            item.checked = false;
          }
        });
        break;
    }
  }

  remove(id: string) {
    this.changeNode(null, this.nodes, id, 'delete');
    delete this.dropTargetIds[id];
    delete this.nodeLookup[id];
  }

  change(value: string, id: string, type: string) {
    this.changeNode(value, this.nodes, id, type)
  }
  private changeNode(value: string, nodes: TreeNode[], id: string, type: string) {
    nodes.forEach((item, index) => {
      if (item.id == id) {
        if (type == 'url') {
          item.url = value
        } else if (type == 'name') {
          item.name = value
        } else {
          nodes.splice(index, 1)
        }
      }
      if (item.children.length > 0) {
        this.changeNode(value, item.children, id, type)
      }
    })
  }

  create() {
    this.editorId = 0;
    this.nav.name = "";
    this.nodes = [
      {
        id: 'demo',
        name: '示例1',
        type: 'custom',
        children:[
        ]
      },
    ];
    this.dropTargetIds = [];
    this.nodeLookup = {};
    this.prepareDragDrop(this.nodes);
  }
}

interface TreeNode {
  id: string;
  name: string;
  objectId?: number;
  url?: string
  children: TreeNode[];
  type: string,
  isExpanded?:boolean;
}

interface DropInfo {
  targetId: string;
  action?: string;
}
