webpackJsonp([1],{

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

/***/ "./resources/js/frontend.js":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__bootstrap__ = __webpack_require__("./resources/js/bootstrap.js");


/**
 * ClipboardJS
 * https://github.com/zenorocha/clipboard.js
 */
var ClipboardJS = __webpack_require__("./node_modules/clipboard/dist/clipboard.js");
new ClipboardJS('.btn-copy');

/**
 * Social Share
 */
$(function () {
    if ($('.socials-share').length) {
        $('.social-share').on('mouseleave', function () {
            $(this).find('.social-share-menu').hide();
        });
        // Action on click share network
        $('.social-share-network').on('click', function () {
            var socialNetwork = $(this).data('social-network');
            var shareUrl = $(this).parent().data('share-url');
            popupSocialShare(socialNetwork, shareUrl);
        });
    }

    function popupSocialShare(socialNetwork, shareUrl) {
        switch (socialNetwork) {
            case 'facebook':
                window.open("https://www.facebook.com/sharer/sharer.php?u=" + shareUrl, "pop", "width=600, height=400, scrollbars=no");
                return false;
                break;
            case 'google':
                window.open("https://plus.google.com/share?url=" + shareUrl, "pop", "width=600, height=400, scrollbars=no");
                return false;
                break;
            case 'twitter':
                window.open("http://twitter.com/intent/tweet?url=" + shareUrl, "pop", "width=600, height=400, scrollbars=no");
                return false;
                break;
            default:
                break;
        }
    }
});

/***/ }),

/***/ "./resources/sass/backend/backend.scss":
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ "./resources/sass/frontend/frontend.scss":
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ 0:
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__("./resources/js/frontend.js");
__webpack_require__("./resources/sass/backend/backend.scss");
module.exports = __webpack_require__("./resources/sass/frontend/frontend.scss");


/***/ })

},[0]);
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9yZXNvdXJjZXMvanMvYm9vdHN0cmFwLmpzIiwid2VicGFjazovLy8uL3Jlc291cmNlcy9qcy9mcm9udGVuZC5qcyIsIndlYnBhY2s6Ly8vLi9yZXNvdXJjZXMvc2Fzcy9iYWNrZW5kL2JhY2tlbmQuc2NzcyIsIndlYnBhY2s6Ly8vLi9yZXNvdXJjZXMvc2Fzcy9mcm9udGVuZC9mcm9udGVuZC5zY3NzIl0sIm5hbWVzIjpbImxpYnJhcnkiLCJhZGQiLCJkb20iLCJ3YXRjaCIsIndpbmRvdyIsIiQiLCJqUXVlcnkiLCJDbGlwYm9hcmRKUyIsInJlcXVpcmUiLCJsZW5ndGgiLCJvbiIsImZpbmQiLCJoaWRlIiwic29jaWFsTmV0d29yayIsImRhdGEiLCJzaGFyZVVybCIsInBhcmVudCIsInBvcHVwU29jaWFsU2hhcmUiLCJvcGVuIl0sIm1hcHBpbmdzIjoiOzs7Ozs7QUFBQTtBQUFBO0FBQUE7QUFBQTtBQUFBO0FBQUE7QUFBQTtBQUFBO0FBQUE7QUFBQTtBQUFBOzs7O0FBSUE7QUFDQTtBQUNBO0NBQ29DO0FBQ3BDOztBQUVBOzs7Ozs7OztBQVFBO0FBQ0E7QUFDQTtBQUNBOztBQUVBLGtGQUFPQSxDQUFDQyxHQUFSLENBQVksK0VBQVosRUFBaUIsZ0ZBQWpCLEVBQXNCLDhFQUF0Qjs7QUFFQTtBQUNBLDhFQUFHQyxDQUFDQyxLQUFKOztBQUVBOzs7Ozs7QUFNQUMsT0FBT0MsQ0FBUCxHQUFXRCxPQUFPRSxNQUFQLEdBQWdCLDhDQUEzQjtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7Ozs7OztBQU1BOztBQUVBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxNOzs7Ozs7OztBQzNFQTtBQUFBO0FBQUE7O0FBRUE7Ozs7QUFJQSxJQUFJQyxjQUFjLG1CQUFPQyxDQUFDLDRDQUFSLENBQWxCO0FBQ0EsSUFBSUQsV0FBSixDQUFnQixXQUFoQjs7QUFHQTs7O0FBR0FGLEVBQUUsWUFBVztBQUNULFFBQUlBLEVBQUUsZ0JBQUYsRUFBb0JJLE1BQXhCLEVBQWdDO0FBQzVCSixVQUFFLGVBQUYsRUFBbUJLLEVBQW5CLENBQXNCLFlBQXRCLEVBQW9DLFlBQVk7QUFDNUNMLGNBQUUsSUFBRixFQUFRTSxJQUFSLENBQWEsb0JBQWIsRUFBbUNDLElBQW5DO0FBQ0gsU0FGRDtBQUdBO0FBQ0FQLFVBQUUsdUJBQUYsRUFBMkJLLEVBQTNCLENBQThCLE9BQTlCLEVBQXVDLFlBQVc7QUFDOUMsZ0JBQUlHLGdCQUFnQlIsRUFBRSxJQUFGLEVBQVFTLElBQVIsQ0FBYSxnQkFBYixDQUFwQjtBQUNBLGdCQUFJQyxXQUFXVixFQUFFLElBQUYsRUFBUVcsTUFBUixHQUFpQkYsSUFBakIsQ0FBc0IsV0FBdEIsQ0FBZjtBQUNBRyw2QkFBaUJKLGFBQWpCLEVBQWdDRSxRQUFoQztBQUNILFNBSkQ7QUFLSDs7QUFFRCxhQUFTRSxnQkFBVCxDQUEwQkosYUFBMUIsRUFBeUNFLFFBQXpDLEVBQW1EO0FBQy9DLGdCQUFRRixhQUFSO0FBQ0ksaUJBQUssVUFBTDtBQUNJVCx1QkFBT2MsSUFBUCxDQUFZLGtEQUFrREgsUUFBOUQsRUFBd0UsS0FBeEUsRUFBK0Usc0NBQS9FO0FBQ0EsdUJBQU8sS0FBUDtBQUNBO0FBQ0osaUJBQUssUUFBTDtBQUNJWCx1QkFBT2MsSUFBUCxDQUFZLHVDQUF1Q0gsUUFBbkQsRUFBNkQsS0FBN0QsRUFBb0Usc0NBQXBFO0FBQ0EsdUJBQU8sS0FBUDtBQUNBO0FBQ0osaUJBQUssU0FBTDtBQUNJWCx1QkFBT2MsSUFBUCxDQUFZLHlDQUF5Q0gsUUFBckQsRUFBZ0UsS0FBaEUsRUFBdUUsc0NBQXZFO0FBQ0EsdUJBQU8sS0FBUDtBQUNBO0FBQ0o7QUFDSTtBQWRSO0FBZ0JIO0FBQ0osQ0EvQkQsRTs7Ozs7OztBQ2JBLHlDOzs7Ozs7O0FDQUEseUMiLCJmaWxlIjoiL2pzL2Zyb250ZW5kLmpzIiwic291cmNlc0NvbnRlbnQiOlsiLyoqXHJcbiAqIFRoaXMgYm9vdHN0cmFwIGZpbGUgaXMgdXNlZCBmb3IgYm90aCBmcm9udGVuZCBhbmQgYmFja2VuZFxyXG4gKi9cclxuXHJcbi8vIGltcG9ydCBfIGZyb20gJ2xvZGFzaCdcclxuLy8gaW1wb3J0IGF4aW9zIGZyb20gJ2F4aW9zJ1xyXG5pbXBvcnQgJCBmcm9tICdqcXVlcnknO1xyXG5pbXBvcnQgJ3BvcHBlci5qcy9kaXN0L3VtZC9wb3BwZXInOyAvLyBSZXF1aXJlZCBmb3IgQlM0XHJcbmltcG9ydCAnYm9vdHN0cmFwJztcclxuXHJcbi8qKlxyXG4gKiBGb250IEF3ZXNvbWUgPj01LjFcclxuICpcclxuICogSXMgcmVjb21tZW5kZWQgaW1wb3J0IGp1c3QgdGhlIGljb25zIHRoYXQgeW91IHVzZSwgZm9yIGRlY3JlYXNlIGNvbnNpZGVyYWJseSB0aGUgZmlsZSBzaXplLlxyXG4gKiBZb3UgY2FuIHNlZSBhdCBuZXh0IGxpbmssIGhvdyBpdCB3b3JrczogaHR0cHM6Ly9naXRodWIuY29tL0ZvcnRBd2Vzb21lL0ZvbnQtQXdlc29tZS9ibG9iL21hc3Rlci9VUEdSQURJTkcubWQjbm8tbW9yZS1kZWZhdWx0LWltcG9ydHNcclxuICogQWxzbyB5b3UgY2FuIGltcG9ydCB0aGUgaWNvbnMgc2VwYXJhdGVseSBvbiB0aGUgZnJvbnRlbmQgYW5kIGJhY2tlbmRcclxuICovXHJcblxyXG5pbXBvcnQgeyBsaWJyYXJ5LCBkb20gfSBmcm9tICdAZm9ydGF3ZXNvbWUvZm9udGF3ZXNvbWUtc3ZnLWNvcmUnO1xyXG5pbXBvcnQgeyBmYWIgfSBmcm9tICdAZm9ydGF3ZXNvbWUvZnJlZS1icmFuZHMtc3ZnLWljb25zJztcclxuaW1wb3J0IHsgZmFyIH0gZnJvbSAnQGZvcnRhd2Vzb21lL2ZyZWUtcmVndWxhci1zdmctaWNvbnMnO1xyXG5pbXBvcnQgeyBmYXMgfSBmcm9tICdAZm9ydGF3ZXNvbWUvZnJlZS1zb2xpZC1zdmctaWNvbnMnO1xyXG5cclxubGlicmFyeS5hZGQoZmFiLCBmYXIsIGZhcyk7XHJcblxyXG4vLyBLaWNrcyBvZmYgdGhlIHByb2Nlc3Mgb2YgZmluZGluZyA8aT4gdGFncyBhbmQgcmVwbGFjaW5nIHdpdGggPHN2Zz5cclxuZG9tLndhdGNoKCk7XHJcblxyXG4vKipcclxuICogV2UnbGwgbG9hZCBqUXVlcnkgYW5kIHRoZSBCb290c3RyYXAgalF1ZXJ5IHBsdWdpbiB3aGljaCBwcm92aWRlcyBzdXBwb3J0XHJcbiAqIGZvciBKYXZhU2NyaXB0IGJhc2VkIEJvb3RzdHJhcCBmZWF0dXJlcyBzdWNoIGFzIG1vZGFscyBhbmQgdGFicy4gVGhpc1xyXG4gKiBjb2RlIG1heSBiZSBtb2RpZmllZCB0byBmaXQgdGhlIHNwZWNpZmljIG5lZWRzIG9mIHlvdXIgYXBwbGljYXRpb24uXHJcbiAqL1xyXG5cclxud2luZG93LiQgPSB3aW5kb3cualF1ZXJ5ID0gJDtcclxuLy8gd2luZG93Ll8gPSBfOyAvLyBMb2Rhc2hcclxuXHJcbi8vIC8qKlxyXG4vLyAgKiBXZSdsbCBsb2FkIHRoZSBheGlvcyBIVFRQIGxpYnJhcnkgd2hpY2ggYWxsb3dzIHVzIHRvIGVhc2lseSBpc3N1ZSByZXF1ZXN0c1xyXG4vLyAgKiB0byBvdXIgTGFyYXZlbCBiYWNrLWVuZC4gVGhpcyBsaWJyYXJ5IGF1dG9tYXRpY2FsbHkgaGFuZGxlcyBzZW5kaW5nIHRoZVxyXG4vLyAgKiBDU1JGIHRva2VuIGFzIGEgaGVhZGVyIGJhc2VkIG9uIHRoZSB2YWx1ZSBvZiB0aGUgXCJYU1JGXCIgdG9rZW4gY29va2llLlxyXG4vLyAgKi9cclxuLy9cclxuLy8gd2luZG93LmF4aW9zID0gYXhpb3M7XHJcbi8vIHdpbmRvdy5heGlvcy5kZWZhdWx0cy5oZWFkZXJzLmNvbW1vblsnWC1SZXF1ZXN0ZWQtV2l0aCddID0gJ1hNTEh0dHBSZXF1ZXN0JztcclxuXHJcbi8vIC8qKlxyXG4vLyAgKiBOZXh0IHdlIHdpbGwgcmVnaXN0ZXIgdGhlIENTUkYgVG9rZW4gYXMgYSBjb21tb24gaGVhZGVyIHdpdGggQXhpb3Mgc28gdGhhdFxyXG4vLyAgKiBhbGwgb3V0Z29pbmcgSFRUUCByZXF1ZXN0cyBhdXRvbWF0aWNhbGx5IGhhdmUgaXQgYXR0YWNoZWQuIFRoaXMgaXMganVzdFxyXG4vLyAgKiBhIHNpbXBsZSBjb252ZW5pZW5jZSBzbyB3ZSBkb24ndCBoYXZlIHRvIGF0dGFjaCBldmVyeSB0b2tlbiBtYW51YWxseS5cclxuLy8gICovXHJcbi8vXHJcbi8vIGNvbnN0IHRva2VuID0gZG9jdW1lbnQuaGVhZC5xdWVyeVNlbGVjdG9yKCdtZXRhW25hbWU9XCJjc3JmLXRva2VuXCJdJyk7XHJcbi8vXHJcbi8vIGlmICh0b2tlbikge1xyXG4vLyAgICAgd2luZG93LmF4aW9zLmRlZmF1bHRzLmhlYWRlcnMuY29tbW9uWydYLUNTUkYtVE9LRU4nXSA9IHRva2VuLmNvbnRlbnQ7XHJcbi8vIH0gZWxzZSB7XHJcbi8vICAgICBjb25zb2xlLmVycm9yKCdDU1JGIHRva2VuIG5vdCBmb3VuZDogaHR0cHM6Ly9sYXJhdmVsLmNvbS9kb2NzL2NzcmYjY3NyZi14LWNzcmYtdG9rZW4nKTtcclxuLy8gfVxyXG5cclxuLyoqXHJcbiAqIEVjaG8gZXhwb3NlcyBhbiBleHByZXNzaXZlIEFQSSBmb3Igc3Vic2NyaWJpbmcgdG8gY2hhbm5lbHMgYW5kIGxpc3RlbmluZ1xyXG4gKiBmb3IgZXZlbnRzIHRoYXQgYXJlIGJyb2FkY2FzdCBieSBMYXJhdmVsLiBFY2hvIGFuZCBldmVudCBicm9hZGNhc3RpbmdcclxuICogYWxsb3dzIHlvdXIgdGVhbSB0byBlYXNpbHkgYnVpbGQgcm9idXN0IHJlYWwtdGltZSB3ZWIgYXBwbGljYXRpb25zLlxyXG4gKi9cclxuXHJcbi8vIGltcG9ydCBFY2hvIGZyb20gJ2xhcmF2ZWwtZWNobydcclxuXHJcbi8vIHdpbmRvdy5QdXNoZXIgPSByZXF1aXJlKCdwdXNoZXItanMnKTtcclxuXHJcbi8vIHdpbmRvdy5FY2hvID0gbmV3IEVjaG8oe1xyXG4vLyAgICAgYnJvYWRjYXN0ZXI6ICdwdXNoZXInLFxyXG4vLyAgICAga2V5OiBwcm9jZXNzLmVudi5NSVhfUFVTSEVSX0FQUF9LRVlcclxuLy8gICAgIGNsdXN0ZXI6IHByb2Nlc3MuZW52Lk1JWF9QVVNIRVJfQVBQX0NMVVNURVIsXHJcbi8vICAgICBlbmNyeXB0ZWQ6IHRydWVcclxuLy8gfSk7XHJcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL3Jlc291cmNlcy9qcy9ib290c3RyYXAuanMiLCJpbXBvcnQgJy4vYm9vdHN0cmFwJztcclxuXHJcbi8qKlxyXG4gKiBDbGlwYm9hcmRKU1xyXG4gKiBodHRwczovL2dpdGh1Yi5jb20vemVub3JvY2hhL2NsaXBib2FyZC5qc1xyXG4gKi9cclxudmFyIENsaXBib2FyZEpTID0gcmVxdWlyZSgnY2xpcGJvYXJkJyk7XHJcbm5ldyBDbGlwYm9hcmRKUygnLmJ0bi1jb3B5Jyk7XHJcblxyXG5cclxuLyoqXHJcbiAqIFNvY2lhbCBTaGFyZVxyXG4gKi9cclxuJChmdW5jdGlvbigpIHtcclxuICAgIGlmICgkKCcuc29jaWFscy1zaGFyZScpLmxlbmd0aCkge1xyXG4gICAgICAgICQoJy5zb2NpYWwtc2hhcmUnKS5vbignbW91c2VsZWF2ZScsIGZ1bmN0aW9uICgpIHtcclxuICAgICAgICAgICAgJCh0aGlzKS5maW5kKCcuc29jaWFsLXNoYXJlLW1lbnUnKS5oaWRlKCk7XHJcbiAgICAgICAgfSk7XHJcbiAgICAgICAgLy8gQWN0aW9uIG9uIGNsaWNrIHNoYXJlIG5ldHdvcmtcclxuICAgICAgICAkKCcuc29jaWFsLXNoYXJlLW5ldHdvcmsnKS5vbignY2xpY2snLCBmdW5jdGlvbigpIHtcclxuICAgICAgICAgICAgdmFyIHNvY2lhbE5ldHdvcmsgPSAkKHRoaXMpLmRhdGEoJ3NvY2lhbC1uZXR3b3JrJyk7XHJcbiAgICAgICAgICAgIHZhciBzaGFyZVVybCA9ICQodGhpcykucGFyZW50KCkuZGF0YSgnc2hhcmUtdXJsJyk7XHJcbiAgICAgICAgICAgIHBvcHVwU29jaWFsU2hhcmUoc29jaWFsTmV0d29yaywgc2hhcmVVcmwpO1xyXG4gICAgICAgIH0pO1xyXG4gICAgfVxyXG5cclxuICAgIGZ1bmN0aW9uIHBvcHVwU29jaWFsU2hhcmUoc29jaWFsTmV0d29yaywgc2hhcmVVcmwpIHtcclxuICAgICAgICBzd2l0Y2ggKHNvY2lhbE5ldHdvcmspIHtcclxuICAgICAgICAgICAgY2FzZSAnZmFjZWJvb2snOlxyXG4gICAgICAgICAgICAgICAgd2luZG93Lm9wZW4oXCJodHRwczovL3d3dy5mYWNlYm9vay5jb20vc2hhcmVyL3NoYXJlci5waHA/dT1cIiArIHNoYXJlVXJsLCBcInBvcFwiLCBcIndpZHRoPTYwMCwgaGVpZ2h0PTQwMCwgc2Nyb2xsYmFycz1ub1wiKTtcclxuICAgICAgICAgICAgICAgIHJldHVybiBmYWxzZTtcclxuICAgICAgICAgICAgICAgIGJyZWFrO1xyXG4gICAgICAgICAgICBjYXNlICdnb29nbGUnOlxyXG4gICAgICAgICAgICAgICAgd2luZG93Lm9wZW4oXCJodHRwczovL3BsdXMuZ29vZ2xlLmNvbS9zaGFyZT91cmw9XCIgKyBzaGFyZVVybCwgXCJwb3BcIiwgXCJ3aWR0aD02MDAsIGhlaWdodD00MDAsIHNjcm9sbGJhcnM9bm9cIik7XHJcbiAgICAgICAgICAgICAgICByZXR1cm4gZmFsc2U7XHJcbiAgICAgICAgICAgICAgICBicmVhaztcclxuICAgICAgICAgICAgY2FzZSAndHdpdHRlcic6XHJcbiAgICAgICAgICAgICAgICB3aW5kb3cub3BlbihcImh0dHA6Ly90d2l0dGVyLmNvbS9pbnRlbnQvdHdlZXQ/dXJsPVwiICsgc2hhcmVVcmwgLCBcInBvcFwiLCBcIndpZHRoPTYwMCwgaGVpZ2h0PTQwMCwgc2Nyb2xsYmFycz1ub1wiKTtcclxuICAgICAgICAgICAgICAgIHJldHVybiBmYWxzZTtcclxuICAgICAgICAgICAgICAgIGJyZWFrO1xyXG4gICAgICAgICAgICBkZWZhdWx0OlxyXG4gICAgICAgICAgICAgICAgYnJlYWs7XHJcbiAgICAgICAgfVxyXG4gICAgfVxyXG59KTtcclxuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vcmVzb3VyY2VzL2pzL2Zyb250ZW5kLmpzIiwiLy8gcmVtb3ZlZCBieSBleHRyYWN0LXRleHQtd2VicGFjay1wbHVnaW5cblxuXG4vLy8vLy8vLy8vLy8vLy8vLy9cbi8vIFdFQlBBQ0sgRk9PVEVSXG4vLyAuL3Jlc291cmNlcy9zYXNzL2JhY2tlbmQvYmFja2VuZC5zY3NzXG4vLyBtb2R1bGUgaWQgPSAuL3Jlc291cmNlcy9zYXNzL2JhY2tlbmQvYmFja2VuZC5zY3NzXG4vLyBtb2R1bGUgY2h1bmtzID0gMSIsIi8vIHJlbW92ZWQgYnkgZXh0cmFjdC10ZXh0LXdlYnBhY2stcGx1Z2luXG5cblxuLy8vLy8vLy8vLy8vLy8vLy8vXG4vLyBXRUJQQUNLIEZPT1RFUlxuLy8gLi9yZXNvdXJjZXMvc2Fzcy9mcm9udGVuZC9mcm9udGVuZC5zY3NzXG4vLyBtb2R1bGUgaWQgPSAuL3Jlc291cmNlcy9zYXNzL2Zyb250ZW5kL2Zyb250ZW5kLnNjc3Ncbi8vIG1vZHVsZSBjaHVua3MgPSAxIl0sInNvdXJjZVJvb3QiOiIifQ==