@if($errors->all())
    <div class="alert alert-danger">
        <strong>Insert failed! Please check the following error message for details.</strong>
        <ul style="padding-top">
            @foreach($errors->all() as $key => $data)
                <li>{{ $data }}</li>
            @endforeach 
        </ul>
    </div>
@endif
@if(session()->has('success'))
    <div class="alert alert-success">
        <strong>You successfully {{ session()->get('success') }} data! Make sure you've {{ session()->get('success') }}ed the data by checking it in your table.</strong>
    </div>
@endif
@if(session()->has('success_custom'))
    <div class="alert alert-success">
        <strong>{{ session()->get('success_custom') }}</strong>
    </div>
@endif
@if(session()->has('failed'))
    <div class="alert alert-danger">
        <strong>Insert failed! Please check the following error message for details.</strong>
        @if(session()->get('failed') == 'cd')
            <ul style="padding-top">
                <li>Data is not found</li>
            </ul>
        @else
            <ul style="padding-top">
                <li>Data cannot insert into database (error database)</li>
            </ul>
        @endif
    </div>
@endif