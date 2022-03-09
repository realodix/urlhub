import './bootstrap';

$(function() {
    /**
     * Copy short url to clipboard
     *
     * https://github.com/zenorocha/clipboard.js
     */
    var ClipboardJS = require('clipboard');

    new ClipboardJS('[data-clipboard-text]').on('success', function(e) {
        $(e.trigger)
            .attr('data-original-title','Copied!').tooltip("_fixTitle").tooltip("show")
            .attr("title", "Copy to clipboard").tooltip("_fixTitle");
    });
});
