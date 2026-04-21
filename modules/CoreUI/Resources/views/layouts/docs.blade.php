<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>@yield('title', config('app.name') . ' Documentation')</title>

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
			class="abbott-sidebar-gradient abbott-scrollbar fixed inset-y-0 left-0 z-40 flex w-72 flex-col overflow-y-auto border-r border-white/10 text-white transition-all duration-300 lg:static lg:translate-x-0"
	>
		<div class="sidebar-brand-wrap flex items-center justify-between px-4 py-4 border-b border-white/10">
			<div class="flex items-center gap-3">
				<div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/10 text-lg font-bold text-white">
					D
				</div>

				<div class="sidebar-brand-text">
					<div class="text-sm font-medium uppercase tracking-[0.2em] text-sky-200">
						Documentation
					</div>
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
					<a href="{{ url('/') }}" class="abbott-nav-link flex rounded-xl px-3 py-3 text-sm font-medium text-white/90">
                            <span class="sidebar-link-inner flex items-center gap-3">
                                <span class="text-sky-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 10.5L12 3l9 7.5V21a1 1 0 0 1-1 1h-5.5v-7h-5v7H4a1 1 0 0 1-1-1v-10.5Z" />
                                    </svg>
                                </span>
                                <span class="sidebar-label">Home</span>
                            </span>
					</a>

					<a href="{{ route('documents.web.index') ?? '#' }}" class="abbott-nav-link active flex rounded-xl px-3 py-3 text-sm font-medium text-white">
                            <span class="sidebar-link-inner flex items-center gap-3">
                                <span class="text-sky-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 4h8l4 4v12a1 1 0 0 1-1 1H7a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2Z" />
                                    </svg>
                                </span>
                                <span class="sidebar-label">Documents</span>
                            </span>
					</a>

					<a href="#" class="abbott-nav-link flex rounded-xl px-3 py-3 text-sm font-medium text-white/90">
                            <span class="sidebar-link-inner flex items-center gap-3">
                                <span class="text-sky-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m11.049 2.927 2.132 6.562h6.901l-5.584 4.057 2.133 6.562-5.582-4.057-5.582 4.057 2.133-6.562L1.916 9.49h6.901l2.232-6.562Z" />
                                    </svg>
                                </span>
                                <span class="sidebar-label">Favourites</span>
                            </span>
					</a>
				</nav>
			</div>

			<div class="mb-6">
				<div class="sidebar-section-title px-3 pb-2 text-xs font-semibold uppercase tracking-[0.2em] text-sky-200/80">
					Collections
				</div>

				<nav class="space-y-1">
					@php
						$docSections = $docSections ?? [
							['label' => 'Getting Started', 'url' => '#'],
							['label' => 'Architecture', 'url' => '#'],
							['label' => 'Modules', 'url' => '#'],
							['label' => 'API Reference', 'url' => '#'],
							['label' => 'Deployment', 'url' => '#'],
						];
					@endphp

					@foreach ($docSections as $section)
						<a href="{{ $section['url'] }}"
						   class="abbott-nav-link flex rounded-xl px-3 py-3 text-sm font-medium text-white/90">
                                <span class="sidebar-link-inner flex items-center gap-3">
                                    <span class="h-2.5 w-2.5 rounded-full bg-sky-300"></span>
                                    <span class="sidebar-label">{{ $section['label'] }}</span>
                                </span>
						</a>
					@endforeach
				</nav>
			</div>
		</div>

		<div class="border-t border-white/10 px-4 py-4">
			<div class="sidebar-footer-text rounded-2xl bg-white/10 p-3">
				<div class="text-xs font-semibold uppercase tracking-[0.2em] text-sky-200">
					Current Version
				</div>
				<div class="mt-1 text-sm font-semibold text-white">
					@yield('doc_version', 'v1.0')
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
						<h1 class="text-lg font-bold text-white">
							@yield('page_title', 'Document Viewer')
						</h1>
					</div>
				</div>

				<div class="hidden md:flex md:w-full md:max-w-md md:items-center">
					<div class="relative w-full">
						<input
								type="text"
								placeholder="Search documents..."
								class="w-full rounded-xl border border-white/20 bg-white/10 py-2.5 pl-10 pr-4 text-sm text-white placeholder:text-slate-200 focus:border-sky-300 focus:outline-none focus:ring-2 focus:ring-sky-300/30"
						>
						<div class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-sky-200">
							<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m21 21-4.35-4.35m1.85-5.15a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
							</svg>
						</div>
					</div>
				</div>
			</div>
		</header>

		<main class="min-h-0 flex-1 overflow-y-auto">
			<div class="mx-auto grid max-w-screen-2xl grid-cols-1 gap-6 px-4 py-6 lg:grid-cols-[minmax(0,1fr)_18rem] lg:px-6">
				<section class="min-w-0">
					<div class="abbott-card rounded-3xl p-6 lg:p-8">
						<div class="mb-8 flex flex-col gap-4 border-b border-slate-200 pb-6 md:flex-row md:items-center md:justify-between">
							<div>
								<p class="mb-2 text-sm font-semibold uppercase tracking-[0.2em] text-sky-600">
									@yield('document_section', 'Technical Documentation')
								</p>
								<h2 class="text-3xl font-extrabold text-slate-900">
									@yield('document_title', 'Document Title')
								</h2>
								<p class="mt-2 max-w-3xl text-sm text-slate-600">
									@yield('document_description', 'A structured technical document using the Abbott colour palette and documentation navigation layout.')
								</p>
							</div>

							<div class="flex items-center gap-3">
								<a href="@yield('prev_doc_url', '#')"
								   class="inline-flex items-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-sky-300 hover:text-sky-700">
									Previous
								</a>

								<a href="@yield('next_doc_url', '#')"
								   class="inline-flex items-center rounded-xl px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:opacity-90"
								   style="background-color: var(--abbott-primary-blue);">
									Next
								</a>
							</div>
						</div>

						<article class="abbott-content prose prose-slate max-w-none">
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
								@php
									$tocItems = $tocItems ?? [
										['id' => 'overview', 'label' => 'Overview'],
										['id' => 'installation', 'label' => 'Installation'],
										['id' => 'configuration', 'label' => 'Configuration'],
										['id' => 'usage', 'label' => 'Usage'],
										['id' => 'api-reference', 'label' => 'API Reference'],
									];
								@endphp

								@foreach ($tocItems as $item)
									<a href="#{{ $item['id'] }}"
									   class="abbott-doc-link doc-toc-link block rounded-lg px-2 py-1.5 text-slate-600"
									   data-target="{{ $item['id'] }}">
										{{ $item['label'] }}
									</a>
								@endforeach
							</nav>
						</div>

						<div class="abbott-card rounded-3xl p-5">
							<div class="mb-3 text-sm font-extrabold uppercase tracking-[0.18em] text-slate-800">
								More Documents
							</div>

							@php
								$relatedDocuments = $relatedDocuments ?? [
									['title' => 'System Overview', 'url' => '#'],
									['title' => 'Module Structure', 'url' => '#'],
									['title' => 'Deployment Guide', 'url' => '#'],
								];
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
