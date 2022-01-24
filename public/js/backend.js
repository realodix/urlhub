"use strict";
(self["webpackChunk"] = self["webpackChunk"] || []).push([["/js/backend"],{

/***/ "./resources/js/backend.js":
/*!*********************************!*\
  !*** ./resources/js/backend.js ***!
  \*********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _bootstrap__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./bootstrap */ "./resources/js/bootstrap.js");
/* harmony import */ var _password_toggle__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./password-toggle */ "./resources/js/password-toggle.js");
/* harmony import */ var datatables_net__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! datatables.net */ "./node_modules/datatables.net/js/jquery.dataTables.js");
/* harmony import */ var datatables_net__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(datatables_net__WEBPACK_IMPORTED_MODULE_2__);



$(document).ready(function () {
  /**
   * DataTables
   * https://datatables.net/
   */
  // All URLs Page
  $('#dt-allUrls').DataTable({
    processing: true,
    serverSide: true,
    stateSave: true,
    ajax: '/admin/allurl/getdata',
    columns: [{
      data: 'keyword'
    }, {
      data: 'long_url',
      name: 'meta_title'
    }, {
      data: 'clicks',
      searchable: false
    }, {
      data: 'created_by'
    }, {
      data: 'created_at',
      type: 'num',
      render: {
        _: 'display',
        sort: 'timestamp'
      },
      searchable: false
    }, {
      data: 'action'
    }],
    language: {
      search: "_INPUT_",
      searchPlaceholder: "Search..."
    }
  }).order([4, 'desc']).draw(); // My URLs Page

  $('#dt-myUrls').DataTable({
    processing: true,
    serverSide: true,
    stateSave: true,
    ajax: '/admin/myurl/getdata',
    columns: [{
      data: 'keyword'
    }, {
      data: 'long_url',
      name: 'meta_title'
    }, {
      data: 'clicks',
      searchable: false
    }, {
      data: 'created_at',
      type: 'num',
      render: {
        _: 'display',
        sort: 'timestamp'
      },
      searchable: false
    }, {
      data: 'action'
    }],
    language: {
      search: "_INPUT_",
      searchPlaceholder: "Search..."
    }
  }).order([3, 'desc']).draw(); // All Users Page

  $('#dt-Users').DataTable({
    processing: true,
    serverSide: true,
    stateSave: true,
    ajax: '/admin/user/user/getdata',
    columns: [{
      data: 'name'
    }, {
      data: 'email'
    }, {
      data: 'created_at',
      type: 'num',
      render: {
        _: 'display',
        sort: 'timestamp'
      }
    }, {
      data: 'updated_at',
      type: 'num',
      render: {
        _: 'display',
        sort: 'timestamp'
      },
      searchable: false
    }, {
      data: 'action'
    }],
    language: {
      search: "_INPUT_",
      searchPlaceholder: "Search..."
    }
  }).order([2, 'desc']).draw();
  /**
   * Initialise the password toggle fields.
   */

  (0,_password_toggle__WEBPACK_IMPORTED_MODULE_1__.initPasswordFields)();
});
/**
 * Copy short url to clipboard
 *
 * https://github.com/zenorocha/clipboard.js
 */

var ClipboardJS = __webpack_require__(/*! clipboard */ "./node_modules/clipboard/dist/clipboard.js");

new ClipboardJS('[data-clipboard-text]').on('success', function (e) {
  $(e.trigger).attr('data-original-title', 'Copied!').tooltip("_fixTitle").tooltip("show").attr("title", "Copy to clipboard").tooltip("_fixTitle");
});

/***/ }),

/***/ "./resources/js/bootstrap.js":
/*!***********************************!*\
  !*** ./resources/js/bootstrap.js ***!
  \***********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var bootstrap__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! bootstrap */ "./node_modules/bootstrap/dist/js/bootstrap.esm.js");
/* harmony import */ var _fortawesome_fontawesome_svg_core__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @fortawesome/fontawesome-svg-core */ "./node_modules/@fortawesome/fontawesome-svg-core/index.es.js");
/* harmony import */ var _fortawesome_free_brands_svg_icons__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @fortawesome/free-brands-svg-icons */ "./node_modules/@fortawesome/free-brands-svg-icons/index.es.js");
/* harmony import */ var _fortawesome_free_regular_svg_icons__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @fortawesome/free-regular-svg-icons */ "./node_modules/@fortawesome/free-regular-svg-icons/index.es.js");
/* harmony import */ var _fortawesome_free_solid_svg_icons__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @fortawesome/free-solid-svg-icons */ "./node_modules/@fortawesome/free-solid-svg-icons/index.es.js");
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





_fortawesome_fontawesome_svg_core__WEBPACK_IMPORTED_MODULE_1__.library.add(_fortawesome_free_brands_svg_icons__WEBPACK_IMPORTED_MODULE_2__.fab, _fortawesome_free_regular_svg_icons__WEBPACK_IMPORTED_MODULE_3__.far, _fortawesome_free_solid_svg_icons__WEBPACK_IMPORTED_MODULE_4__.fas); // Kicks off the process of finding <i> tags and replacing with <svg>

_fortawesome_fontawesome_svg_core__WEBPACK_IMPORTED_MODULE_1__.dom.watch();
/**
 * Bootstrap tooltips
 * https://getbootstrap.com/docs/4.3/components/tooltips/
 */
// $("body").tooltip({
//     selector: '[data-toggle="tooltip"]',
// });

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

/***/ "./resources/js/password-toggle.js":
/*!*****************************************!*\
  !*** ./resources/js/password-toggle.js ***!
  \*****************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "initPasswordFields": () => (/* binding */ initPasswordFields)
/* harmony export */ });
/**
 * Export this field so that it can be called in other files.
 */
function initPasswordFields() {
  bindEvents();
}
/**
 * Bind the events that are related to password field toggling.
 */

function bindEvents() {
  $('body').on('click', '.password-toggler', togglePasswordField);
}
/**
 * After the toggler has been clicked, show/hide the password
 * in the input field.
 */


function togglePasswordField() {
  var $inputField = $(this).closest('.password-toggler-container').find('input');

  if ($inputField.attr('type') === 'text') {
    $inputField.attr('type', 'password');
    $(this).find('.fa-eye').removeClass('d-none');
    $(this).find('.fa-eye-slash').addClass('d-none');
  } else {
    $inputField.attr('type', 'text');
    $(this).find('.fa-eye').addClass('d-none');
    $(this).find('.fa-eye-slash').removeClass('d-none');
  }
}

/***/ })

},
/******/ __webpack_require__ => { // webpackRuntimeModules
/******/ var __webpack_exec__ = (moduleId) => (__webpack_require__(__webpack_require__.s = moduleId))
/******/ __webpack_require__.O(0, ["/js/vendor"], () => (__webpack_exec__("./resources/js/backend.js")));
/******/ var __webpack_exports__ = __webpack_require__.O();
/******/ }
]);
//# sourceMappingURL=backend.js.map