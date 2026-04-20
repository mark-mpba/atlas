<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>{{ config('app.name', 'Atlas Docs') }}</title>
	@php
		$faviconPath = public_path('favicon.ico');
		$faviconHref = asset('favicon.ico') . (file_exists($faviconPath) ? '?v=' . filemtime($faviconPath) : '');
	@endphp
	<link rel="icon" type="image/x-icon" href="{{ $faviconHref }}">
	<link rel="shortcut icon" href="{{ $faviconHref }}">
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
				<a href="{{ url('/') }}" class="icon icon-home">
					{{ config('app.name', 'Atlas Docs') }}
				</a>

				<div class="version">
					Markdown Documentation Portal
				</div>
			</div>

			<div class="wy-menu wy-menu-vertical">
				<ul>
					<li class="toctree-l1 current">
						<a href="{{ url('/') }}">Home</a>
					</li>

					@auth
						<li class="toctree-l1">
							<a href="{{ url('/dashboard') }}">Dashboard</a>
						</li>
					@else
						@if (Route::has('login'))
							<li class="toctree-l1">
								<a href="{{ route('login') }}">Log in</a>
							</li>
						@endif

						@if (Route::has('register'))
							<li class="toctree-l1">
								<a href="{{ route('register') }}">Register</a>
							</li>
						@endif
					@endauth
				</ul>
			</div>
		</div>
	</nav>

	<section data-toggle="wy-nav-shift" class="wy-nav-content-wrap">
		<nav class="wy-nav-top" aria-label="Mobile navigation menu">
			<i data-toggle="wy-nav-top" class="fa fa-bars"></i>
			<a href="{{ url('/') }}">{{ config('app.name', 'Atlas Docs') }}</a>
		</nav>

		<div class="wy-nav-content">
			<div class="rst-content">
				<div role="navigation" aria-label="Page navigation">
					<ul class="wy-breadcrumbs">
						<li><a href="{{ url('/') }}">Docs</a> &raquo;</li>
						<li>Home</li>
					</ul>
					<hr>
				</div>

				<div class="document">
					<div class="section">
						<h1 class="atlas-brand">
							{{ config('app.name', 'Atlas Docs') }}
						</h1>

						<p class="atlas-subtitle">
							Host, manage, search, and publish markdown documents from a Laravel admin dashboard.
						</p>

						<div class="atlas-top-links">
							@auth
								<a href="{{ url('/dashboard') }}" class="btn btn-neutral">
									Dashboard
								</a>
							@else
								@if (Route::has('login'))
									<a href="{{ route('login') }}" class="btn btn-neutral">
										Log in
									</a>
								@endif

								@if (Route::has('register'))
									<a href="{{ route('register') }}" class="btn btn-neutral">
										Register
									</a>
								@endif
							@endauth
						</div>

						<div class="atlas-doc-card admonition note">
							<p class="admonition-title">Getting started</p>

							<p>
								This application is configured to use your documentation theme for the public frontend,
								while the admin area can manage markdown content, publishing, and navigation.
							</p>

							<ul>
								<li>Browse published markdown documents</li>
								<li>Log in to manage content from the admin dashboard</li>
								<li>Use categories and tags to organise documents</li>
								<li>Render markdown into styled documentation pages</li>
							</ul>
						</div>

						<div class="atlas-actions">
							<a href="{{ url('/docs') }}" class="btn btn-neutral">
								View Documentation
							</a>

							@auth
								<a href="{{ url('/admin/documents') }}" class="btn btn-neutral">
									Manage Documents
								</a>
							@endif
						</div>

						<div class="section atlas-doc-card">
							<h2>About this portal</h2>
							<p>
								The public site uses the Read the Docs-style theme stylesheet, with local overrides
								applied through <code>theme_extra.css</code>. The layout is intentionally structured
								around <code>wy-nav</code>, <code>wy-menu</code>, and <code>rst-content</code> classes.
							</p>

							<pre><code>v{{ app()->version() }}</code></pre>
						</div>
					</div>
				</div>

				<footer>
					<hr>
					<div role="contentinfo">
						<p>&copy; {{ now()->year }} MPBA Limited</p>
					</div>
				</footer>
			</div>
		</div>
	</section>
</div>
</body>
</html>
