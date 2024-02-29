import './bootstrap';


/**
 * Copy short url to clipboard
 *
 * https://github.com/zenorocha/clipboard.js
 */
const clipboardJs = require('clipboard');

const target = document.getElementById('clipboard_shortlink');
const clipboard = new clipboardJs(target);

// Success action handler
clipboard.on('success', function (e) {
    const currentLabel = target.innerHTML;

    // Exit label update when already in progress
    if (target.innerHTML === 'Copied!') {
        return;
    }

    // Update button label
    target.innerHTML = 'Copied!';

    // Revert button label after 3 seconds
    setTimeout(function () {
        target.innerHTML = currentLabel;
    }, 3000)
});
