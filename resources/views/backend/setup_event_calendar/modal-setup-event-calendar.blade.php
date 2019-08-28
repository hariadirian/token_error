
<div class="modal-header" style="padding-bottom:25px">
    <h5 class="modal-title" style="font-size:25px" id="largeModalLabel">Form event Calendar</h5>
</div>

{!! Form::open(['route' => 'store.setup_calendar.insert', 'id' => 'fom']) !!}
    <div class="modal-body" >
            <div class="input-group">
                <span class="input-group-addon">
                    <div class="demo-google-material-icon">
                        <i class="material-icons bright-icons">add_to_queue</i>
                    </div>
                </span>

                {!! Form::hidden('event_startdate', $start, ['class' => 'form-control form-indigo', 'required' => 'required', 'readyonly' => 'readonly']) !!}
                {!! Form::hidden('event_enddate', $end, ['class' => 'form-control form-indigo', 'required' => 'required', 'readyonly' => 'readonly']) !!}
                <div class="form-group form-float  @if ($errors->has('event_name')) focused error @endif">
                    <div class="form-line">
                        {!! Form::text('event_name', null, ['class' => 'form-control form-indigo', 'required' => 'required', 'maxlength' => 128 ]) !!}
                        {!! Form::label('event_name', 'Event Name', ['class' => 'form-label']) !!}
                    </div>
                </div>
            </div>
            <div class="input-group input-group-indigo">
                <span class="input-group-addon">
                    <div class="demo-google-material-icon">
                        <i class="material-icons bright-icons">view_list</i>
                    </div>
                </span>
                
                <select name="event_type" class="form-control show-tick" data-live-search="true">
                    <option>--  Select Event Type --</option>
                    <option value="HOLIDAY">Holiday</option>
                    <option value="WEEK">Week (Pekan)</option>
                </select>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button type="submit" id="save" class="btn btn-link waves-effect save">SAVE CHANGES</button>
        <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
    </div>
{!! Form::close() !!}
