$(function () {
    //Textare auto growth
    autosize($('textarea.auto-growth'));

    //Datetimepicker plugin
    $('.datetimepicker').bootstrapMaterialDatePicker({
        format: 'dddd DD MMMM YYYY - HH:mm',
        clearButton: true,
        weekStart: 1
    });

    $('.datepicker').bootstrapMaterialDatePicker({
        format: 'ddd, DD MMM YYYY',
        currentDate : new Date(), 
        clearButton: true,
        nowButton : true,
        weekStart: 1,
        time: false
    }).on('change', function(e, date){
        $('.js-exportable-ajax').DataTable().clear().destroy();
        $.tables_js_exportable_ajax();
    });

});