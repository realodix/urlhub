import './../../vendor/power-components/livewire-powergrid/dist/powergrid';

/**
 * Copy short url to clipboard
 *
 * https://github.com/zenorocha/clipboard.js
 */
import clipboardJs from 'clipboard';

const target = document.getElementById('clipboard_shortlink');
const clipboard = new clipboardJs(target);

// Success action handler
clipboard.on('success', (e) => {
    const currentLabel = target.innerHTML;
    const copiedIcon = '<svg class="blade-icon text-emerald-500" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z"></path></svg>'

    // Exit label update when already in progress
    if (target.innerHTML === copiedIcon) {
        return;
    }

    // Update button label
    target.innerHTML = copiedIcon;

    // Revert button label after 3 seconds
    setTimeout(() => {
        target.innerHTML = currentLabel;
    }, 3000);
});
