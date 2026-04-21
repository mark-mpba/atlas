@extends('coreui::layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
	<div class="container-fluid py-4">
		<div class="row g-4">
			<div class="col-md-3">
				<div class="card shadow-sm">
					<div class="card-header">Documents</div>
					<div class="card-body">
						<h3 class="mb-0">{{ $stats['documents_total'] ?? 0 }}</h3>
					</div>
				</div>
			</div>

			<div class="col-md-3">
				<div class="card shadow-sm">
					<div class="card-header">Published</div>
					<div class="card-body">
						<h3 class="mb-0">{{ $stats['documents_published'] ?? 0 }}</h3>
					</div>
				</div>
			</div>

			<div class="col-md-3">
				<div class="card shadow-sm">
					<div class="card-header">Drafts</div>
					<div class="card-body">
						<h3 class="mb-0">{{ $stats['documents_draft'] ?? 0 }}</h3>
					</div>
				</div>
			</div>

			<div class="col-md-3">
				<div class="card shadow-sm">
					<div class="card-header">Users</div>
					<div class="card-body">
						<h3 class="mb-0">{{ $stats['users_total'] ?? 0 }}</h3>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
