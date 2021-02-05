const request = require("superagent");
const hljs = require("highlight.js");
const App = require("./main");
function Posts() {
    App.call(this)
    let attribute = {
        id: 0
    };
    this.getAttribute = function(name) {
        return attribute[name];
    }
    this.setAttribute = function(name, value) {
        attribute[name] = value;
    }
}

Posts.prototype = Object.create(App.prototype)
Posts.prototype.view = function(id) {
    request('POST', this.getRequestUrl('stats')).then()
}
Posts.prototype.isPostViews = function() {
    var match = this.options.path.match(/\/(post)\/(\d+)/i)
    if (match !== null && match.length > 2 && match[2] !== undefined) {
        return parseInt(match[2], 10)
    }
    return null
}
Posts.prototype.highlight = function() {
    hljs.initHighlightingOnLoad();
}
Posts.prototype.bootstrap = function() {
    let id = this.isPostViews();
    if (id !== null) {
        this.setAttribute('id', id);
        this.view(id)
    }
    $('[data-toggle="tooltip"]').tooltip()
}
Posts.prototype.event = function() {
    var _this = this
    var events = {
        'click': {
            '.tutorial-tool-share': function() {
                var share = $('.social-share');
                if (share.height() > 0) {
                    share.css({'height': 0, 'overflow': 'hidden'});
                } else {
                    share.css({'height': 200});
                    setTimeout(function() {
                        share.css({'overflow': 'visible'})
                    }, 1000);
                }
            },
            '#btn-like': function(e) {
                _this.login() && request('POST', _this.getRequestUrl('like')).then(function(response) {
                    console.log(response);
                    if (_this.login(response.body.code) && response.body.code === 200) {
                        $('#btn-like').html("已赞")
                            .removeClass('btn-outline-pink')
                            .addClass("btn-pink")
                    }
                });
            },
            '#commentButton': function() {
                var content = $('textarea').val().trim();
                if (content === '') {
                    toastr.error("评论内容不能为空哦!");
                    return false;
                }
                var __this = $(this);
                if (_this.login() && content) {
                    __this.button('loading')
                    request('POST', _this.getRequestUrl('comment')).send({content: content}).then(function(response) {
                        _this.login(response.body.code)
                        if (response.body.code === 200) {
                            $('textarea').val('');
                            window.location = window.location.href + "#reply1";
                        }
                        __this.button('reset');
                    });
                }
                return false;
            }
        }
    };
    var eventName,
        element;
    for (eventName in events) {
        for (element in events[eventName]) {
            if (events[eventName].hasOwnProperty(element)) {
                $(element).on(eventName, events[eventName][element]);
            }
        }
    }
}
Posts.prototype.getRequestUrl = function(name) {
    return this.options.url[name] + '/' + this.getAttribute('id')
}
const app = new Posts();
app.init();
app.highlight();
