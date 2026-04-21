@extends('coreui::layouts.master')

@section('title', $document->title)

@section('content')
	<div class="section">
		<h1>{{ $document->title }}</h1>

		<div class="document-content">
			{!! $document->html_body !!}
		</div>
	</div>
@endsection
