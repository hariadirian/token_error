$(function () {
//a
    $('.card').on('click', '.show-upload', function () {
        if($('#form-upload-img').css('display') == 'none'){
            $('#form-upload-img').show();
            $('#img_type').removeClass('hidden');
            $('.show-upload').removeClass('btn-success');
            $('.show-upload').addClass('btn-danger');
            $('.show-upload').text('Hide Upload Images');
        }else{
            $('#form-upload-img').hide();
            $('#img_type').addClass('hidden');
            $('.show-upload').removeClass('btn-danger');
            $('.show-upload').addClass('btn-success');
            $('.show-upload').text('Show Upload Images');
        }
    });
    
    $('.card').on('click', '.js-sweetalert', function () {
        swal({
            title: "Promo Image",
            customClass: 'swal-wide',
            text: $('#body-'+this.id).html(),
            html: true
        });
        $('.sweet-alert').css('margin-left', -$('.sweet-alert').width() / 2);
    });

    $('.card').on('click', '.change_state', function () {
        var id = this.id;
        $.ajax({
            url: $('.mainurl').val() +'/store/master_promotion/change_state',
            type: "POST",
            data:{
                _token  : $('meta[name="csrf-token"]').attr('content'),
                cd      : this.id.replace('state-', '')
            },
            cache: false,
            success:function(data)
            {
                var data = $.parseJSON(data);
                if(data[0] == 'Y'){
                    $('#'+id).text('ACTIVE');
                }else{
                    $('#'+id).text('NOT ACTIVE');
                }
            }
        });
    });
});