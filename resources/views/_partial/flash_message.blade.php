
@if (Session::has('flash_message'))
    <div class="alert alert-info {{ Session::has('penting') ? 'alert-important' : '' }}">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <i class="material-icons">info</i> {{ Session::get('flash_message') }}
    </div>
@endif


@if (Session::has('flash_message_daftar_pasien_baru'))
    <div class="alert alert-info {{ Session::has('penting') ? 'alert-important' : '' }}">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <i class="fa fa-info"> Notifikasi : </i> {{ Session::get('flash_message_daftar_pasien_baru') }}
    </div>
@endif

@if (Session::has('flash_message_cache'))
    <div class="alert alert-info {{ Session::has('penting') ? 'alert-important' : '' }}">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
       <strong> {{ Session::get('flash_message_cache') }} </strong>
    </div>
@endif