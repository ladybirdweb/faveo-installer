<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{--    <title>@if (trim($__env->yieldContent('template_title')))@yield('template_title') | @endif {{ trans('installer_messages.title') }}</title>--}}
    {{--    <link rel="icon" type="image/png" href="{{ asset('installer/img/favicon/favicon-16x16.png') }}" sizes="16x16"/>--}}
    {{--    <link rel="icon" type="image/png" href="{{ asset('installer/img/favicon/favicon-32x32.png') }}" sizes="32x32"/>--}}
    {{--    <link rel="icon" type="image/png" href="{{ asset('installer/img/favicon/favicon-96x96.png') }}" sizes="96x96"/>--}}
    {{--    <link href="{{ asset('installer/css/style.min.css') }}" rel="stylesheet"/>--}}
    @yield('style')

</head>
<body>
<div class="master">

    @yield('container')

</div>
@yield('scripts')
<script type="text/javascript">
    var x = document.getElementById('error_alert');
    var y = document.getElementById('close_alert');
    y.onclick = function () {
        x.style.display = "none";
    };
</script>
</body>
</html>
