(function ($, Drupal, drupalSettings) {
    $(document).ready(function () {
        $("img.lazy").lazyload({
            effect : "fadeIn"
        });
    });
})(jQuery, Drupal, drupalSettings);