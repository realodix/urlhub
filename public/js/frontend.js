"use strict";
(self["webpackChunk"] = self["webpackChunk"] || []).push([["/js/frontend"],{

/***/ "./resources/js/bootstrap.js":
/*!***********************************!*\
  !*** ./resources/js/bootstrap.js ***!
  \***********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _fortawesome_fontawesome_svg_core__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @fortawesome/fontawesome-svg-core */ "./node_modules/@fortawesome/fontawesome-svg-core/index.es.js");
/* harmony import */ var _fortawesome_free_brands_svg_icons__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @fortawesome/free-brands-svg-icons */ "./node_modules/@fortawesome/free-brands-svg-icons/index.es.js");
/* harmony import */ var _fortawesome_free_regular_svg_icons__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @fortawesome/free-regular-svg-icons */ "./node_modules/@fortawesome/free-regular-svg-icons/index.es.js");
/* harmony import */ var _fortawesome_free_solid_svg_icons__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @fortawesome/free-solid-svg-icons */ "./node_modules/@fortawesome/free-solid-svg-icons/index.es.js");
/* harmony import */ var alpinejs__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! alpinejs */ "./node_modules/alpinejs/dist/module.esm.js");
window._ = __webpack_require__(/*! lodash */ "./node_modules/lodash/lodash.js");
window.$ = window.jQuery = __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js");
/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = __webpack_require__(/*! axios */ "./node_modules/axios/index.js");
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
/**
 * Font Awesome >=5.1
 *
 * Is recommended import just the icons that you use, for decrease considerably the file size.
 * You can see at next link, how it works: https://github.com/FortAwesome/Font-Awesome/blob/master/UPGRADING.md#no-more-default-imports
 * Also you can import the icons separately on the frontend and backend
 */





_fortawesome_fontawesome_svg_core__WEBPACK_IMPORTED_MODULE_0__.library.add(_fortawesome_free_brands_svg_icons__WEBPACK_IMPORTED_MODULE_1__.fab, _fortawesome_free_regular_svg_icons__WEBPACK_IMPORTED_MODULE_2__.far, _fortawesome_free_solid_svg_icons__WEBPACK_IMPORTED_MODULE_3__.fas); // Kicks off the process of finding <i> tags and replacing with <svg>

_fortawesome_fontawesome_svg_core__WEBPACK_IMPORTED_MODULE_0__.dom.watch();

window.Alpine = alpinejs__WEBPACK_IMPORTED_MODULE_4__["default"];
alpinejs__WEBPACK_IMPORTED_MODULE_4__["default"].start();
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
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _bootstrap__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./bootstrap */ "./resources/js/bootstrap.js");
/* harmony import */ var jquery_typewatch__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! jquery.typewatch */ "./node_modules/jquery.typewatch/jquery.typewatch.js");
/* harmony import */ var jquery_typewatch__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(jquery_typewatch__WEBPACK_IMPORTED_MODULE_1__);


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

var twOptions = {
  callback: function callback(value) {
    var linkStatus = $("#link-availability-status");
    axios.post('/validate-custom-key', {
      keyword: $('#custom_key').val()
    }).then(function (res) {
      if (res.data.errors) {
        linkStatus.removeClass("text-emerald-600").addClass("text-red-600");
        linkStatus.html(res.data.errors[0]);
      } else {
        linkStatus.removeClass("text-red-600").addClass("text-emerald-600");
        linkStatus.html(res.data.success);
      }
    })["catch"](function (error) {
      linkStatus.html("Hmm. We're having trouble connecting to the server.");
    });
    linkStatus.html('<span><i class="fa fa-spinner"></i> Loading..</span>');
  },
  wait: 500,
  captureLength: 1,
  highlight: true,
  allowSubmit: false
}; // Add TypeWatch to check when users type

$('#custom_key').typeWatch(twOptions);

/***/ }),

/***/ "./resources/sass/backend.scss":
/*!*************************************!*\
  !*** ./resources/sass/backend.scss ***!
  \*************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./resources/sass/frontend.scss":
/*!**************************************!*\
  !*** ./resources/sass/frontend.scss ***!
  \**************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./resources/css/main.css":
/*!********************************!*\
  !*** ./resources/css/main.css ***!
  \********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ })

},
/******/ __webpack_require__ => { // webpackRuntimeModules
/******/ var __webpack_exec__ = (moduleId) => (__webpack_require__(__webpack_require__.s = moduleId))
/******/ __webpack_require__.O(0, ["css/backend","css/main","css/frontend","/js/vendor"], () => (__webpack_exec__("./resources/js/frontend.js"), __webpack_exec__("./resources/sass/backend.scss"), __webpack_exec__("./resources/sass/frontend.scss"), __webpack_exec__("./resources/css/main.css")));
/******/ var __webpack_exports__ = __webpack_require__.O();
/******/ }
]);
//# sourceMappingURL=frontend.js.map