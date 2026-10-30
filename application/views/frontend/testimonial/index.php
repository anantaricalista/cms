<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
.page-header{
    padding-top:90px;
    padding-bottom:40px;
    text-align:center;
    background: linear-gradient(180deg, rgba(255,255,255,.04), transparent);
}
.page-header h1{
    font-size:2rem;
    font-weight:700;
    margin-bottom:14px;
    color:var(--tx);
}
.page-header .lead{
    max-width:760px;
    margin:auto;
    font-size:1rem;
    color:var(--tx2);
    line-height:1.8;
}
#testimonial-container{
    transition:opacity .25s ease;
}
@media (max-width:991px){
    .page-header{
        padding-top:90px;
        padding-bottom:30px;
    }
    .page-header h1{
        font-size:1.7rem;
    }
    .page-header .lead{
        font-size:.95rem;
    }
}
</style>

<section class="page-header">
    <div class="container">
        <h1 class="display-4 fw-bold mb-3">Testimonial</h1>
        <p class="lead mb-4">Dengarkan apa kata klien kami tentang layanan yang kami berikan.</p>
    </div>
</section>

<section class="section-padding">
    <div class="container">
        <div id="testimonial-container">
            <?php $this->load->view('frontend/testimonial/list'); ?>
        </div>
    </div>
</section>

<script>
$(function(){
    $(document).on('click','.testimonial-pagination a',function(e){
        e.preventDefault();
        const url=$(this).attr('href');
        if(!url) return;
        $('#testimonial-container').css({opacity:.35});
        $('#testimonial-container').load(
            url+' #testimonial-container > *',
            function(){
                $('#testimonial-container').animate({opacity:1},180);
                $('html, body').animate({scrollTop:$('.page-header').offset().top-80},300);
            }
        );
    });
});
</script>
