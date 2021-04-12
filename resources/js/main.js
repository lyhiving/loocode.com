'use strict';

require("whatwg-fetch");

function App() {
  this.options = {
    url: {
      stats: '/api/post/view',
      like: '/post/like',
      comment: '/post/comment',
      user_update: '/user/update',
    }
  }
}

App.prototype = {
  constructor: App,
  init: function () {
    this.options.path = window.location.pathname
    if (typeof this['bootstrap'] === 'function') {
      this.bootstrap()
    }
    if (typeof this['event'] === 'function') {
      this.event()
    }
  },
  getRequestUrl: function (name) {
    return this.options.url[name];
  },
  login: function () {
    const element = document.querySelector("meta[name='Authorization']")
    if ((arguments.length === 0 && element == null) || (arguments.length > 0 && arguments[0] === 401)) {
      window.dispatchEvent(new CustomEvent('login', {detail: {open: true}}));
      return false
    }
    return true
  }
}
module.exports.App = App;
