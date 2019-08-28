$(document).ready(function(){
    //Kosongkan img yang berada di dalam div #bg-slideshow
    var $slide = $("#bg-slideshow");
    $slide.empty();    
    
    // Buat array yang berisi letak gambar - gambar yang akan dijadikan 
    // slideshow. Lalu append gambar gambar tersebut di dalam div #bg-slideshow
    var bg_img = new Array('themes/images/carousel/dunat1.jpg','themes/images/carousel/tambur1.jpg','themes/images/carousel/reptil1.jpg','themes/images/carousel/musin1.jpg','themes/images/carousel/muspu1.jpg');


    for (var i=0; i < bg_img.length; i++) {
        imgTemplate = "<img src='" + bg_img[i] + "' alt='gambar' >";
        $slide.append(imgTemplate);
    }
    
    //Slideshow
    $("#bg-slideshow img:gt(0)").hide();
    setInterval(function(){
        $visible = $("#bg-slideshow img:visible");
        $visible.fadeOut(100);
        if( $visible.next('img').length == 0 ) {
            $("#bg-slideshow img:first").fadeIn(1000);
        } else {
            $visible.next('img').fadeIn(1000);
        }
    },5000);
    
});