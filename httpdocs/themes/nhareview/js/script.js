(function ($, Drupal, drupalSettings) {

    $(document).ready(function () {
        $( ".product_teaser img" ).each(function( index ) {
            var pc = $(this).attr('data-pc'),
                mb = $(this).attr('data-mb');
            if ($(window).width() > 480) {
                $(this).attr('src', pc);
            } else {
                $(this).attr('src', mb);
            }
            
        });

        stickySidebarNr();
        imageLazyLoading();
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

    function stickySidebarNr () {
        if ($('.sidebar_left').length) {
            if ($('.toolbar-tray-open').length) {
                var stickySidebar = new StickySidebar('.sidebar_left .sidebar_content', {
                    topSpacing: 160,
                    containerSelector: '.sidebar_left',
                });
            } else {
                var stickySidebar = new StickySidebar('.sidebar_left .sidebar_content', {
                    topSpacing: 80,
                    containerSelector: '.sidebar_left',
                });
            }
        }
    }

    function imageLazyLoading() {
        $("img.lazy").lazyload({
            effect : "fadeIn",
            threshold : 500
        });
    }
})(jQuery, Drupal, drupalSettings);

