<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>@yield('title', 'Documentation')</title>

	<link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v={{ file_exists(public_path('favicon.ico')) ? filemtime(public_path('favicon.ico')) : time() }}">
	<link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v={{ file_exists(public_path('favicon.ico')) ? filemtime(public_path('favicon.ico')) : time() }}">

	@vite([
		'resources/css/app.css',
		'resources/css/docs/theme.css',
		'resources/css/docs/theme_extra.css',
		'resources/js/app.js',
	])
</head>
<body class="wy-body-for-nav atlas-home-page">
<div class="wy-grid-for-nav">
	<nav data-toggle="wy-nav-shift" class="wy-nav-side">
		<div class="wy-side-scroll">
			<div class="wy-side-nav-search">
				<a href="{{ route('documents.web.index') }}" class="icon icon-home">
					{{ config('app.name', 'Documentation') }}
				</a>

				<div class="version">
					Markdown Documentation Portal
				</div>
			</div>

			<div class="wy-menu wy-menu-vertical">
				<ul>
					<li class="toctree-l1 {{ request()->routeIs('documents.web.index') ? 'current' : '' }}">
						<a href="{{ route('documents.web.index') }}">Home</a>
					</li>

					@isset($navigation)
						@foreach($navigation as $section)
							<li class="toctree-l1 {{ $section['active'] ? 'current' : '' }}">
								<a href="#">{{ $section['title'] }}</a>

								@if(!empty($section['children']))
									<ul>
										@foreach($section['children'] as $child)
											<li class="toctree-l2 {{ $child['active'] ? 'current' : '' }}">
												<a href="{{ $child['url'] }}">{{ $child['title'] }}</a>
											</li>
										@endforeach
									</ul>
								@endif
							</li>
						@endforeach
					@endisset
				</ul>
			</div>
		</div>
	</nav>

	<section data-toggle="wy-nav-shift" class="wy-nav-content-wrap">
		<nav class="wy-nav-top" aria-label="Mobile navigation menu">
			<i data-toggle="wy-nav-top" class="fa fa-bars"></i>
			<a href="{{ route('documents.web.index') }}">{{ config('app.name', 'Documentation') }}</a>
		</nav>

		<div class="wy-nav-content">
			<div class="rst-content">
				@hasSection('breadcrumbs')
					<div role="navigation" aria-label="Page navigation">
						@yield('breadcrumbs')
						<hr>
					</div>
				@endif

				@yield('content')
			</div>
		</div>
	</section>
</div>
</body>
</html>
