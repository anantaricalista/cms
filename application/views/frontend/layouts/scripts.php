<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!-- ===========================
     CORE JAVASCRIPT
=========================== -->

<!-- jQuery -->
<script src="<?php echo base_url('assets/frontend/js/jquery-3.7.1.min.js'); ?>"></script>

<!-- Bootstrap -->
<script src="<?php echo base_url('assets/frontend/js/bootstrap.bundle.min.js'); ?>"></script>

<!-- AOS -->
<script src="<?php echo base_url('assets/frontend/js/aos.js'); ?>"></script>

<!-- Swiper -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/14.0.5/swiper-bundle.min.js" integrity="sha512-DMunOu/iiGaR/Jq+asCspQ+ip9ASQi+bUl2Ct0ByLOoIOGrl9hNG/+aBZGKZ38jjOP/YCckpogtuhq3L0GLb5g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<!-- Magnific Popup -->
<script src="<?php echo base_url('assets/frontend/js/jquery.magnific-popup.min.js'); ?>"></script>

<!-- Main Script -->
<script src="<?php echo base_url('assets/frontend/js/main.js'); ?>"></script>

<!-- ===========================
     INITIALIZATION
=========================== -->

<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | AOS
    |--------------------------------------------------------------------------
    */

    if (typeof AOS !== 'undefined') {

        AOS.init({

            duration: 800,
            easing: 'ease-in-out',
            once: true

        });

    }

    /*
    |--------------------------------------------------------------------------
    | Magnific Popup
    |--------------------------------------------------------------------------
    */

    if ($.fn.magnificPopup) {

        $('.vidpop').magnificPopup({

            type: 'iframe'

        });

    }

    /*
    |--------------------------------------------------------------------------
    | Swiper
    |--------------------------------------------------------------------------
    | Slider akan diaktifkan apabila terdapat elemen .swiper
    */

    if (typeof Swiper !== 'undefined' && document.querySelector('.swiper')) {

        new Swiper('.swiper', {

            loop: true,

            slidesPerView: 1,

            spaceBetween: 20,

            autoplay: {

                delay: 5000

            },

            pagination: {

                el: '.swiper-pagination',
                clickable: true

            },

            navigation: {

                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev'

            }

        });

    }

});

</script>