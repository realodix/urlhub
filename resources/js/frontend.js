import './bootstrap';

/**
 * ClipboardJS
 * https://github.com/zenorocha/clipboard.js
 */
var ClipboardJS = require('clipboard');
new ClipboardJS('.btn-copy');


/**
 * Social Share
 */
$(function(){
    if($('.socials-share').length){
        $('.social-share').on('mouseleave', function () {
            $(this).find('.social-share-menu').hide();
        });
        // Action on click share network
        $('.social-share-network').on('click', function(){
           var socialNetwork = $(this).data('social-network');
           var shareUrl = $(this).parent().data('share-url');
            popupSocialShare(socialNetwork, shareUrl);
        });
    }

    function popupSocialShare(socialNetwork, shareUrl){
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
            case 'linkedin':
                window.open("https://www.linkedin.com/shareArticle?mini=true&url="+ shareUrl , "pop", "width=600, height=400, scrollbars=no");
                break;
            default:
                break;
        }
    }
});
