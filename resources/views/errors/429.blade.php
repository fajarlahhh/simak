@extends('layouts.empty', ['paceTop' => true])

@section('title', '429 Error Page')

@section('content')
	<!-- begin error -->
	<div class="error">
		<div class="error-code m-b-10">429</div>
		<div class="error-content">
			<div class="error-message">Too Many Requests</div>
			<div>
				<a href="/" class="btn btn-success p-l-20 p-r-20">Go Home</a>
			</div>
		</div>
	</div>
	<!-- end error -->
@endsection