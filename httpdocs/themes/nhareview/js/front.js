(function ($, Drupal, drupalSettings) {
    $(document).ready(function () {
        var related = new Swiper("#product_new", {
            slidesPerView: 1.3,
            spaceBetween: 10,
            freeMode: true,
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
                    slidesPerView: 2.5,
                },
                1200: {
                    slidesPerView: 3,
                },
                1380: {
                    slidesPerView: 3,
                },
            }
        });
    });
})(jQuery, Drupal, drupalSettings);

