import './bootstrap';

/**
 * ClipboardJS
 * https://github.com/zenorocha/clipboard.js
 */
var ClipboardJS = require('clipboard');
new ClipboardJS('.btn-copy').on('success', function() {
    document.getElementById("url-copied").innerHTML = "Copied!";
});


/**
 * TypeWatch
 * https://github.com/dennyferra/TypeWatch
 */
import 'jquery.typewatch';
$(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var twOptions = {
        callback: function (value) {
            $.ajax({
                url: "/api/custom-link-avail-check",
                type: 'POST',
                data: {
                    'short_url_custom': $('#short_url_custom').val()
                },
                dataType: "html"
            }).done(function(msg) {
                if (msg == 'unavailable') {
                    $('#link-availability-status').html(' <span style="color:red"><i class="fa fa-ban"></i> Already in use</span>');
                } else if (msg == 'available') {
                    $('#link-availability-status').html('<span style="color:green"><i class="fa fa-check"></i> Available</span>');
                } else {
                    $('#link-availability-status').html(' <span style="color:red"><i class="fa fa-exclamation-circle"></i> An error occured. Try again </span>' + msg);
                }
            }).fail(function(jqXHR, textStatus) {
                $('#link-availability-status').html(' <span style="color:red"><i class="fa fa-exclamation-circle"></i> An error occured. Try again </span>' + textStatus);
            });

            $('#link-availability-status').html('<span><i class="fa fa-spinner"></i> Loading</span>');
        },
        wait: 500,
        highlight: true,
        allowSubmit: false,
        captureLength: 1
    };

    // Add TypeWatch to check when users type
    $('#short_url_custom').typeWatch(twOptions);
});


/**
 * Social Share
 */
$(function() {
    if ($('.socials-share').length) {
        $('.social-share').on('mouseleave', function () {
            $(this).find('.social-share-menu').hide();
        });
        // Action on click share network
        $('.social-share-network').on('click', function() {
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
                window.open("http://twitter.com/intent/tweet?url=" + shareUrl , "pop", "width=600, height=400, scrollbars=no");
                return false;
                break;
            default:
                break;
        }
    }
});
