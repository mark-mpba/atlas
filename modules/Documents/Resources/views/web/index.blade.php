@extends('coreui::layouts.master')

@section('title', 'Documentation')

@section('content')
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
@endsection
