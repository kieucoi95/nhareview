(function ($, Drupal, drupalSettings) {
    $(document).ready(function () {
        $("img.lazy").lazyload({
            effect : "fadeIn",
            threshold : 500
        });

        setTimeout(() => {
            if ($('.toolbar-tray-open').length) {
                var stickySidebar = new StickySidebar('.sidebar_left .sidebar_content', {
                    topSpacing: 160,
                    bottomSpacing: 20,
                    containerSelector: '.sidebar_left',
                });
            } else {
                var stickySidebar = new StickySidebar('.sidebar_left .sidebar_content', {
                    topSpacing: 80,
                    containerSelector: '.sidebar_left',
                });
            }
        }, 500);
        
        
    });
})(jQuery, Drupal, drupalSettings);

