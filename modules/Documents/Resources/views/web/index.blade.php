@extends('coreui::layouts.docs')

@section('title', 'Documentation')

@section('breadcrumbs')
	<ul class="wy-breadcrumbs">
		<li>Home</li>
	</ul>
@endsection

@section('content')
	<div class="document">
		<div class="section">
			<h1>Documentation</h1>

			<ul>
				@forelse($documents as $document)
					<li>
						<a href="{{ route('documents.web.show', $document->slug) }}">
							{{ $document->title }}
						</a>
					</li>
				@empty
					<li>No documents found.</li>
				@endforelse
			</ul>
		</div>
	</div>
@endsection
