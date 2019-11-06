@extends('layouts.empty', ['paceTop' => true])

@section('title', '419 Error Page')

@section('content')
	<!-- begin error -->
	<div class="error">
		<div class="error-code m-b-10">419</div>
		<div class="error-content">
			<div class="error-message">Page Expired</div>
			<div>
				<a href="{{ url()->previous() }}" class="btn btn-success p-l-20 p-r-20">Go Back</a>
			</div>
		</div>
	</div>
	<!-- end error -->
@endsection