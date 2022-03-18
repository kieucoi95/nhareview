(function ($) {
    $(document).ready(function () {

        var gallery_thumb = new Swiper(".gallery_thumb", {
            spaceBetween: 10,
            slidesPerView: 4,
            freeMode: true,
            watchSlidesProgress: true,
        });

        var gallery = new Swiper(".gallery", {
            spaceBetween: 10,
            thumbs: {
                swiper: gallery_thumb,
            },
        });

        $(".rating_product").each(function( index ) {
            $(this).rateYo({
                rating: $(this).attr('data-rating'),
                readOnly: true,
                starWidth: "14px",
                ratedFill: "#08C299",
                starSvg: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"/></svg>'
            });
        });

        var related = new Swiper("#related", {
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
})(jQuery);
