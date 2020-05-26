@if(config('sweetalert.animation.enable'))
    <link rel="stylesheet" href="{{ config('sweetalert.animatecss') }}">
@endif
<script src="{{ $cdn?? asset('vendor/sweetalert/sweetalert.all.js')  }}"></script>
@if (Session::has('alert.config'))
<script>
    Swal.fire({!! Session::pull('alert.config') !!});
</script>
@endif
<script>
    const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000
                });
</script>
