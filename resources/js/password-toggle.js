/**
 * Export this field so that it can be called in other files.
 */
export function initPasswordFields() {
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
    let $inputField = $(this).closest('.password-toggler-container').find('input');

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
