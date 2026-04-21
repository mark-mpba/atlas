<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>@yield('title', config('app.name'))</title>

	@vite([
		'resources/css/app.css',
		'resources/js/app.js',
	])
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
	<div class="container-fluid">
		<a class="navbar-brand" href="{{ route('admin.dashboard') }}">
			{{ config('app.name', 'Atlas') }}
		</a>
	</div>
</nav>

<main>
	@yield('content')
</main>
</body>
</html>
