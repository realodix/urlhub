(window["webpackJsonp"] = window["webpackJsonp"] || []).push([["/js/frontend"],{

/***/ "./resources/js/bootstrap.js":
/*!***********************************!*\
  !*** ./resources/js/bootstrap.js ***!
  \***********************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _fortawesome_fontawesome_svg_core__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @fortawesome/fontawesome-svg-core */ "./node_modules/@fortawesome/fontawesome-svg-core/index.es.js");
/* harmony import */ var _fortawesome_free_brands_svg_icons__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @fortawesome/free-brands-svg-icons */ "./node_modules/@fortawesome/free-brands-svg-icons/index.es.js");
/* harmony import */ var _fortawesome_free_regular_svg_icons__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @fortawesome/free-regular-svg-icons */ "./node_modules/@fortawesome/free-regular-svg-icons/index.es.js");
/* harmony import */ var _fortawesome_free_solid_svg_icons__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @fortawesome/free-solid-svg-icons */ "./node_modules/@fortawesome/free-solid-svg-icons/index.es.js");
window._ = __webpack_require__(/*! lodash */ "./node_modules/lodash/lodash.js");
/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
  window.Popper = __webpack_require__(/*! popper.js */ "./node_modules/popper.js/dist/esm/popper.js")["default"];
  window.$ = window.jQuery = __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js");

  __webpack_require__(/*! bootstrap */ "./node_modules/bootstrap/dist/js/bootstrap.js");
} catch (e) {}
/**
 * Font Awesome >=5.1
 *
 * Is recommended import just the icons that you use, for decrease considerably the file size.
 * You can see at next link, how it works: https://github.com/FortAwesome/Font-Awesome/blob/master/UPGRADING.md#no-more-default-imports
 * Also you can import the icons separately on the frontend and backend
 */






_fortawesome_fontawesome_svg_core__WEBPACK_IMPORTED_MODULE_0__["library"].add(_fortawesome_free_brands_svg_icons__WEBPACK_IMPORTED_MODULE_1__["fab"], _fortawesome_free_regular_svg_icons__WEBPACK_IMPORTED_MODULE_2__["far"], _fortawesome_free_solid_svg_icons__WEBPACK_IMPORTED_MODULE_3__["fas"]); // Kicks off the process of finding <i> tags and replacing with <svg>

_fortawesome_fontawesome_svg_core__WEBPACK_IMPORTED_MODULE_0__["dom"].watch();
/**
 * Bootstrap tooltips
 * https://getbootstrap.com/docs/4.3/components/tooltips/
 */

$("body").tooltip({
  selector: '[data-toggle="tooltip"]'
});
/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = __webpack_require__(/*! axios */ "./node_modules/axios/index.js");
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */
// import Echo from 'laravel-echo';
// window.Pusher = require('pusher-js');
// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     forceTLS: true
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
 *
 * https://github.com/zenorocha/clipboard.js
 */

var ClipboardJS = __webpack_require__(/*! clipboard */ "./node_modules/clipboard/dist/clipboard.js");

new ClipboardJS('.btn-clipboard').on('success', function () {
  $('.btn-clipboard').attr('data-original-title', 'Copied!').tooltip("_fixTitle").tooltip("show").attr("title", "Copy to clipboard").tooltip("_fixTitle");
});
/**
 * Custom link Avail Check
 *
 * https://github.com/dennyferra/TypeWatch
 */

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
          'keyword': $('#custom_keyword').val()
        },
        dataType: "json"
      }).done(function (data) {
        if (data.errors) {
          $("#link-availability-status").removeClass("text-success").addClass("text-danger");
          $("#link-availability-status").html(data.errors[0]);
        } else {
          $("#link-availability-status").removeClass("text-danger").addClass("text-success");
          $("#link-availability-status").html(data.success);
        }
      }).fail(function (jqXHR, textStatus) {
        $("#link-availability-status").html("Hmm. We're having trouble connecting to the server.");
      });
      $('#link-availability-status').html('<span><i class="fa fa-spinner"></i> Loading..</span>');
    },
    wait: 500,
    captureLength: 1,
    highlight: true,
    allowSubmit: false
  }; // Add TypeWatch to check when users type

  $('#custom_keyword').typeWatch(twOptions);
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
    share: "whatsapp",
    logo: "fab fa-whatsapp",
    shareUrl: "https://wa.me/?text={url}",
    shareIn: "popup"
  }, {
    share: "telegram",
    logo: "fab fa-telegram",
    shareUrl: "https://telegram.me/share/url?url={url}",
    shareIn: "popup"
  }]
});

/***/ }),

/***/ "./resources/sass/backend.scss":
/*!*************************************!*\
  !*** ./resources/sass/backend.scss ***!
  \*************************************/
/*! no static exports found */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ "./resources/sass/frontend.scss":
/*!**************************************!*\
  !*** ./resources/sass/frontend.scss ***!
  \**************************************/
/*! no static exports found */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ 0:
/*!*****************************************************************************************************!*\
  !*** multi ./resources/js/frontend.js ./resources/sass/backend.scss ./resources/sass/frontend.scss ***!
  \*****************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(/*! E:\laragon\www\urlhub\resources\js\frontend.js */"./resources/js/frontend.js");
__webpack_require__(/*! E:\laragon\www\urlhub\resources\sass\backend.scss */"./resources/sass/backend.scss");
module.exports = __webpack_require__(/*! E:\laragon\www\urlhub\resources\sass\frontend.scss */"./resources/sass/frontend.scss");


/***/ })

},[[0,"/js/manifest","/js/vendor"]]]);
//# sourceMappingURL=frontend.js.map