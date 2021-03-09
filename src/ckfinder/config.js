/*
 Copyright (c) 2007-2019, CKSource - Frederico Knabben. All rights reserved.
 For licensing, see LICENSE.html or https://ckeditor.com/sales/license/ckfinder
 */

var config = {};

// Set your configuration options below.

// Examples:
// config.language = 'pl';
// config.skin = 'jquery-mobile';

// Examples:
config.language = 'zh_CN';
// config.skin = 'jquery-mobile';
var hostDomain = window.parent.location.protocol + '//' + window.parent.location.host;
var currentDomain = null;
var selector = document.querySelectorAll("[type=\"text/javascript\"]")
selector.forEach(function (node, index, parent) {
    if (node.nodeName.toLowerCase() === 'script') {
        var l = document.createElement("a");
        l.href = node.src
        currentDomain = l.protocol + '//' + l.hostname
    }
});
config.connectorPath = (currentDomain == null ? hostDomain : currentDomain) + '/backend/ckfinder/connector';
CKFinder.define(config);
