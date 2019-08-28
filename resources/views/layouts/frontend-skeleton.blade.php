<!DOCTYPE html>
<html lang="en">
    <head>
        @include('layouts.frontend-layout.meta')

        <title>E-ticketing Taman Mini Indonesia Indah</title>

        <link rel="icon" href="favicon.ico" type="image/x-icon">

        @include('layouts.frontend-layout.header')
    </head>

	<body style="background-color:#f5f5f5">

        <input type="hidden" id="mainurl-A543FD876YGhJY746392GUYXydsX" class="mainurl" value="{{URL::to('/')}}"/>

            <header>

                @include('layouts.frontend-layout.top-header')

                @include('layouts.frontend-layout.body-header')

            </header>

            @include('layouts.frontend-layout.navbar')

            @yield('content')

            @include('layouts.frontend-layout.body-footer')

        @include('layouts.frontend-layout.footer')

    </body>
</html>
