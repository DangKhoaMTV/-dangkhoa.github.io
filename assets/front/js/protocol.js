$(function ($) {
    "use strict";

    jQuery(document).ready(function($) {

        //Intro Section
        new Swiper(".intro-image", {
            slidesPerView: "auto",
            spaceBetween: 0,
            breakpoints: {
                760: {
                    spaceBetween: 24,
                },
               550: {
                    spaceBetween: 12,
                },
            },
        });
        //Special Offer Section
        /* var special_right = new Swiper(".swiper-special-offer-right", {
            slidesPerView: 1,
            spaceBetween: 0,
        });
        var special_left = new Swiper(".swiper-special-offer-left", {
            slidesPerView: 1,
            spaceBetween: 36,
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            breakpoints: {
                760: {
                    spaceBetween: 24,
                },
               550: {
                    spaceBetween: 12,
                },
            },
        });
        special_left.controller.control = special_right;
        special_right.controller.control = special_left; */

        //Gallery Section
        new Swiper(".home-galery-column", {
            slidesPerView: "auto",
            spaceBetween: 0,
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
        });

        //Solution Section
        new Swiper(".swiper-container-solution", {
            slidesPerView: "auto",
            spaceBetween: 0,
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            rewind:true
        });

        //Video gallery Section
        new Swiper(".masonry-video-row", {
            slidesPerView: "auto",
            spaceBetween: 0,
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
        });

        //Products page shop item Section
        new Swiper(".products .shop-item .item-img", {
            slidesPerView: "auto",
            spaceBetween: 0,
            pagination: {
                el: '.swiper-pagination',
                type: 'bullets',
                clickable : true
            },
            // autoplay: {
            //     delay: 3000,
            //     disableOnInteraction: false,
            // },
        });
        // solution pagination
        new Swiper(".swiper-container-solution", {
            slidesPerView: "auto",
            spaceBetween: 0,
            pagination: {
                el: '.swiper-pagination',
                type: 'bullets',
                clickable : true
            },
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
        });

        // solution pagination mobile
        new Swiper(".swiper-container-solution", {
            slidesPerView: "auto",
            spaceBetween: 0,
            pagination: {
                el: '.swiper-pagination_m',
                type: 'bullets',
                clickable : true
            },
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
        });

        new Swiper(".products .product-attributes", {
            slidesPerView: "auto",
            spaceBetween: 0,
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
        });

        //Dinning page
        var dinning_left = new Swiper(".swiper-dinning-left", {
            slidesPerView: 1,
            spaceBetween: 36,
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            breakpoints: {
                760: {
                    spaceBetween: 24,
                },
                550: {
                    spaceBetween: 12,
                },
            },
        });


        //Dinning
        var dinning_items = new Swiper(".dinning-sliders .dinning-items", {
            slidesPerView: "auto",
            spaceBetween: 12,
            breakpoints: {
                1200: {
                    spaceBetween: 20,
                    loop: true,
                },
                760: {
                    spaceBetween: 20,
                },
                550: {
                    spaceBetween: 12,
                },
            },
        });

        //Dinning slider box
        new Swiper(".dinning-items-box", {
            slidesPerView: "auto",
            spaceBetween: 0,
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
        });

       //Service indoor
        new Swiper(".single-service-img-swiper", {
            slidesPerView: "auto",
            spaceBetween: 0,
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
        });

       //Service indoor
        new Swiper(".single-service-outdoor-swiper", {
            slidesPerView: "auto",
            spaceBetween: 0,
            effect: 'fade',
            speed: 1000,
            fadeEffect: {
                crossFade: true
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
        });

        //===== Magnific Popup
        $('.img-popup').magnificPopup({
            type: 'image',
            gallery: {
                enabled: true
            }
        });

        /*$('#google_map').magnificPopup({
            type: 'iframe',
            mainClass: 'mfp-fade',
            preloader: true,
        });*/

        // video popup in magnific popup
        $('.gallery-video-play-button').magnificPopup({
            type: 'iframe',
            mainClass: 'mfp-fade',
            preloader: true,
        });
    });


    $('.ajax-product-detail').on('click',function (e){
        e.preventDefault();
        let url = $(this).attr('href');
        let modal = $(this).data('target');
        $(modal+' .modal-body').load(url ,function (response, status, xhr){
            if (status == "error" ) {
                location.href = url;
            }
        });
    });


});

if ($('.js-background-video')[0]){
   // YouTube Player API for header BG video
    // Insert the <script> tag targeting the iframe API
    const tag = document.createElement('script');
    tag.src = "https://www.youtube.com/iframe_api";
    const firstScriptTag = document.getElementsByTagName('script')[0];
    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
    // Get the video ID passed to the data-video attribute
    const bgVideoID = document.querySelector('.js-background-video').getAttribute('data-video');

    // Set the player options
    const playerOptions = {
        // Autoplay + mute has to be activated (value = 1) if you want to autoplay it everywhere
        // Chrome/Safari/Mobile
        autoplay: 1,
        mute: 1,
        autohide: 1,
        modestbranding: 1,
        rel: 0,
        showinfo: 0,
        controls: 0,
        disablekb: 1,
        enablejsapi: 1,
        iv_load_policy: 3,
        loop: 1
    }

    // Get the video overlay, to mask it when the video is loaded
    const videoOverlay = document.querySelector('.js-video-overlay');

    // This function creates an <iframe> (and YouTube player)
    // after the API code downloads.
    let ytPlayer;

    function onYouTubeIframeAPIReady() {
        ytPlayer = new YT.Player('yt-player', {
            width: '1280',
            height: '720',
            videoId: bgVideoID,
            playerVars: playerOptions,
            events: {
                'onReady': onPlayerReady,
                'onStateChange': onPlayerStateChange
            },
        });
    }

    // The API will call this function when the video player is ready.
    function onPlayerReady(event) {
        event.target.playVideo();

        // Get the duration of the currently playing video
        const videoDuration = event.target.getDuration();

        // When the video is playing, compare the total duration
        // To the current passed time if it's below 2 and above 0,
        // Return to the first frame (0) of the video
        // This is needed to avoid the buffering at the end of the video
        // Which displays a black screen + the YouTube loader
        setInterval(function () {
            const videoCurrentTime = event.target.getCurrentTime();
            const timeDifference = videoDuration - videoCurrentTime;

            if (2 > timeDifference > 0) {
                event.target.seekTo(0);
            }
        }, 1000);
    }

    // When the player is ready and when the video starts playing
    // The state changes to PLAYING and we can remove our overlay
    // This is needed to mask the preloading
    function onPlayerStateChange(event) {
        if (event.data == YT.PlayerState.PLAYING) {
            videoOverlay.classList.add('header__video-overlay--fadeOut');
        }
        //console.log(event);
        if (event.data == YT.PlayerState.ENDED) {
           ytPlayer.seekTo(0);
        }
    }
    //End YT video
}

function init_product_swiper() {
    //Product detail page
    new Swiper(".product-item-row .product-item .item-img", {
        slidesPerView: "auto",
        spaceBetween: 0,
        pagination: {
            el: '.swiper-pagination',
            type: 'bullets',
            clickable : true
        },
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        autoplay: {
            delay: 3000,
            disableOnInteraction: false,
        },
    });
}