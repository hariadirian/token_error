$(function () {

    $('#scan-result').on('click', '#redeem', function(){
        var total = parseInt(prompt("Isi jumlah tiket yang akan digunakan!"));
        if(isNaN(total)){
            alert('It is not a number. Please enter a number.');
        }else{
            if (total != null && total != "" & total != 0) {
                if (confirm("Anda yakin ingin me-redeem "+total+" ticket?")) {
                    $.ajax({
                        url: $('.mainurl').val() +'/ticket/scan/redeem',
                        type: "POST",
                        data:{
                            _token  : $('meta[name="csrf-token"]').attr('content'),
                            cd      : $('#cd').val(),
                            total   : total
                        },
                        cache: false,
                        success:function(data)
                    {
                        var data = $.parseJSON(data);
                            if(data['state']){   
                                $('#scanner-label').remove();
                                $('#scanner').remove();
                                $('#scan-result').empty();
                                $('#scan-result').append(data['content']);
                                $('#leftover-tickets-info').html($('#leftovers-val').val());
                                alert('Terima kasih. Sisa tiket :'+$('#leftovers-val').val());
                                if($('#leftovers-val').val() > 0){
                                //
                                }else{
                                    $('#redeem').remove();
                                }
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
                } 
            }
        }
    });

    $('#btn-qrcode').on('click', function(){
        var result = {
            code: 'GTD-' + $('#input-qrcode').val()
        };
        arg.resultFunction(result);
    });
    /*$('#qwe').on('click', function(){
        var cd = 'GTD-5D30B1658B5DE';
        alert('Qr Code ' + cd + ' has found!');
        $.ajax({
            url: $('.mainurl').val() +'/ticket/scan/process',
            type: "POST",
            data:{
                _token  : $('meta[name="csrf-token"]').attr('content'),
                cd      : cd
            },
            cache: false,
            success:function(data)
            {
            var data = $.parseJSON(data);
                if(data['state']){   
                    decoder.stop();
                    alert('Qr Code ' + cd + ' has successfully scanned!');
                    $('.form-scanner').empty();
                    $('#scanner-label').remove();
                    $('#scanner').remove();
                    $('#scan-result').empty();
                    $('#scan-result').append(data['content']);
                    $('#leftover-tickets-info').html($('#leftovers-val').val());
                    if($('#leftovers-val').val() > 0){
                        //
                    }else{
                        $('#redeem').remove();
                    }
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
*/

    var txt = "innerText" in HTMLElement.prototype ? "innerText" : "textContent";
    var arg = {
        resultFunction: function(result) {
            var cd = result.code;
            $.ajax({
                url: $('.mainurl').val() +'/ticket/scan/process',
                type: "POST",
                data:{
                    _token  : $('meta[name="csrf-token"]').attr('content'),
                    cd      : cd
                },
                cache: false,
                success:function(data)
                {
                var data = $.parseJSON(data);
                    if(data['state']){   
                        decoder.stop();
                        alert('Qr Code ' + cd + ' has successfully scanned!');
                        $('.form-scanner').empty();
                        $('#scanner-label').remove();
                        $('#scanner').remove();
                        $('#scan-result').empty();
                        $('#scan-result').append(data['content']);
                        $('#leftover-tickets-info').html($('#leftovers-val').val());
                        if($('#leftovers-val').val() > 0){
                            //
                        }else{
                            $('#redeem').remove();
                        }
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
        }
    };
    
    function Q(el) {
        if (typeof el === "string") {
            var els = document.querySelectorAll(el);
            return typeof els === "undefined" ? undefined : els.length > 1 ? els : els[0];
        }
        return el;
    }
    var txt = "innerText" in HTMLElement.prototype ? "innerText" : "textContent";
    var play = Q("#play"),
        stop = Q("#stop");
    
    var decoder = new WebCodeCamJS("canvas").init(arg);

    play.addEventListener("click", function() {
        if (!decoder.isInitialized()) {
            alert('Decoder is not initialized!');
        } else {
            decoder.play();
        }
    }, false);
    stop.addEventListener("click", function(event) {
        decoder.stop();
    }, false);
});