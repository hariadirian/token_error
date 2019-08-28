$(function () {

    $('.datepicker').datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true,
        minDate: 'dateToday',
        onSelect: function(dateText, inst, cd, qty, type) {

            $(this).parent().find('.ticket-date').val(dateText);
            drawcart($(this).parent().parent().parent().parent().find('.cart-qty'));
            
        }
    });

    $('.cart-qty').on('change', function(){

        drawcart(this);
        
    }); 

    $('.remove-cart').on('click', function(){

        var cart = this;
        $('#cart-list-content').addClass('disabled-content');
        $.ajax({
            url: $('.mainurl').val() +'/cart/remove',
            type: "POST",
            data:{
                _token      : $('meta[name="csrf-token"]').attr('content'),
                product     : $(cart).parent().parent().find('.product-uu').val(),
                hdCd        : $('#hdCd').val(),
            },
            cache: false,
            success:function(data)
            {
                var data = $.parseJSON(data);
                if(data['state'] > 0 || data['state'] != '0'){
                    $(cart).parent().parent().remove();
                }else{
                    alert('Keranjang gagal/tidak dapat di hapus! Silahkan kontak cs kami untuk keterangan lebih lanjut.');
                }
                
                $('th.total').text(data['total_amount']);
                $('#cart-qty').text(data['qty_product']);
                $('#cart-sum').text(data['total_amount'] + ',-');
                $('#cart-list-content').removeClass('disabled-content');
            }
        });
        
    }); 
    
    function drawcart(cart) {

        $('#cart-list-content').addClass('disabled-content');
        $.ajax({
            url: $('.mainurl').val() +'/cart/list',
            type: "POST",
            data:{
                _token      : $('meta[name="csrf-token"]').attr('content'),
                date        : $(cart).parent().parent().find('input.ticket-date').val(),
                type        : $(cart).parent().parent().find('.type-ticket').text(),
                product     : $(cart).parent().parent().find('.product-uu').val(),
                hdCd        : $('#hdCd').val(),
                qty         : $(cart).val()
            },
            cache: false,
            success:function(data)
            {
                var data = $.parseJSON(data);
                if(data['state'] == true){
                    $('#cart-qty').text(data['total_qty']);
                    $('#cart-sum').text(data['total_amount'] + ',-');
                    $(cart).parent().next().find('strong').text(data['amount']);
                    $(cart).parent().parent().find('.product-price').text(data['product_price']);
                    $(cart).parent().parent().find('.ticket-date').val(data['date']);
                    $(cart).parent().parent().find('.product-date').val(data['date_text']);
                    $('th.total').text(data['total_amount']);
                    $(cart).val(data['qty']);
                    if(data['message']){
                        alert(data['message']);
                    }
                }else{
                    alert(data['message']);
                }
                $('#cart-list-content').removeClass('disabled-content');
            }
        });
    }
});