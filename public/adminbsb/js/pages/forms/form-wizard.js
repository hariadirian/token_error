$(function () {
    //Horizontal form basic
    $('#wizard_horizontal').steps({
        headerTag: 'h2',
        bodyTag: 'section',
        transitionEffect: 'slideLeft',
        onInit: function (event, currentIndex) {
            setButtonWavesEffect(event);
        },
        onStepChanged: function (event, currentIndex, priorIndex) {
            setButtonWavesEffect(event);
        }
    });

    //Vertical form basic
    $('#wizard_vertical').steps({
        headerTag: 'h2',
        bodyTag: 'section',
        transitionEffect: 'slideLeft',
        stepsOrientation: 'vertical',
        onInit: function (event, currentIndex) {
            setButtonWavesEffect(event);
        },
        onStepChanged: function (event, currentIndex, priorIndex) {
            setButtonWavesEffect(event);
        }
    });

    //Advanced form with validation
    var form = $('#wizard_with_validation').show();
    form.steps({
        headerTag: 'h3',
        bodyTag: 'fieldset',
        transitionEffect: 'slideLeft',
        onInit: function (event, currentIndex) {
            $.AdminBSB.input.activate();

            //Set tab width
            var $tab = $(event.currentTarget).find('ul[role="tablist"] li');
            var tabCount = $tab.length;
            $tab.css('width', (100 / tabCount) + '%');

            //set button waves effect
            setButtonWavesEffect(event);
        },
        onStepChanging: function (event, currentIndex, newIndex) {
            if (currentIndex > newIndex) { return true; }

            if (currentIndex < newIndex) {
                form.find('.body:eq(' + newIndex + ') label.error').remove();
                form.find('.body:eq(' + newIndex + ') .error').removeClass('error');
            }

            form.validate().settings.ignore = ':disabled,:hidden';
            return form.valid();
        },
        onStepChanged: function (event, currentIndex, priorIndex) {
            setButtonWavesEffect(event);
        },
        onFinishing: function (event, currentIndex) {
            form.validate().settings.ignore = ':disabled';
            return form.valid();
        },
        onFinished: function (event, currentIndex) {

            if(!$('.modal-body').find('#disabled_state').length){
            $.ajax({
                url: $('.mainurl').val() +'/konsultasi/store_operasi',
                type: "POST",
                data:{
                    _token              : $('meta[name="csrf-token"]').attr('content'),
                    cd_bkd              : $('#queue').val(),

                    //STEP 1
                    jenis_operasi               : $('#jenis_operasi').val(),
                    agreement_check             : (typeof($('#agreement_check:checked').val()) != "undefined" && $('#agreement_check:checked').val() !== null)? 1 : 0,

                    //STEP 2
                    docter_code_of_operation    : $('#docter_code_of_operation').val(),
                    informers                   : $('#informers').val(),
                    recipient_of_information    : $('#recipient_of_information').val(),

                    docter_signature_JSON       : sig.signature('toJSON'),
                    docter_signature_JPEG      : sig.signature("toDataURL", 'image/jpeg'),
                    patient_signature_JSON       : sig2.signature('toJSON'),
                    patient_signature_JPEG      : sig2.signature("toDataURL", 'image/jpeg'),

                    //STEP 3
                    lab                 : $( ".form-entry-lab" ).serializeArray(),
                    ket_lab             : $('#ket_lab').val(),
                    
                },
                cache: false,
                success:function(data)
                {
                    var data = $.parseJSON(data);
                    
                    if(data[0] == 0){
    
                        var tipe = 'danger';
                        var timer = 1000;
    
                    }else{
    
                        swal("Good job!", "Submitted!", "success");
                        
                        var tipe = 'success';
                        var timer = 4000;
    
                    }
    
                    $.notify({
                        message: data[1]
                    },
                        {
                            type:tipe,
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
            });
        }else{
            alert('Data tidak dapat dirubah! data sudah masuk ke dalam form laboratorium.');
        }
        }
    });

    form.validate({
        highlight: function (input) {
            $(input).parents('.form-line').addClass('error');
        },
        unhighlight: function (input) {
            $(input).parents('.form-line').removeClass('error');
        },
        errorPlacement: function (error, element) {
            $(element).parents('.form-group').append(error);
        },
        rules: {
            'confirm': {
                equalTo: '#password'
            }
        }
    });
});

function setButtonWavesEffect(event) {
    $(event.currentTarget).find('[role="menu"] li a').removeClass('waves-effect');
    $(event.currentTarget).find('[role="menu"] li:not(.disabled) a').addClass('waves-effect');
}