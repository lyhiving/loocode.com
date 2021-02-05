'use strict';

const app = require("./main")
/**
 * Created by zhoutianliang on 2017/6/15.
 */
$('.cd-top-trigger').on('click', function(event) {
    window.scrollTo(0, 0);
});

const wh = $(window).height();
const bh = $(document.body).height();
if (bh < wh) {
    $('footer').css('marginTop', wh - bh + 50);
}

