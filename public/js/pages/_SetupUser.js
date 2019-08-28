$(function () {

    $('#date_from').bootstrapMaterialDatePicker('destroy');
    $('#date_from').removeData();
    $('#date_from').val('');
    $('#date_from').bootstrapMaterialDatePicker({
        format: 'ddd, DD MMM YYYY',
        currentDate : new Date('2019-01-01'), 
        clearButton: true,
        nowButton : true,
        weekStart: 1,
        time: false
    }).on('change', function(e, date){
        $('.js-exportable-ajax').DataTable().clear().destroy();
        $.tables_js_exportable_ajax();
    });

    $('table').on('click', '.edit-form-detail', function () {
        $('.form-detail-edit').prop('disabled', false);
        $('.form-detail-edit').find('button').removeClass('disabled');
        $(this).hide();
        $('.save-form-detail').show();
    });
    
    $('table').on('click', '.save-form-detail', function () {
        
        $.ajax({
            url: $('.mainurl').val() +'/store/user/update',
            type: "POST",
            data:{
                _token  : $('meta[name="csrf-token"]').attr('content'),
                datas   : $('.form-detail-edit').serializeArray()
            },
            cache: false,
            success:function(data)
            {
                var data = $.parseJSON(data);
                if(data['state']){
                    $.notify({
                        message: data['message']
                    }, {
                        type:'success',
                        allow_dismiss: true,
                        newest_on_top: true,
                        z_index: 9999,
                        timer: 4000,
                        placement: {
                            from: 'top',
                            align: 'center'
                        },
                    });
                    $('.form-detail-edit').prop('disabled', true);
                    $('.form-detail-edit').find('button').addClass('disabled');
                    $('.edit-form-detail').show();
                    $('.save-form-detail').hide();
                }else{
                    $.notify({
                        message: data['message']
                    }, {
                        type:'danger',
                        allow_dismiss: true,
                        newest_on_top: true,
                        z_index: 9999,
                        timer: 4000,
                        placement: {
                            from: 'top',
                            align: 'center'
                        },
                    });
                }
            }
        });
    });

    $(document).on('click', '#add_new', function () {
        $.ajax({
            url: $('.mainurl').val() +'/store/user/create',
            type: "POST",
            data:{
                _token  : $('meta[name="csrf-token"]').attr('content')
            },
            cache: false,
            success:function(data)
            {
                var data = $.parseJSON(data);
                $('.modal-content').empty();
                $('.modal-content').append(data['content']);
                $.AdminBSB.input.activate();
                $.AdminBSB.select.activate();
            }
        });
    });
});