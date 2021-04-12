'use strict';

const rocket = document.getElementById('fly-rocket');
rocket.addEventListener('click', function (event) {
  window.scrollTo({
    top: 0,
    behavior: "smooth"
  })
});
