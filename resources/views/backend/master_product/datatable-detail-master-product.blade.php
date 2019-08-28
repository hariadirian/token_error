<div class="row" style="text-align:left;color:white;">
    <div class="col-md-12">
        <div class="panel-body bg-bright">
            @foreach($products as $product)
                <h3 style="margin-top:10px;margin-left:20px;margin-bottom:0px;color:#535353">Detail Product</h3>
                <div class="col-md-12" style="padding-right: 20px;padding-left: 20px;">
                    <hr style="border-top:1px solid #babeff"></hr>
                </div>  
                <div class="body">
                    {!! Form::hidden('idem_ticket_uu', $product->m_product_uu, ['id' => 'idem_ticket_uu', 'readonly' => 'readonly']) !!}
                    <div class="input-group">
                        <div class="col-md-10">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" class="form-control form-bright" value="{{ $product->name }}" readonly> 
                                    <label class="form-label" style="color:#1e5f98 !important">Nama Product</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" class="form-control form-bright" value="{{ substr($product->isactive, 0, 10) }}" readonly>
                                    <label class="form-label" style="color:#1e5f98 !important">State</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="input-group">
                        <div class="col-md-3">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" class="form-control form-bright" value="{{ $product->product_category }}" readonly>
                                    <label class="form-label" style="color:#1e5f98 !important">Type Customer</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" class="form-control form-bright" value="{{ $product->weekdays_value }}" readonly>
                                    <label class="form-label" style="color:#1e5f98 !important">Weekdays</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" class="form-control form-bright" value="{{ $product->holiday_value }}" readonly>
                                    <label class="form-label" style="color:#1e5f98 !important">Holiday</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" class="form-control form-bright" value="{{ $product->pekan_value }}" readonly>
                                    <label class="form-label" style="color:#1e5f98 !important">Week</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" class="form-control form-bright" value="{{ $product->created }}" readonly>
                                    <label class="form-label" style="color:#1e5f98 !important">Created at</label>
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
                            @if($product->toTicketImgHd)
                                @if($product->toTicketImgHd->toTicketImgDt)
                                    <table class="table table-hover" style="color:#686868;">
                                        <tbody>
                                            @foreach($product->toTicketImgHd->toTicketImgDt as $key => $ticketDT)
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
                            {!! Form::open(['route' => 'store.master_product.insert', 'id' => 'frmFileUpload', 'class' => 'dropzone', 'style' => 'text-align:center', 'enctype' => 'multipart/form-data']) !!}
                            
                                <div class="dz-message">
                                    <div class="drag-icon-cph">
                                        <i class="material-icons">touch_app</i>
                                    </div>
                                    <h3>Drop images here or click to upload.</h3>
                                    <em>(This is images upload form for integrating images on product ERP with eticketing web. You can upload multiple images for each product.)</em>
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