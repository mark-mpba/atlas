@extends('coreui::layouts.admin')

@section('title', 'Documents')

@section('content')
	<div class="container-fluid py-4">
		<div class="d-flex justify-content-between mb-3">
			<h1 class="h3 mb-0">Documents</h1>
			<a href="{{ route('documents.admin.create') }}" class="btn btn-primary">Create Document</a>
		</div>

		<div class="card shadow-sm">
			<div class="card-body">
				<p class="mb-0">Document listing will go here.</p>
			</div>
		</div>
	</div>
@endsection
