<div class="row" style="text-align:left;color:white;">
    <div class="col-md-12">
        <div class="panel-body bg-bright">
            @foreach($promotion as $promo)
                <h3 style="margin-top:10px;margin-left:20px;margin-bottom:0px;color:#535353" class="pull-left">Detail Promotion</h3>
                <div class="col-md-12" style="padding-right: 20px;padding-left: 20px;">
                    <hr style="border-top:1px solid #babeff"></hr>
                </div>  
                <div class="body">
                    {!! Form::hidden('idem_ticket_uu', $promo->m_promotion_uu, ['id' => 'idem_ticket_uu', 'readonly' => 'readonly']) !!}
                    <div class="input-group">
                        <div class="col-md-8">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" class="form-control form-bright" value="{{ $promo->description }}" readonly>
                                    <label class="form-label form-bright-label">Nama Promo</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" class="form-control form-bright" value="{{ substr($promo->startdate, 0, 10) }}" readonly>
                                    <label class="form-label form-bright-label">Promo from</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" class="form-control form-bright" value="{{ substr($promo->enddate, 0, 10) }}" readonly>
                                    <label class="form-label form-bright-label">Promo to</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="input-group">
                        <div class="col-md-2">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" class="form-control form-bright" value="{{ $promo->min_operand . ' ' . $promo->min_val }}" readonly>
                                    <label class="form-label form-bright-label">Min Ticket</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" class="form-control form-bright" value="{{ $promo->max_operand . ' ' . $promo->max_val }}" readonly>
                                    <label class="form-label form-bright-label">Max Ticket</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" class="form-control form-bright" value="{{ $promo->promotionusagelimit }}" readonly>
                                    <label class="form-label form-bright-label">Total Ticket</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" class="form-control form-bright" value="{{ $promo->product }}" readonly>
                                    <label class="form-label form-bright-label">Product</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="input-group">
                        <div class="col-md-3">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" class="form-control form-bright" value="{{ $promo->type_customer }}" readonly>
                                    <label class="form-label form-bright-label">Type Customer</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" class="form-control form-bright" value="{{ $promo->promo_weekdays_type == 'A'? number_format( $promo->promo_weekdays,0,',','.') : $promo->promo_weekdays.' %' }}" readonly>
                                    <label class="form-label form-bright-label">Promo Weekdays</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" class="form-control form-bright" value="{{ $promo->promo_holiday_type == 'A'? number_format( $promo->promo_holiday,0,',','.') : $promo->promo_holiday.' %' }}" readonly>
                                    <label class="form-label form-bright-label">Promo Holiday</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" class="form-control form-bright" value="{{ $promo->promo_pekan_type == 'A'? number_format( $promo->promo_pekan,0,',','.') : $promo->promo_pekan.' %' }}" readonly>
                                    <label class="form-label form-bright-label">Promo Week</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" class="form-control form-bright" value="{{ $promo->created }}" readonly>
                                    <label class="form-label form-bright-label">Created at</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pull-right" style="margin-bottom:20px">
                        <button class="btn btn-success show-upload" style="width:300px">Show Upload Images</button>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div id="form-upload-img" style="display:none">
                            @if($promo->toTicketImgHd)
                                @if($promo->toTicketImgHd->toTicketImgDt)
                                    <table class="table table-hover" style="color:#686868;">
                                        <tbody>
                                            @foreach($promo->toTicketImgHd->toTicketImgDt as $key => $ticketDT)
                                                <tr>
                                                    <td style="text-align:left;padding-left:30px" width="70%" class="js-sweetalert" id="{{ $ticketDT->cd_ms_ticket_img_dt }}">
                                                        {{  $ticketDT->filename }}
                                                    </td>
                                                    <td>
                                                        {{  $ticketDT->img_type }}
                                                    </td>
                                                    <td>
                                                        <a href="#" id="{{ 'state-'.$ticketDT->cd_ms_ticket_img_dt }}" class="change_state" onclick="event.preventDefault();">
                                                            {{  $ticketDT->is_active == 'Y'? 'ACTIVE' : 'NOT ACTIVE' }}
                                                        </a>
                                                    </td>
                                                </tr>

                                                <div class="hidden" id="body-{{ $ticketDT->cd_ms_ticket_img_dt }}">
                                                    <img src="{{ Storage::url($ticketDT->srcname) }}" title="{{ $ticketDT->filename }}"  style="height:300px;overflow:hidden;align:middle" />
                                                    <h3> {{ $ticketDT->filename }}</h3>
                                                    <h5> {{ $ticketDT->created_at }}</h5>
                                                </div>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            @else
                                <p style="text-align:center;color:black"><i>There are no images stored in the database for this component</i></p>
                            @endif

                            <hr></hr>
                            
                            {!! Form::select('img_type', [null => 'Select Image Type'] + ['HORIZONTAL' => 'Horizontal','BOX'=>'Box','SLIDER'=>'Slider'], null, ['class' => 'form-control', 'id' => 'img_type']) !!}
                            <a href="{{ URL::asset('download/template/carousell') }}" style="float:right;margin-right:20px" >Template Slider</a>
                            <a href="{{ URL::asset('download/template/ticket_box') }}" style="float:right;margin-right:20px" >Template Box Ticket</a>
                            <a href="{{ URL::asset('download/template/ticket_horizontal') }}" style="float:right;margin-right:20px" >Template Horizontal Ticket</a>
                            <br />
                            <br />
                            {!! Form::open(['route' => 'store.master_promotion.insert', 'id' => 'frmFileUpload', 'class' => 'dropzone', 'style' => 'text-align:center', 'enctype' => 'multipart/form-data']) !!}
                            
                                <div class="dz-message">
                                    <div class="drag-icon-cph">
                                        <i class="material-icons">touch_app</i>
                                    </div>
                                    <h3>Drop images here or click to upload.</h3>
                                    <em>(This is images upload form for integrating images on promotion ERP with eticketing web. You can upload multiple images for each promotion.)</em>
                                </div>
                                <div class="fallback">

                                    {!! Form::file('file') !!}

                                </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
<script>
    $.AdminBSB.input.activate();
    Dropzone.autoDiscover = false;
    Dropzone.options.frmFileUpload = {
        acceptedFiles: 'image/*',
        maxFilesize: 500
    };
    var $dropzone = $("#frmFileUpload").dropzone({
        init: function() {
            this.on("sending", function(file, xhr, formData){
                if ( $('#img_type :selected').val() ) {
                    formData.append("idem_ticket_uu",   $('#idem_ticket_uu').val());
                    formData.append("img_type",         $('#img_type :selected').val());
                }
            });
            this.on("complete", function(file) {
                if (!$('#img_type :selected').val()) {
                    this.removeFile(file);
                    alert('Choose image type first!');
                    return false;
                }
            });
        }
    });
</script>