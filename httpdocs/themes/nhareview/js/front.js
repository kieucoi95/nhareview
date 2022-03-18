(function ($, Drupal, drupalSettings) {
    $(document).ready(function () {
        var related = new Swiper("#product_new", {
            slidesPerView: 1.7,
            spaceBetween: 10,
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            breakpoints: {
                480: {
                    slidesPerView: 2,
                },
                // when window width is >= 640px
                768: {
                    slidesPerView: 3,
                },
                1200: {
                    slidesPerView: 4,
                },
                1380: {
                    slidesPerView: 4,
                },
            }
        });
    });
})(jQuery, Drupal, drupalSettings);

