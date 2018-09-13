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
            case 'linkedin':
                window.open("https://www.linkedin.com/shareArticle?mini=true&url=" + shareUrl, "pop", "width=600, height=400, scrollbars=no");
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
//# sourceMappingURL=frontend.js.map