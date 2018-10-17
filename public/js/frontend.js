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


/**
 * Bootstrap tooltips
 * https://getbootstrap.com/docs/4.1/components/tooltips/
 */
__WEBPACK_IMPORTED_MODULE_0_jquery___default()("body").tooltip({
  selector: '[data-toggle="tooltip"]'
});

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
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_jquery_typewatch__ = __webpack_require__("./node_modules/jquery.typewatch/jquery.typewatch.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_jquery_typewatch___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_1_jquery_typewatch__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2_jssocials__ = __webpack_require__("./node_modules/jssocials/dist/jssocials.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2_jssocials___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_2_jssocials__);


/**
 * Copy short url to clipboard
 */
// https://github.com/zenorocha/clipboard.js
var ClipboardJS = __webpack_require__("./node_modules/clipboard/dist/clipboard.js");

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
                    'short_url_custom': $('#short_url_custom').val()
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
    };

    // Add TypeWatch to check when users type
    $('#short_url_custom').typeWatch(twOptions);
});

/**
 * Social Share
 * https://github.com/tabalinas/jssocials
 */

$("#jssocials").jsSocials({
    shareIn: "popup",
    showLabel: false,
    shares: [{ share: "email", logo: "fas fa-envelope" }, { share: "facebook", logo: "fab fa-facebook" }, { share: "twitter", logo: "fab fa-twitter" }]
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