window.$ = window.jQuery = require('jquery');
require("popper.js");
require("bootstrap");
require("social-share.js");

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
    init: function() {
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
    login: function() {
        if (arguments.length === 0 && $("meta[name='Authorization']").length === 0) {
            $("#login").modal()
            return false
        }
        if (arguments.length > 0 && arguments[0] === 401) {
            $('#login').modal()
            return false
        }
        return true
    }
}

module.exports = App;


