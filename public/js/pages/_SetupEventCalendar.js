$(function () {
    $.ajax({
        url: $('.mainurl').val() +'/api/get/setup_calendar',
        type: "GET",
        cache: false,
        dataType: 'JSON',
        success:function(event){
            drawcalendar(event);
        }
    });

    function drawcalendar(events) {
        $('#calendar').fullCalendar({
            header: {
              left: 'prev,next today',
              center: 'title',
              right: 'month,agendaWeek'
            },
            navLinks: true, // can click day/week names to navigate views
            selectable: true,
            selectHelper: true,
            select: function(start, end) {
                $.ajax({
                    url: $('.mainurl').val() +'/api/get/modal_setup_calendar/'+$.fullCalendar.formatDate(start, "Y-MM-DD")+'/'+$.fullCalendar.formatDate(end, "Y-MM-DD"),
                    type: "GET",
                    cache: false,
                    error: function() {
                        alert('Terjadi kesalahan saat mengambil event');
                    },
                    success:function(data)
                    {
                        var data = $.parseJSON(data);
                        $('.modal-content').empty();
                        $('.modal-content').append(data[0]);
                        $('#modal').modal();

                        if(!$('#modal > .modal-dialog').hasClass('modal-sm')){
                            $('#modal > .modal-dialog').addClass('modal-sm');
                        }
                        $('#modal').show();
                        $.AdminBSB.input.activate();
                        $.AdminBSB.select.activate();

                        $('#calendar').fullCalendar('unselect');
                    }
                });
            },
            editable: true,
            eventLimit: true, // allow "more" link when too many events
            events: events,
            eventRender: function(event, element) {
                element.append('<form id="delete-'+event.cd_ms_event_calendar+'" action="'+$('.mainurl').val() +'/store/setup_calendar/delete/'+'" method="POST" style="display: none;"><input type="hidden" name="_token" value="'+$('meta[name="csrf-token"]').attr("content")+'" /><input type="hidden" name="cd" value="'+event.cd_ms_event_calendar+'" /></form><span class="closeon js-sweetalert" data-type="confirm ">X</span>');
                element.find(".closeon").click(function() {
                    Deletetanggal(event.cd_ms_event_calendar);
                });
                element.attr('title', event.tip);
            }
          });
    }
    
    function Deletetanggal(eventcd) {
        swal({
            title: "Are you sure to delete this data?",
            text: "You will not be able to recover this data!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            closeOnConfirm: false
        }, function () {
            $('#delete-'+eventcd).submit();
        });

    }

});

