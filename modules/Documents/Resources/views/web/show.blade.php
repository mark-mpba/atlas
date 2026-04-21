@extends('coreui::layouts.docs')

@section('title', $document->title)

@section('breadcrumbs')
	<ul class="wy-breadcrumbs">
		<li>
			<a href="{{ route('documents.web.index') }}">Home</a> &raquo;
		</li>

		@if($document->category)
			<li>{{ $document->category->name }} &raquo;</li>
		@endif

		<li>{{ $document->title }}</li>
	</ul>
@endsection

@section('content')
	<div class="document">
		<div class="section">
			<h1>{{ $document->title }}</h1>

			<div class="document-content">
				{!! $document->html_body !!}
			</div>
		</div>
	</div>
@endsection
