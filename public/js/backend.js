webpackJsonp([2],{

/***/ "./resources/js/backend.js":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__bootstrap__ = __webpack_require__("./resources/js/bootstrap.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__coreui_coreui__ = __webpack_require__("./node_modules/@coreui/coreui/dist/js/coreui.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__coreui_coreui___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_1__coreui_coreui__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2_datatables_net__ = __webpack_require__("./node_modules/datatables.net/js/jquery.dataTables.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2_datatables_net___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_2_datatables_net__);



/**
 * DataTables
 * https://datatables.net/
 */

$(document).ready(function () {
  $('#datatables').DataTable();
});

/***/ }),

/***/ "./resources/js/bootstrap.js":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_jquery__ = __webpack_require__("./node_modules/jquery/dist/jquery.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_jquery___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_jquery__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_popper_js_dist_umd_popper__ = __webpack_require__("./node_modules/popper.js/dist/umd/popper.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_popper_js_dist_umd_popper___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_1_popper_js_dist_umd_popper__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2_bootstrap__ = __webpack_require__("./node_modules/bootstrap/dist/js/bootstrap.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2_bootstrap___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_2_bootstrap__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__fortawesome_fontawesome_svg_core__ = __webpack_require__("./node_modules/@fortawesome/fontawesome-svg-core/index.es.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4__fortawesome_free_brands_svg_icons__ = __webpack_require__("./node_modules/@fortawesome/free-brands-svg-icons/index.es.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_5__fortawesome_free_regular_svg_icons__ = __webpack_require__("./node_modules/@fortawesome/free-regular-svg-icons/index.es.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_6__fortawesome_free_solid_svg_icons__ = __webpack_require__("./node_modules/@fortawesome/free-solid-svg-icons/index.es.js");
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






__WEBPACK_IMPORTED_MODULE_3__fortawesome_fontawesome_svg_core__["b" /* library */].add(__WEBPACK_IMPORTED_MODULE_4__fortawesome_free_brands_svg_icons__["a" /* fab */], __WEBPACK_IMPORTED_MODULE_5__fortawesome_free_regular_svg_icons__["a" /* far */], __WEBPACK_IMPORTED_MODULE_6__fortawesome_free_solid_svg_icons__["a" /* fas */]);

// Kicks off the process of finding <i> tags and replacing with <svg>
__WEBPACK_IMPORTED_MODULE_3__fortawesome_fontawesome_svg_core__["a" /* dom */].watch();

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

window.$ = window.jQuery = __WEBPACK_IMPORTED_MODULE_0_jquery___default.a;
// window._ = _; // Lodash

// /**
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

/***/ 1:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__("./resources/js/backend.js");


/***/ })

},[1]);
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9yZXNvdXJjZXMvanMvYmFja2VuZC5qcyIsIndlYnBhY2s6Ly8vLi9yZXNvdXJjZXMvanMvYm9vdHN0cmFwLmpzIl0sIm5hbWVzIjpbIiQiLCJkb2N1bWVudCIsInJlYWR5IiwiRGF0YVRhYmxlIiwibGlicmFyeSIsImFkZCIsImRvbSIsIndhdGNoIiwid2luZG93IiwialF1ZXJ5Il0sIm1hcHBpbmdzIjoiOzs7Ozs7QUFBQTtBQUFBO0FBQUE7QUFBQTtBQUFBO0FBQUE7QUFBQTtBQUNBOztBQUdBOzs7O0FBSUE7QUFDQUEsRUFBRUMsUUFBRixFQUFZQyxLQUFaLENBQWtCLFlBQVc7QUFDekJGLElBQUUsYUFBRixFQUFpQkcsU0FBakI7QUFDSCxDQUZELEU7Ozs7Ozs7O0FDVEE7QUFBQTtBQUFBO0FBQUE7QUFBQTtBQUFBO0FBQUE7QUFBQTtBQUFBO0FBQUE7QUFBQTs7OztBQUlBO0FBQ0E7QUFDQTtDQUNvQztBQUNwQzs7QUFFQTs7Ozs7Ozs7QUFRQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQSxrRkFBT0MsQ0FBQ0MsR0FBUixDQUFZLCtFQUFaLEVBQWlCLGdGQUFqQixFQUFzQiw4RUFBdEI7O0FBRUE7QUFDQSw4RUFBR0MsQ0FBQ0MsS0FBSjs7QUFFQTs7Ozs7O0FBTUFDLE9BQU9SLENBQVAsR0FBV1EsT0FBT0MsTUFBUCxHQUFnQiw4Q0FBM0I7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBOzs7Ozs7QUFNQTs7QUFFQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsTSIsImZpbGUiOiIvanMvYmFja2VuZC5qcyIsInNvdXJjZXNDb250ZW50IjpbImltcG9ydCAnLi9ib290c3RyYXAnO1xyXG5pbXBvcnQgJ0Bjb3JldWkvY29yZXVpJ1xyXG5cclxuXHJcbi8qKlxyXG4gKiBEYXRhVGFibGVzXHJcbiAqIGh0dHBzOi8vZGF0YXRhYmxlcy5uZXQvXHJcbiAqL1xyXG5pbXBvcnQgJ2RhdGF0YWJsZXMubmV0JztcclxuJChkb2N1bWVudCkucmVhZHkoZnVuY3Rpb24oKSB7XHJcbiAgICAkKCcjZGF0YXRhYmxlcycpLkRhdGFUYWJsZSgpO1xyXG59ICk7XHJcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL3Jlc291cmNlcy9qcy9iYWNrZW5kLmpzIiwiLyoqXHJcbiAqIFRoaXMgYm9vdHN0cmFwIGZpbGUgaXMgdXNlZCBmb3IgYm90aCBmcm9udGVuZCBhbmQgYmFja2VuZFxyXG4gKi9cclxuXHJcbi8vIGltcG9ydCBfIGZyb20gJ2xvZGFzaCdcclxuLy8gaW1wb3J0IGF4aW9zIGZyb20gJ2F4aW9zJ1xyXG5pbXBvcnQgJCBmcm9tICdqcXVlcnknO1xyXG5pbXBvcnQgJ3BvcHBlci5qcy9kaXN0L3VtZC9wb3BwZXInOyAvLyBSZXF1aXJlZCBmb3IgQlM0XHJcbmltcG9ydCAnYm9vdHN0cmFwJztcclxuXHJcbi8qKlxyXG4gKiBGb250IEF3ZXNvbWUgPj01LjFcclxuICpcclxuICogSXMgcmVjb21tZW5kZWQgaW1wb3J0IGp1c3QgdGhlIGljb25zIHRoYXQgeW91IHVzZSwgZm9yIGRlY3JlYXNlIGNvbnNpZGVyYWJseSB0aGUgZmlsZSBzaXplLlxyXG4gKiBZb3UgY2FuIHNlZSBhdCBuZXh0IGxpbmssIGhvdyBpdCB3b3JrczogaHR0cHM6Ly9naXRodWIuY29tL0ZvcnRBd2Vzb21lL0ZvbnQtQXdlc29tZS9ibG9iL21hc3Rlci9VUEdSQURJTkcubWQjbm8tbW9yZS1kZWZhdWx0LWltcG9ydHNcclxuICogQWxzbyB5b3UgY2FuIGltcG9ydCB0aGUgaWNvbnMgc2VwYXJhdGVseSBvbiB0aGUgZnJvbnRlbmQgYW5kIGJhY2tlbmRcclxuICovXHJcblxyXG5pbXBvcnQgeyBsaWJyYXJ5LCBkb20gfSBmcm9tICdAZm9ydGF3ZXNvbWUvZm9udGF3ZXNvbWUtc3ZnLWNvcmUnO1xyXG5pbXBvcnQgeyBmYWIgfSBmcm9tICdAZm9ydGF3ZXNvbWUvZnJlZS1icmFuZHMtc3ZnLWljb25zJztcclxuaW1wb3J0IHsgZmFyIH0gZnJvbSAnQGZvcnRhd2Vzb21lL2ZyZWUtcmVndWxhci1zdmctaWNvbnMnO1xyXG5pbXBvcnQgeyBmYXMgfSBmcm9tICdAZm9ydGF3ZXNvbWUvZnJlZS1zb2xpZC1zdmctaWNvbnMnO1xyXG5cclxubGlicmFyeS5hZGQoZmFiLCBmYXIsIGZhcyk7XHJcblxyXG4vLyBLaWNrcyBvZmYgdGhlIHByb2Nlc3Mgb2YgZmluZGluZyA8aT4gdGFncyBhbmQgcmVwbGFjaW5nIHdpdGggPHN2Zz5cclxuZG9tLndhdGNoKCk7XHJcblxyXG4vKipcclxuICogV2UnbGwgbG9hZCBqUXVlcnkgYW5kIHRoZSBCb290c3RyYXAgalF1ZXJ5IHBsdWdpbiB3aGljaCBwcm92aWRlcyBzdXBwb3J0XHJcbiAqIGZvciBKYXZhU2NyaXB0IGJhc2VkIEJvb3RzdHJhcCBmZWF0dXJlcyBzdWNoIGFzIG1vZGFscyBhbmQgdGFicy4gVGhpc1xyXG4gKiBjb2RlIG1heSBiZSBtb2RpZmllZCB0byBmaXQgdGhlIHNwZWNpZmljIG5lZWRzIG9mIHlvdXIgYXBwbGljYXRpb24uXHJcbiAqL1xyXG5cclxud2luZG93LiQgPSB3aW5kb3cualF1ZXJ5ID0gJDtcclxuLy8gd2luZG93Ll8gPSBfOyAvLyBMb2Rhc2hcclxuXHJcbi8vIC8qKlxyXG4vLyAgKiBXZSdsbCBsb2FkIHRoZSBheGlvcyBIVFRQIGxpYnJhcnkgd2hpY2ggYWxsb3dzIHVzIHRvIGVhc2lseSBpc3N1ZSByZXF1ZXN0c1xyXG4vLyAgKiB0byBvdXIgTGFyYXZlbCBiYWNrLWVuZC4gVGhpcyBsaWJyYXJ5IGF1dG9tYXRpY2FsbHkgaGFuZGxlcyBzZW5kaW5nIHRoZVxyXG4vLyAgKiBDU1JGIHRva2VuIGFzIGEgaGVhZGVyIGJhc2VkIG9uIHRoZSB2YWx1ZSBvZiB0aGUgXCJYU1JGXCIgdG9rZW4gY29va2llLlxyXG4vLyAgKi9cclxuLy9cclxuLy8gd2luZG93LmF4aW9zID0gYXhpb3M7XHJcbi8vIHdpbmRvdy5heGlvcy5kZWZhdWx0cy5oZWFkZXJzLmNvbW1vblsnWC1SZXF1ZXN0ZWQtV2l0aCddID0gJ1hNTEh0dHBSZXF1ZXN0JztcclxuXHJcbi8vIC8qKlxyXG4vLyAgKiBOZXh0IHdlIHdpbGwgcmVnaXN0ZXIgdGhlIENTUkYgVG9rZW4gYXMgYSBjb21tb24gaGVhZGVyIHdpdGggQXhpb3Mgc28gdGhhdFxyXG4vLyAgKiBhbGwgb3V0Z29pbmcgSFRUUCByZXF1ZXN0cyBhdXRvbWF0aWNhbGx5IGhhdmUgaXQgYXR0YWNoZWQuIFRoaXMgaXMganVzdFxyXG4vLyAgKiBhIHNpbXBsZSBjb252ZW5pZW5jZSBzbyB3ZSBkb24ndCBoYXZlIHRvIGF0dGFjaCBldmVyeSB0b2tlbiBtYW51YWxseS5cclxuLy8gICovXHJcbi8vXHJcbi8vIGNvbnN0IHRva2VuID0gZG9jdW1lbnQuaGVhZC5xdWVyeVNlbGVjdG9yKCdtZXRhW25hbWU9XCJjc3JmLXRva2VuXCJdJyk7XHJcbi8vXHJcbi8vIGlmICh0b2tlbikge1xyXG4vLyAgICAgd2luZG93LmF4aW9zLmRlZmF1bHRzLmhlYWRlcnMuY29tbW9uWydYLUNTUkYtVE9LRU4nXSA9IHRva2VuLmNvbnRlbnQ7XHJcbi8vIH0gZWxzZSB7XHJcbi8vICAgICBjb25zb2xlLmVycm9yKCdDU1JGIHRva2VuIG5vdCBmb3VuZDogaHR0cHM6Ly9sYXJhdmVsLmNvbS9kb2NzL2NzcmYjY3NyZi14LWNzcmYtdG9rZW4nKTtcclxuLy8gfVxyXG5cclxuLyoqXHJcbiAqIEVjaG8gZXhwb3NlcyBhbiBleHByZXNzaXZlIEFQSSBmb3Igc3Vic2NyaWJpbmcgdG8gY2hhbm5lbHMgYW5kIGxpc3RlbmluZ1xyXG4gKiBmb3IgZXZlbnRzIHRoYXQgYXJlIGJyb2FkY2FzdCBieSBMYXJhdmVsLiBFY2hvIGFuZCBldmVudCBicm9hZGNhc3RpbmdcclxuICogYWxsb3dzIHlvdXIgdGVhbSB0byBlYXNpbHkgYnVpbGQgcm9idXN0IHJlYWwtdGltZSB3ZWIgYXBwbGljYXRpb25zLlxyXG4gKi9cclxuXHJcbi8vIGltcG9ydCBFY2hvIGZyb20gJ2xhcmF2ZWwtZWNobydcclxuXHJcbi8vIHdpbmRvdy5QdXNoZXIgPSByZXF1aXJlKCdwdXNoZXItanMnKTtcclxuXHJcbi8vIHdpbmRvdy5FY2hvID0gbmV3IEVjaG8oe1xyXG4vLyAgICAgYnJvYWRjYXN0ZXI6ICdwdXNoZXInLFxyXG4vLyAgICAga2V5OiBwcm9jZXNzLmVudi5NSVhfUFVTSEVSX0FQUF9LRVlcclxuLy8gICAgIGNsdXN0ZXI6IHByb2Nlc3MuZW52Lk1JWF9QVVNIRVJfQVBQX0NMVVNURVIsXHJcbi8vICAgICBlbmNyeXB0ZWQ6IHRydWVcclxuLy8gfSk7XHJcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL3Jlc291cmNlcy9qcy9ib290c3RyYXAuanMiXSwic291cmNlUm9vdCI6IiJ9