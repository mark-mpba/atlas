<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>@yield('title', config('app.name'))</title>

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
<body class="wy-body-for-nav">
<div class="wy-grid-for-nav">
	<nav data-toggle="wy-nav-shift" class="wy-nav-side">
		<div class="wy-side-scroll">
			<div class="wy-side-nav-search">
				<a href="{{ url('/') }}" class="icon icon-home">{{ config('app.name', 'Atlas') }}</a>
				<div class="version">Documentation</div>
			</div>
		</div>
	</nav>

	<section data-toggle="wy-nav-shift" class="wy-nav-content-wrap">
		<nav class="wy-nav-top" aria-label="Mobile navigation menu">
			<i data-toggle="wy-nav-top" class="fa fa-bars"></i>
			<a href="{{ url('/') }}">{{ config('app.name', 'Atlas') }}</a>
		</nav>

		<div class="wy-nav-content">
			<div class="rst-content">
				@yield('content')
			</div>
		</div>
	</section>
</div>
</body>
</html>
