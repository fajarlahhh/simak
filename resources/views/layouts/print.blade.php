<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
	@include('includes.head')
</head>
@php
	$bodyClass = (!empty($boxedLayout)) ? 'boxed-layout ' : '';
	$bodyClass .= (!empty($paceTop)) ? 'pace-top ' : '';
	$bodyClass .= (!empty($bodyExtraClass)) ? $bodyExtraClass . ' ' : '';
@endphp
<style type="text/css">
@page {
	header: page-header;
	footer: page-footer;
}
.numbering{
    text-align: right;
}
@page {
    size: auto;
}
body {
    font-size: 11px;
}
</style>
<body class="bg-white ">
    <div class="text-center">
        <img src="/assets/img/logo/favicon.png" class="width-100" alt=""><hr>
        {!! $judul !!}
    </div>

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
