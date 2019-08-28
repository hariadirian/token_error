$(function () {
    $('.datepicker').datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true,
        minDate: 'dateToday',
        onSelect: function(dateText, inst, cd, qty, type) {
            
            var type = $('#type_ticket').val();
            var cd = $('#cd').val();
            var qty = $('#quantity').val();
            var promo_code = $('#promo_code').val();
            var type_customer = $('#type_customer').val();

            if(dateText && type && cd && qty){
                drawprice(dateText, cd, qty, type, promo_code, type_customer);
            }
        }
    });

    $( "#quantity" ).change(function() {

        var type =  $('#type_ticket').val();
        var cd = $('#cd').val();
        var qty = $('#quantity').val();
        var dateText = $('#ticket_date').val();
        var promo_code = $('#promo_code').val();
        var type_customer = $('#type_customer').val();

        if(dateText && type && cd && qty){
            drawprice(dateText, cd, qty, type, promo_code, type_customer);
        }
    });

    $( "#promo_code" ).change(function() {

        var type =  $('#type_ticket').val();
        var cd = $('#cd').val();
        var qty = $('#quantity').val();
        var dateText = $('#ticket_date').val();
        var promo_code = $('#promo_code').val();
        var type_customer = $('#type_customer').val();

        if(dateText && type && cd && qty){
            drawprice(dateText, cd, qty, type, promo_code, type_customer);
        }
    });

    function drawprice(date, cd, qty, type, promo_code, type_customer) {
        $.ajax({
            url: $('.mainurl').val() +'/ticket/check_price',
            type: "POST",
            data:{
                _token      : $('meta[name="csrf-token"]').attr('content'),
                date        : date,
                cd          : cd,
                qty         : qty,
                type        : type,
                promo_code  : promo_code,
                type_customer : type_customer
            },
            cache: false,
            success:function(data)
            {
                var data = $.parseJSON(data);
                if(data[0] == 'failed'){
                    alert(data[1]);
                }else{
                    $('#price-div').empty();
                    $('#price-div').append(data[0]);
                }
            }
        });
    }
});