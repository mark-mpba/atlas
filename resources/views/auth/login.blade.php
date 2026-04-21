@extends('admin::layouts.master')

@section('title', 'Login')

@section('content')
	<div class="container py-5">
		<div class="row justify-content-center">
			<div class="col-md-5">
				<div class="card shadow-sm">
					<div class="card-header">
						Login
					</div>

					<div class="card-body">
						<form method="POST" action="{{ route('login') }}">
							@csrf

							<div class="mb-3">
								<label for="email" class="form-label">Email</label>
								<input
										type="email"
										id="email"
										name="email"
										class="form-control"
										value="{{ old('email') }}"
										required
										autofocus
								>
							</div>

							<div class="mb-3">
								<label for="password" class="form-label">Password</label>
								<input
										type="password"
										id="password"
										name="password"
										class="form-control"
										required
								>
							</div>

							<div class="form-check mb-3">
								<input
										type="checkbox"
										id="remember"
										name="remember"
										class="form-check-input"
								>
								<label for="remember" class="form-check-label">Remember me</label>
							</div>

							<div class="d-flex justify-content-between align-items-center">
								<button type="submit" class="btn btn-primary">
									Login
								</button>

								<a href="{{ route('password.request') }}">
									Forgot password?
								</a>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
