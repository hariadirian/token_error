<!DOCTYPE html>
<html>

    <head>

        @include('layouts.backend-layout.meta')

        <title>Reservasi Taman Mini Indonesia Indah</title>

        <link rel="icon" href="favicon.ico" type="image/x-icon">

        @include('layouts.backend-layout.header')

    </head>

    <body class="theme-indigo">
        <input type="hidden" id="mainurl-A543FD876YGhJY746392GUYXydsX" class="mainurl" value="{{URL::to('/')}}"/>

        @include('layouts.backend-layout.background-process')

        @include('layouts.backend-layout.navbar')

        @include('layouts.backend-layout.sidebar')

        @yield('content')
        
        @include('layouts.backend-layout.footer')
        <br />
        <br />
        <footer class="" style="position: relative;bottom: 0px;width:100%;background-color:white; padding:15px 15px 15px 15px; text-align:right;border-top:1px solid #eee;">
            <div class="legal">
                <div class="copyright">
                    &copy; 2019 <a href="javascript:void(0);">Taman Mini Indonesia Indah</a>.
                </div>
            </div>
        </footer>

        @include('_partial.modal')

    </body>

</html>