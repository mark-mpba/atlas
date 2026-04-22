@php
	use Illuminate\Support\Str;
@endphp

@extends('coreui::layouts.docs')

@section('title', $document->title ?? 'Document')
@section('brand_title', config('app.name'))
@section('page_title', $document->title ?? 'Document')
@section('document_section', optional($document->category)->name ?? 'Documentation')
@section('document_title', $document->title ?? 'Document')
@section('document_description', $document->description ?? 'Documentation page')
@section('doc_version', $document->version ?? 'v1.0')
@section('prev_doc_url', $previousDocument ? route('documents.web.show', $previousDocument->slug) : '#')
@section('next_doc_url', $nextDocument ? route('documents.web.show', $nextDocument->slug) : '#')

@section('content')
	@php
		$rawContent = $renderedContent ?? '';
		preg_match_all('/<h([2-3])[^>]*>(.*?)<\/h[2-3]>/i', $rawContent, $headingMatches, PREG_SET_ORDER);
		$tocItems = collect($headingMatches)->map(function (array $match): array {
			$label = trim(strip_tags(html_entity_decode($match[2])));
			$id = Str::slug($label);
			return [
				'level' => (int) $match[1],
				'id'    => $id,
				'label' => $label,
			];
		})->values();

		$rawContent = preg_replace_callback('/<h([2-3])([^>]*)>(.*?)<\/h[2-3]>/i', function (array $match): string {
			$label = trim(strip_tags(html_entity_decode($match[3])));
			$id = Str::slug($label);
			return '<h' . $match[1] . $match[2] . ' id="' . e($id) . '">' . $match[3] . '</h' . $match[1] . '>';
		}, $rawContent);

	@endphp

	@push('toc')
		@foreach ($tocItems as $item)
			<a href="#{{ $item['id'] }}"
			   class="abbott-doc-link doc-toc-link block rounded-lg px-2 py-1.5 text-slate-600 {{ $item['level'] === 3 ? 'ms-4 text-xs' : '' }}"
			   data-target="{{ $item['id'] }}">
				{{ $item['label'] }}
			</a>
		@endforeach
	@endpush

	@if (! empty($rawContent))
		{!! $rawContent !!}
	@else
		<div class="rounded-2xl border border-amber-200 bg-amber-50 p-6 text-sm text-amber-800">
			No renderable markdown content was found for this document.
		</div>
	@endif
@endsection
