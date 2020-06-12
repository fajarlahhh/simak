<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8" />
    <title>{{ config("app.name") }} @yield('title')</title>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
    <link rel="icon" href="{{ url('public/assets/img/logo/favicon.png') }}" type="image/gif">
    <meta content="{{ config("app.name")." ".env('APP_COMPANY') }}" name="description" />
    <meta content="Andi Fajar Nugraha" name="author" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<style type="text/css">
    p, table {
        margin-top: 0px;
        margin-bottom: 0px;
    }
    hr {
        border: 1px solid;
    }

    .v-top{
        vertical-align: text-top;
    }
</style>
<body class="bg-white" style="font-family: 'Times New Roman', Times, serif;">

    {!! $data->kop_isi !!}
    @include($halaman)

    <htmlpagefooter name="page-footer">
        <table width="100%" style="border: 0px">
            <tr>
                <td style="border: 0px">
                    <small>
                    {!! $judul !!}</small>
                </td>
                <td class="text-right" style="border: 0px">
                    {PAGENO}
                </td>
            </tr>
        </table>
    </htmlpagefooter>
</body>
</html>
