
var table;

$(function () {
    $('.js-basic-example').DataTable({
        responsive: true
    });
    
    var selected = [];
	$.tables_js_exportable_ajax = function(id){

        var x = 0;
        $('.js-exportable-ajax').each(function () {
            if(x == 0){
                if(typeof id === "undefined"){
                    var $table = $(this);
                }else{
                    var $table = $('#'+id);
                    x = x + 1;
                }

                table = $table.DataTable({
                    dom: 'Bflrtip',
                    select: {
                        style:    'multiple',
                        selector: 'tr>td:nth-child(3)'
                    },
                    bPaginate: true,
                    processing: true,
                    serverSide: true,
                    sPaginationType: 'full_numbers',
                    ajax: {
                        "url" : "/api/get/"+$(this).attr('data-source-tb'),
                        "headers": {
                            'X-CSRF-TOKEN'  : $('meta[name="csrf-token"]').attr('content')
                        },
                        "type": "POST",
                        "data": {
                            source : $(this).attr('data-source-tb'),
                            additional_filter : $('#'+this.id+'-search').find('.additional-filter').serializeArray()
                        },
                    },
                    responsive: true,
                    lengthMenu: [[5, 10, 50, -1], [5, 10, 50, "All"]],
                    oLanguage: {
                        "loadingRecords": "&nbsp;",
                        "processing": "Loading...",
                        "sLengthMenu": "&nbsp; _MENU_ &nbsp;",
                        "sSearch": "",
                        "sSearchPlaceholder": "Search records"
                    },
                    buttons: [
                        'copy', 'csv', 'excel', 'pdf', 'print'
                    ],
                    initComplete:
                        function( settings, json){
                            jQuery.each( json, function( i, val ) {
                                if(i != 'aaData' && i != 'iTotalRecords' && i != 'iTotalDisplayRecords'){
                                    $( "#" + i ).empty();
                                    $( "#" + i ).append( val );
                                }
                            });
                    },
                    drawCallback: function( settings ) {

                        $('#container-detail').empty();
                        $.AdminBSB.select.activate();
                        
                        $('[data-toggle="tooltip"]').tooltip({
                            container: 'body'
                        });
                        
                        if($(this).attr('selected-row') == 1){

                            $("#"+$(this).attr('id')+" td").css("border-top", "2px solid #fff");
                            $("#"+$(this).attr('id')+" tbody").css("cursor", "pointer");

                        }

                        $('td:nth-child(1)').addClass("first-td-datatable");
                        $('td').addClass("default-td-datatable");
                        $('th').addClass("default-th-datatable");

                    },
                    "createdRow": function ( row, data, index ) {
                        //
                    },
                    columnDefs: [
                    {
                        "targets": [ 0 ],
                        "visible": false,
                        "searchable": false
                    }]
                });
            }
        });
	}

    $("#current").change(function () {
   
        $('.js-exportable-ajax').DataTable().clear().destroy();
        $.tables_js_exportable_ajax();

    });

    $(".additional-filter").change(function () {
   
        $('.js-exportable-ajax').DataTable().clear().destroy();
        $.tables_js_exportable_ajax();

    });
    $("#refresh").click(function () {

        $('.js-exportable-ajax').DataTable().clear().destroy();
        $.tables_js_exportable_ajax();

    });

    $.tables_js_exportable_ajax();
/* ------------------ SELECTED TABLE SHOW DATA CONTAINER --------------------*/

    $.data_detail_container = function(row, source){
        return $.ajax({
            url: $('.mainurl').val() +'/api/get/detail/'+source,
            type: "POST",
            headers: {
                'X-CSRF-TOKEN'  : $('meta[name="csrf-token"]').attr('content')
            },
            data:{
                uniqcd                 : row[0],
            },
            cache: false,
            success:function(data)
            {
                var data = $.parseJSON(data);
                $('.custom-detail-row').empty();
                $('.custom-detail-row').append(data[0]);
                $('.custom-detail-row').css('visibility', 'hidden');
                setTimeout(function(){ 
                    $('.custom-detail-row').css('visibility', 'visible').hide().show("slide", { direction: "left" }, 1000);
                }, 900);
            }
        });   
    }

    $.detail_container = function(){
        return '<div class="custom-detail-row" style="height:100%;padding:10px"></div>'
    }

    $.close_details = function(){
        table.rows().eq(0).each( function ( idx ) {
            var row = table.row( idx );
            if ( row.child.isShown() ) {
                $('.custom-detail-row').hide("slide", { "direction": "left" }, function() {
                    row.child.hide("slow");
                  });
     
                // Remove from the 'open' array
                detailRows.splice( idx, 1 );
            }
        } );
    }
    if($('.js-exportable-ajax').attr('selected-row') == 1){
        var detailRows = [];
        $('.js-exportable-ajax tbody').on('click', 'tr', function (e) {
            var tr = $(this).closest('tr');
            var row = table.row( tr );
            var idx = $.inArray( tr.attr('id'), detailRows );

            if(typeof tr.attr('role') !== 'undefined'){
                var source = $(this).closest('table').attr('data-source-tb');
                if ( row.child.isShown() ) {
                    $('.custom-detail-row').hide("slide", { "direction": "left" }, 1000, function() {
                        row.child.hide(1000);
                    });
                    // Remove from the 'open' array
                    detailRows.splice( idx, 1 );
                } else {
                    $.close_details();
                    row.child( $.detail_container() ).show();
                    setTimeout(function(){ 
                        $.data_detail_container(row.data(), source);
                        $('.custom-detail-row').hide();
                        $('.custom-detail-row').show("slow");
                        $('.custom-detail-row').css("background-color","rgb(167, 197, 255)");
                    }, 300);
        
                    
                    // Add to the 'open' array
                    if ( idx === -1 ) {
                        detailRows.push( tr.attr('id') );
                    }
                    
                }
    
                if ( $(this).hasClass('selected') ) {
                    $(this).removeClass('selected');
                    $( "#container-detail" ).fadeOut( "slow", function() {
                            $('#container-detail').hide();
                            $('#container-detail').empty();
                    });
                }else {
    
                    $(this).DataTable.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected'); 

                }
                
            }

            
        });
    }
});
