@extends('coreui::layouts.admin')

@section('title', 'Create Document')

@section('content')
	<div class="container-fluid py-4">
		<div class="card shadow-sm">
			<div class="card-header">
				Create Document
			</div>

			<div class="card-body">
				<form method="POST" action="{{ route('documents.admin.store') }}">
					@csrf

					<div class="mb-3">
						<label for="title" class="form-label">Title</label>
						<input
								type="text"
								id="title"
								name="title"
								class="form-control"
								value="{{ old('title') }}"
								required
						>
					</div>

					<div class="mb-3">
						<label for="markdown_body" class="form-label">Markdown</label>
						<textarea
								id="markdown_body"
								name="markdown_body"
								class="form-control"
								rows="12"
								required
						>{{ old('markdown_body') }}</textarea>
					</div>

					<div class="mb-3">
						<label for="status" class="form-label">Status</label>
						<select id="status" name="status" class="form-select">
							<option value="draft">Draft</option>
							<option value="published">Published</option>
						</select>
					</div>

					<button type="submit" class="btn btn-primary">Save</button>
				</form>
			</div>
		</div>
	</div>
@endsection
