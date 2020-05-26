@if (session()->get('error'))
<div class="alert alert-danger">
    <ul>
        @foreach (session()->get('error') as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
