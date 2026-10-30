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
#team-container{
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
        <h1 class="display-4 fw-bold mb-3">Tim Kami</h1>
        <p class="lead mb-4">Kenali para profesional berpengalaman yang siap membantu kesuksesan bisnis Anda.</p>
    </div>
</section>

<section class="section-padding">
    <div class="container">
        <div id="team-container">
            <?php $this->load->view('frontend/team/list'); ?>
        </div>
    </div>
</section>

<script>
$(function(){
    $(document).on('click','.team-pagination a',function(e){
        e.preventDefault();
        const url=$(this).attr('href');
        if(!url) return;
        $('#team-container').css({opacity:.35});
        $('#team-container').load(
            url+' #team-container > *',
            function(){
                $('#team-container').animate({opacity:1},180);
                $('html, body').animate({scrollTop:$('.page-header').offset().top-80},300);
            }
        );
    });
});
</script>
