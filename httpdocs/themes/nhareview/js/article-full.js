(function ($, Drupal, drupalSettings) {
    $(document).ready(function () {
        var swiper = new Swiper(".related_posts", {
            slidesPerView: 3,
            spaceBetween: 30,
            lazy: true,
            navigation: {
                nextEl: ".slide_button .swiper-button-next",
                prevEl: ".slide_button .swiper-button-prev",
            },
        });
    });
})(jQuery, Drupal, drupalSettings);

