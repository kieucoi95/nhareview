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
        
        $('.open_menu_mobile').click(function (e) { 
            e.preventDefault();
            $('.main_menu').addClass('active');
            $('.bg_overlay').addClass('active');
        });

        $('.bg_overlay').click(function (e) { 
            e.preventDefault();
            $('.main_menu').removeClass('active');
            $('.bg_overlay').removeClass('active');
        });
    });
})(jQuery, Drupal, drupalSettings);

