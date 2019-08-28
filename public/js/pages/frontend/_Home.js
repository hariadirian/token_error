$(function () {
        // HOME SLICK
        $('#home-slick').slick({
            autoplay: true,
            infinite: true,
            speed: 300,
            arrows: true,
        });

        // PRODUCTS SLICK
        $('.product-slick').slick({
            autoplay: true,
            infinite: true,
            speed: 300,
            dots: true,
            arrows: false,
            appendDots: '.product-slick-dots-1',
            responsive: [{
                breakpoint: 991,
                settings: {
                slidesToShow: 1,
                slidesToScroll: 5,
                }
            },
            {
                breakpoint: 480,
                settings: {
                dots: false,
                arrows: true,
                slidesToShow: 1,
                slidesToScroll: 5,
                }
            },
            ]
        });

        // PRODUCT DETAILS SLICK
        $('#product-main-view').slick({
            infinite: true,
            speed: 300,
            dots: false,
            arrows: true,
            fade: true,
            asNavFor: '#product-view',
        });

        $('#product-view').slick({
            slidesToShow: 3,
            slidesToScroll: 1,
            arrows: true,
            centerMode: true,
            focusOnSelect: true,
            asNavFor: '#product-main-view',
        });

        // PRODUCT ZOOM
        $('#product-main-view .product-view').zoom();

        // PRICE SLIDER
        var slider = document.getElementById('price-slider');
        if (slider) {
            noUiSlider.create(slider, {
            start: [1, 999],
            connect: true,
            tooltips: [true, true],
            format: {
                to: function(value) {
                return value.toFixed(2) + '$';
                },
                from: function(value) {
                return value
                }
            },
            range: {
                'min': 1,
                'max': 999
            }
            });
        }
    });