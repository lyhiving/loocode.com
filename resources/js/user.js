'use strict';

import * as FilePond from 'filepond';
import * as FilePondPluginFileValidateSize from 'filepond-plugin-file-validate-size';
import * as FilePondPluginImageExifOrientation from 'filepond-plugin-image-exif-orientation';
import * as FilePondPluginFileEncode from 'filepond-plugin-file-encode'

import App from "./main"
import request from 'superagent';

function User() {
    App.call(this)
}
User.prototype = Object.create(App.prototype);
User.prototype.update = function (meta) {
    const _this = this;
    request('POST', this.getRequestUrl('user_update')).send(meta).then(function(response) {
        if (_this.login(response.body.code) && response.body.code === 200) {
            alert("更新成功");
        }
    });
};
(function() {
    FilePond.setOptions({
        allowDrop: true,
        allowReplace: false,
        instantUpload: false,
        labelIdle: "拖拽文件或点击",
        server: {
            url: location.protocol + '//' + location.hostname + (location.port ? ':' + location.port : ''),
            process: '/user/upload',
            revert: null,
            restore: null,
            fetch: null,
        },

    });
    FilePond.registerPlugin(
        FilePondPluginFileEncode,
        FilePondPluginFileValidateSize,
        FilePondPluginImageExifOrientation
    );
    window.pond = null;
    var fields = [].slice.call(document.querySelectorAll('input[type="file"]'));
    fields.forEach(function(field, index) {
        var pondItem = FilePond.create(field);
        pondItem.on('processfile', (error, file) => {
            if (error) {
                return null;
            }
            $(pondItem.element).addClass('processing-complete');
        });
        pondItem.on('processfilerevert', (file) => {
            $(pondItem.element).removeClass('processing-complete');
        });
        if (index === 0) {
            window.pond = pondItem;
        }
    });
    let email = true;
    const emailText = $('#email-text');
    const user = new User()
    $('#btn-email').on('click', function () {
        const value = emailText.val();
        if (!email) {
            if (value.replaceAll(" ", "") === "") {
                alert("填写正确的邮箱");
                return
            }
        }
        email = !email;
        if (email) {
            user.update({email: value});
            emailText.prop("disabled", true);
            $(this).html("修改邮箱");
        } else {
            emailText.prop("disabled", false);
            $(this).html("保存修改");
        }
    });
})();

