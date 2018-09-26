import './bootstrap';

/**
 * ClipboardJS
 * https://github.com/zenorocha/clipboard.js
 */
var ClipboardJS = require('clipboard');
new ClipboardJS('.btn-clipboard').on('success', function() {
    $('.btn-clipboard')
        .attr('data-original-title','Copied!').tooltip("_fixTitle").tooltip("show")
        .attr("title", "Copy to clipboard").tooltip("_fixTitle");
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
                dataType: "json"
            })
            .done(function(data) {
                if (data.errors) {
                    $("#link-availability-status")
                        .removeClass("text-success")
                        .addClass("text-danger");
                    document.getElementById("link-availability-status").innerHTML = data.errors[0];
                } else {
                    $("#link-availability-status")
                        .removeClass("text-danger")
                        .addClass("text-success");
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
