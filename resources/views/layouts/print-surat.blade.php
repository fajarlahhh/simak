<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8" />
    <title>{{ config("app.name") }} @yield('title')</title>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
    <link rel="icon" href="/assets/img/logo/favicon.png" type="image/gif">
    <meta content="{{ config("app.name")." ".env('APP_COMPANY') }}" name="description" />
    <meta content="Andi Fajar Nugraha" name="author" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<style>
    p, table {
        margin-top: 0px;
        margin-bottom: 0px;
    }
    hr {
        border: 1px solid;
    }
</style>
<body class="bg-white" style="font-family: 'Times New Roman', Times, serif;">
    {!! $data->kop_isi !!}
    @include($halaman)
    <script src="/assets/js/bundle.js"></script>
    <script>
        $(document).ready(function(){
            window.print();
        });
    </script>
</body>
</html>
