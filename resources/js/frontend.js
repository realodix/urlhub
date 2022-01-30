import './bootstrap';
import 'jquery.typewatch';

/**
 * Copy short url to clipboard
 *
 * https://github.com/zenorocha/clipboard.js
 */
var ClipboardJS = require('clipboard');

new ClipboardJS('.btn-clipboard').on('success', function() {
    $('.btn-clipboard')
        .attr('data-original-title','Copied!').tooltip("_fixTitle").tooltip("show")
        .attr("title", "Copy to clipboard").tooltip("_fixTitle");
});



/**
 * Custom link Avail Check
 *
 * https://github.com/dennyferra/TypeWatch
 */

var twOptions = {
    callback: function (value) {
        let linkStatus = $("#link-availability-status");

        axios.post('/validate-custom-key', {
            keyword: $('#custom_key').val()
        })
        .then(function (res) {
            if (res.data.errors) {
                linkStatus.removeClass("text-emerald-600").addClass("text-red-600");
                linkStatus.html(res.data.errors[0]);
            } else {
                linkStatus.removeClass("text-red-600").addClass("text-emerald-600");
                linkStatus.html(res.data.success);
            }
        })
        .catch(function (error) {
            linkStatus.html("Hmm. We're having trouble connecting to the server.");
        });

        linkStatus.html('<span><i class="fa fa-spinner"></i> Loading..</span>');

    },
    wait: 500,
    captureLength: 1,
    highlight: true,
    allowSubmit: false
};

// Add TypeWatch to check when users type
$('#custom_key').typeWatch(twOptions);
