import './bootstrap';
import Alpine from 'alpinejs'

window.Alpine = Alpine

import './../../vendor/power-components/livewire-powergrid/dist/powergrid'

Alpine.start()

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
