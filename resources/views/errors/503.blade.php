@extends('layouts.empty', ['paceTop' => true])

@section('title', '503 Error Page')

@section('content')
	<!-- begin error -->
	<div class="error">
		<div class="error-code m-b-10">503</div>
		<div class="error-content">
			<div class="error-message">Service Unavailable</div>
			<div class="error-desc m-b-30">
				$exception->getMessage()
			</div>
			<div>
				<a href="/" class="btn btn-success p-l-20 p-r-20">Go Home</a>
			</div>
		</div>
	</div>
	<!-- end error -->
@endsection