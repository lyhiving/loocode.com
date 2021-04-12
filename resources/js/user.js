'use strict';
import * as FilePond from 'filepond';
import * as FilePondPluginFileValidateSize from 'filepond-plugin-file-validate-size';
import * as FilePondPluginImageExifOrientation from 'filepond-plugin-image-exif-orientation';
import * as FilePondPluginFileEncode from 'filepond-plugin-file-encode'
import {App} from './main'

function User() {
  App.call(this)
}

User.prototype = Object.create(App.prototype);
User.prototype.update = function (meta) {
  global.fetch(
    this.getRequestUrl('user_update'),
    {
      body: JSON.stringify(meta),
      headers: {
        'Content-Type': 'application/json'
      },
      method: "POST"
    }
  ).then((response) => {
    alert("更新成功");
  });
};
(function () {
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
  const fields = [].slice.call(document.querySelectorAll('input[type="file"]'));
  fields.forEach(function (field, index) {
    const pondItem = FilePond.create(field);
    pondItem.on('processfile', (error, file) => {
      if (error) {
        return null;
      }
      (pondItem.element).classList.add('processing-complete');
    });
    pondItem.on('processfilerevert', (file) => {
      (pondItem.element).classList.remove('processing-complete');
    });
    if (index === 0) {
      window.pond = pondItem;
    }
  });
  let email = true;
  const user = new User()
  const emailText = document.getElementById('email-text');
  const btnEmail = document.getElementById('btn-email');
  btnEmail.addEventListener('click', function () {
    const value = emailText.value;
    if (!email) {
      if (value.replaceAll(" ", "") === "") {
        alert("填写正确的邮箱");
        return
      }
    }
    email = !email;
    if (email) {
      user.update({email: value});
      emailText.setAttribute("disabled", true);
      emailText.classList.add("bg-gray-200");
      this.innerHTML = "修改邮箱";
    } else {
      emailText.removeAttribute("disabled");
      emailText.classList.remove("bg-gray-200");
      this.innerHTML = "保存修改";
    }
  });
})();

