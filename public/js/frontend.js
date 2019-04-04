(window["webpackJsonp"] = window["webpackJsonp"] || []).push([["/js/frontend"],{

/***/ "./resources/js/bootstrap.js":
/*!***********************************!*\
  !*** ./resources/js/bootstrap.js ***!
  \***********************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var popper_js_dist_umd_popper__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! popper.js/dist/umd/popper */ "./node_modules/popper.js/dist/umd/popper.js");
/* harmony import */ var popper_js_dist_umd_popper__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(popper_js_dist_umd_popper__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var bootstrap__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! bootstrap */ "./node_modules/bootstrap/dist/js/bootstrap.js");
/* harmony import */ var bootstrap__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(bootstrap__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _fortawesome_fontawesome_svg_core__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @fortawesome/fontawesome-svg-core */ "./node_modules/@fortawesome/fontawesome-svg-core/index.es.js");
/* harmony import */ var _fortawesome_free_brands_svg_icons__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @fortawesome/free-brands-svg-icons */ "./node_modules/@fortawesome/free-brands-svg-icons/index.es.js");
/* harmony import */ var _fortawesome_free_regular_svg_icons__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @fortawesome/free-regular-svg-icons */ "./node_modules/@fortawesome/free-regular-svg-icons/index.es.js");
/* harmony import */ var _fortawesome_free_solid_svg_icons__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @fortawesome/free-solid-svg-icons */ "./node_modules/@fortawesome/free-solid-svg-icons/index.es.js");
/**
 * This bootstrap file is used for both frontend and backend
 */
// import _ from 'lodash'
// import axios from 'axios'

 // Required for BS4


/**
 * Font Awesome >=5.1
 *
 * Is recommended import just the icons that you use, for decrease considerably the file size.
 * You can see at next link, how it works: https://github.com/FortAwesome/Font-Awesome/blob/master/UPGRADING.md#no-more-default-imports
 * Also you can import the icons separately on the frontend and backend
 */





_fortawesome_fontawesome_svg_core__WEBPACK_IMPORTED_MODULE_3__["library"].add(_fortawesome_free_brands_svg_icons__WEBPACK_IMPORTED_MODULE_4__["fab"], _fortawesome_free_regular_svg_icons__WEBPACK_IMPORTED_MODULE_5__["far"], _fortawesome_free_solid_svg_icons__WEBPACK_IMPORTED_MODULE_6__["fas"]); // Kicks off the process of finding <i> tags and replacing with <svg>

_fortawesome_fontawesome_svg_core__WEBPACK_IMPORTED_MODULE_3__["dom"].watch();
/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

window.$ = window.jQuery = jquery__WEBPACK_IMPORTED_MODULE_0___default.a; // window._ = _; // Lodash

/**
 * Bootstrap tooltips
 * https://getbootstrap.com/docs/4.1/components/tooltips/
 */

jquery__WEBPACK_IMPORTED_MODULE_0___default()("body").tooltip({
  selector: '[data-toggle="tooltip"]'
}); // /**
//  * We'll load the axios HTTP library which allows us to easily issue requests
//  * to our Laravel back-end. This library automatically handles sending the
//  * CSRF token as a header based on the value of the "XSRF" token cookie.
//  */
//
// window.axios = axios;
// window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
// /**
//  * Next we will register the CSRF Token as a common header with Axios so that
//  * all outgoing HTTP requests automatically have it attached. This is just
//  * a simple convenience so we don't have to attach every token manually.
//  */
//
// const token = document.head.querySelector('meta[name="csrf-token"]');
//
// if (token) {
//     window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
// } else {
//     console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
// }

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */
// import Echo from 'laravel-echo'
// window.Pusher = require('pusher-js');
// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     encrypted: true
// });

/***/ }),

/***/ "./resources/js/frontend.js":
/*!**********************************!*\
  !*** ./resources/js/frontend.js ***!
  \**********************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _bootstrap__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./bootstrap */ "./resources/js/bootstrap.js");
/* harmony import */ var jquery_typewatch__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! jquery.typewatch */ "./node_modules/jquery.typewatch/jquery.typewatch.js");
/* harmony import */ var jquery_typewatch__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(jquery_typewatch__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var jssocials__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! jssocials */ "./node_modules/jssocials/dist/jssocials.js");
/* harmony import */ var jssocials__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(jssocials__WEBPACK_IMPORTED_MODULE_2__);

/**
 * Copy short url to clipboard
 */
// https://github.com/zenorocha/clipboard.js

var ClipboardJS = __webpack_require__(/*! clipboard */ "./node_modules/clipboard/dist/clipboard.js");

new ClipboardJS('.btn-clipboard').on('success', function () {
  $('.btn-clipboard').attr('data-original-title', 'Copied!').tooltip("_fixTitle").tooltip("show").attr("title", "Copy to clipboard").tooltip("_fixTitle");
});
/**
 * Custom link Avail Check
 */
// https://github.com/dennyferra/TypeWatch


$(function () {
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
  var twOptions = {
    callback: function callback(value) {
      $.ajax({
        url: "/custom-link-avail-check",
        type: 'POST',
        data: {
          'url_key': $('#custom_url_key').val()
        },
        dataType: "json"
      }).done(function (data) {
        if (data.errors) {
          $("#link-availability-status").removeClass("text-success").addClass("text-danger");
          document.getElementById("link-availability-status").innerHTML = data.errors[0];
        } else {
          $("#link-availability-status").removeClass("text-danger").addClass("text-success");
          document.getElementById("link-availability-status").innerHTML = data.success;
        }
      }).fail(function (jqXHR, textStatus) {
        document.getElementById("link-availability-status").innerHTML = "Hmm. We're having trouble connecting to the server.";
      });
      $('#link-availability-status').html('<span><i class="fa fa-spinner"></i> Loading..</span>');
    },
    wait: 500,
    captureLength: 1,
    highlight: true,
    allowSubmit: false
  }; // Add TypeWatch to check when users type

  $('#custom_url_key').typeWatch(twOptions);
});
/**
 * Social Share
 * https://github.com/tabalinas/jssocials
 */


$("#jssocials").jsSocials({
  shareIn: "popup",
  showLabel: false,
  shares: [{
    share: "email",
    logo: "fas fa-envelope"
  }, {
    share: "facebook",
    logo: "fab fa-facebook"
  }, {
    share: "twitter",
    logo: "fab fa-twitter"
  }, {
    share: "telegram",
    logo: "fab fa-telegram",
    shareUrl: "https://telegram.me/share/url?url={url}",
    shareIn: "popup"
  }]
});

/***/ }),

/***/ "./resources/sass/backend/backend.scss":
/*!*********************************************!*\
  !*** ./resources/sass/backend/backend.scss ***!
  \*********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ "./resources/sass/frontend/frontend.scss":
/*!***********************************************!*\
  !*** ./resources/sass/frontend/frontend.scss ***!
  \***********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ 0:
/*!**********************************************************************************************************************!*\
  !*** multi ./resources/js/frontend.js ./resources/sass/backend/backend.scss ./resources/sass/frontend/frontend.scss ***!
  \**********************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(/*! D:\laragon\www\newt-master\resources\js\frontend.js */"./resources/js/frontend.js");
__webpack_require__(/*! D:\laragon\www\newt-master\resources\sass\backend\backend.scss */"./resources/sass/backend/backend.scss");
module.exports = __webpack_require__(/*! D:\laragon\www\newt-master\resources\sass\frontend\frontend.scss */"./resources/sass/frontend/frontend.scss");


/***/ })

},[[0,"/js/manifest","/js/vendor"]]]);
//# sourceMappingURL=frontend.js.map