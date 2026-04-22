<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>@yield('title', config('app.name') . '')</title>

	<meta name="csrf-token" content="{{ csrf_token() }}">

	@vite([
		'resources/css/app.css',
		'resources/js/app.js',
	])

	@stack('styles')
</head>
<body class="min-h-screen bg-slate-50">
<div class="flex h-screen overflow-hidden">
	<aside
			id="docsSidebar"
			dusk="docs-sidebar"
			class="abbott-sidebar-gradient abbott-scrollbar fixed inset-y-0 left-0 z-40 flex w-72 flex-col overflow-y-auto border-r border-white/10 text-white transition-all duration-300 lg:static lg:translate-x-0"
	>
		<div class="sidebar-brand-wrap flex items-center justify-between px-4 py-4 border-b border-white/10">
			<div class="flex items-center gap-3">
				<div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/10 text-lg font-bold text-white">
					D
				</div>
				<div class="sidebar-brand-text">
					<div class="text-lg font-bold text-white">
						@yield('brand_title', config('app.name'))
					</div>
				</div>
			</div>

			<button
					id="collapseSidebarBtn"
					type="button"
					class="hidden rounded-lg bg-white/10 p-2 text-white hover:bg-white/20 lg:inline-flex"
					aria-label="Collapse navigation"
			>
				<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 19l-7-7 7-7" />
				</svg>
			</button>
		</div>

		<div class="flex-1 px-3 py-4">
			<div class="mb-6">
				<div class="sidebar-section-title px-3 pb-2 text-xs font-semibold uppercase tracking-[0.2em] text-sky-200/80">
					Main
				</div>

				<nav class="space-y-1">
					<a href="{{ route('documents.web.home') }}" class="abbott-nav-link flex rounded-xl px-3 py-3 text-sm font-medium text-white/90">
						<span class="sidebar-link-inner flex items-center gap-3">
							<span class="text-sky-200">
								<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 10.5L12 3l9 7.5V21a1 1 0 0 1-1 1h-5.5v-7h-5v7H4a1 1 0 0 1-1-1v-10.5Z" />
								</svg>
							</span>
							<span class="sidebar-label">Home</span>
						</span>
					</a>
				</nav>
			</div>

			@php
				$navigation = $navigation ?? ['sections' => [], 'favourites' => []];
			@endphp

			<div class="mb-6">
				<div class="sidebar-section-title px-3 pb-2 text-xs font-semibold uppercase tracking-[0.2em] text-sky-200/80">
					Documents
				</div>

				<div class="space-y-2">
					@foreach ($navigation['sections'] as $index => $section)
						@php
							$collapseId = 'doc-section-' . $index;
							$isExpanded = !empty($section['expanded']);
						@endphp

						<div class="doc-search-section-wrapper overflow-hidden rounded-2xl bg-white/5">
							<button
									type="button"
									data-search-text="{{ strtolower($section['title']) }}"
									data-default-expanded="{{ $isExpanded ? 'true' : 'false' }}"
									class="doc-nav-toggle doc-search-section flex w-full items-center justify-between px-3 py-3 text-left text-sm font-semibold text-white/95 hover:bg-white/10"
									data-target="#{{ $collapseId }}"
									aria-expanded="{{ $isExpanded ? 'true' : 'false' }}"
							>
								<span class="flex items-center gap-3">
									<span class="text-sky-200">
										<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 7h16M4 12h16M4 17h16" />
										</svg>
									</span>
									<span>{{ $section['title'] }}</span>
								</span>

								<span class="doc-nav-chevron transition-transform duration-200 {{ $isExpanded ? 'rotate-90' : '' }}">
									<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5l7 7-7 7" />
									</svg>
								</span>
							</button>

							<div id="{{ $collapseId }}" class="doc-nav-panel" style="display: {{ $isExpanded ? 'block' : 'none' }};">
								@if (!empty($section['children']))
									<nav class="space-y-1 px-2 pb-2">
										@foreach ($section['children'] as $child)
											<a
													href="{{ $child['url'] }}"
													data-search-text="{{ strtolower($child['title']) }}"
													class="abbott-nav-link doc-search-item flex rounded-xl px-3 py-2 text-sm font-medium {{ $child['active'] ? 'active text-white' : 'text-white/85' }}"
											>
												<span class="sidebar-link-inner flex items-center gap-3">
													<span class="h-2 w-2 rounded-full bg-sky-300"></span>
													<span class="sidebar-label">{{ $child['title'] }}</span>
												</span>
											</a>
										@endforeach
									</nav>
								@endif
							</div>
						</div>
					@endforeach
				</div>
			</div>

			@if (!empty($navigation['favourites']))
				<div class="mb-6">
					<div class="sidebar-section-title px-3 pb-2 text-xs font-semibold uppercase tracking-[0.2em] text-sky-200/80">
						Favourites
					</div>

					<nav class="space-y-1">
						@foreach ($navigation['favourites'] as $favourite)
							<a
									href="{{ $favourite['url'] }}"
									data-search-text="{{ strtolower($favourite['title']) }}"
									class="abbott-nav-link doc-search-item flex rounded-xl px-3 py-2 text-sm font-medium {{ $favourite['active'] ? 'active text-white' : 'text-white/85' }}"
							>
								<span class="sidebar-link-inner flex items-center gap-3">
									<span class="text-sky-200">
										<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
											<path d="m9.049 2.927.951 2.928a1 1 0 0 0 .95.69h3.078l-2.49 1.809a1 1 0 0 0-.364 1.118l.951 2.928-2.49-1.809a1 1 0 0 0-1.176 0l-2.49 1.809.951-2.928a1 1 0 0 0-.364-1.118L4.972 6.545H8.05a1 1 0 0 0 .95-.69l.049-.151Z"/>
										</svg>
									</span>
									<span class="sidebar-label">{{ $favourite['title'] }}</span>
								</span>
							</a>
						@endforeach
					</nav>
				</div>
			@endif
		</div>

		<div class="border-t border-white/10 px-4 py-4">
			<div class="sidebar-footer-text rounded-2xl bg-white/10 p-3">
				<div class="text-xs font-semibold uppercase tracking-[0.2em] text-sky-200">
					Current Version: @yield('doc_version', 'v1.0')
				</div>
			</div>
		</div>
	</aside>

	<div
			id="mobileSidebarBackdrop"
			class="fixed inset-0 z-30 hidden bg-slate-900/50 lg:hidden"
	></div>

	<div class="flex min-w-0 flex-1 flex-col">
		<header class="abbott-topbar sticky top-0 z-20 border-b border-white/10">
			<div class="flex h-16 items-center justify-between px-4 lg:px-6">
				<div class="flex items-center gap-3">
					<button
							id="openSidebarMobileBtn"
							type="button"
							class="inline-flex rounded-lg bg-white/10 p-2 text-white hover:bg-white/20 lg:hidden"
							aria-label="Open navigation"
					>
						<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6h16M4 12h16M4 18h16" />
						</svg>
					</button>

					<div>
						<div class="text-xs font-semibold uppercase tracking-[0.2em] text-sky-200">
							Documentation Portal
						</div>
						<h1 class="text-lg font-bold text-white" dusk="docs-page-title">
							@yield('page_title', 'Document Viewer')
						</h1>
					</div>
				</div>

				<div class="hidden md:flex md:w-full md:max-w-md md:items-center">
					<div class="relative w-full">
						<input
								id="docsSearchInput"
								type="text"
								placeholder="Search documents..."
								class="w-full rounded-xl border border-white/20 bg-white/10 py-2.5 pl-10 pr-12 text-sm text-white placeholder:text-slate-200 focus:border-sky-300 focus:outline-none focus:ring-2 focus:ring-sky-300/30"
						>

						<div class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-sky-200">
							<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m21 21-4.35-4.35m1.85-5.15a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
							</svg>
						</div>

						<button
								id="docsSearchClearBtn"
								type="button"
								aria-label="Clear search"
								title="Clear search"
						>
							<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M6 6l12 12M18 6L6 18" />
							</svg>
						</button>
					</div>
				</div>
			</div>
		</header>

		<main class="min-h-0 flex-1 overflow-y-auto">
			<div class="mx-auto grid max-w-screen-2xl grid-cols-1 gap-6 px-4 py-6 lg:grid-cols-[minmax(0,1fr)_18rem] lg:px-6">
				<section class="min-w-0">
					<div class="abbott-card rounded-3xl p-6 lg:p-8">
						@php
							$documentSection = trim((string) $__env->yieldContent('document_section'));
							$documentTitle = trim((string) $__env->yieldContent('document_title'));
							$documentDescription = trim((string) $__env->yieldContent('document_description'));
							$prevDocUrl = trim((string) $__env->yieldContent('prev_doc_url'));
							$nextDocUrl = trim((string) $__env->yieldContent('next_doc_url'));

							$showDocumentHero = $documentSection !== '' || $documentTitle !== '' || $documentDescription !== '';
							$showDocumentNav = $prevDocUrl !== '' || $nextDocUrl !== '';
						@endphp

						@if ($showDocumentHero || $showDocumentNav)
							<div class="mb-8 flex flex-col gap-4 border-b border-slate-200 pb-6 md:flex-row md:items-center md:justify-between">
								@if ($showDocumentHero)
									<div>
										@if ($documentSection !== '')
											<p class="mb-2 text-sm font-semibold uppercase tracking-[0.2em] text-sky-600">
												{{ $documentSection }}
											</p>
										@endif

										@if ($documentTitle !== '')
											<h2 class="text-3xl font-extrabold text-slate-900">
												{{ $documentTitle }}
											</h2>
										@endif

										@if ($documentDescription !== '')
											<p class="mt-2 max-w-3xl text-sm text-slate-600">
												{{ $documentDescription }}
											</p>
										@endif
									</div>
								@endif

								@if ($showDocumentNav)
									<div class="flex items-center gap-3">
										@if ($prevDocUrl !== '')
											<a href="{{ $prevDocUrl }}"
											   class="inline-flex items-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-sky-300 hover:text-sky-700">
												Previous
											</a>
										@endif

										@if ($nextDocUrl !== '')
											<a href="{{ $nextDocUrl }}"
											   class="inline-flex items-center rounded-xl px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:opacity-90"
											   style="background-color: var(--abbott-primary-blue);">
												Next
											</a>
										@endif
									</div>
								@endif
							</div>
						@endif
						<article class="abbott-content prose prose-slate max-w-none" dusk="document-content">
							@yield('content')
						</article>
					</div>
				</section>

				<aside class="hidden lg:block">
					<div class="doc-toc-sticky space-y-4">
						<div class="abbott-card rounded-3xl p-5">
							<div class="mb-4 flex items-center gap-2">
								<div class="h-8 w-1 rounded-full" style="background-color: var(--abbott-primary-blue);"></div>
								<h3 class="text-sm font-extrabold uppercase tracking-[0.18em] text-slate-800">
									In This Document
								</h3>
							</div>

							<nav id="docTocNav" class="space-y-3 text-sm">
								@stack('toc')
							</nav>
						</div>

						<div class="abbott-card rounded-3xl p-5">
							<div class="mb-3 text-sm font-extrabold uppercase tracking-[0.18em] text-slate-800">
								More Documents
							</div>

							@php
								$relatedDocuments = $relatedDocuments ?? [];
							@endphp

							<div class="space-y-2">
								@foreach ($relatedDocuments as $document)
									<a href="{{ $document['url'] }}"
									   class="block rounded-xl border border-slate-200 px-4 py-3 text-sm font-medium text-slate-700 transition hover:border-sky-300 hover:bg-sky-50 hover:text-sky-700">
										{{ $document['title'] }}
									</a>
								@endforeach
							</div>
						</div>
					</div>
				</aside>
			</div>
		</main>
	</div>
</div>

@stack('scripts')
</body>
</html>
