(function ($, Drupal, drupalSettings) {
    $(document).ready(function () {
        var swiper = new Swiper(".related_posts", {
            slidesPerView: 1.3,
            spaceBetween: 15,
            lazy: true,
            navigation: {
                nextEl: ".slide_button .swiper-button-next",
                prevEl: ".slide_button .swiper-button-prev",
            },
            breakpoints: {
                // when window width is >= 480px
                480: {
                    slidesPerView: 2,
                    spaceBetween: 15
                },
                // when window width is >= 640px
                992: {
                    slidesPerView: 3,
                    spaceBetween: 15
                }
            }
        });
    });
})(jQuery, Drupal, drupalSettings);

