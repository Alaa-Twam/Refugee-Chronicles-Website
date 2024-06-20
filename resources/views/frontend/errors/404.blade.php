@extends('frontend.layouts.master')

@section('content')
	<section id="content">
		<div class="content-wrap">
			<div class="container">
            <div class="row align-items-center justify-content-center text-center col-mb-80">
						<div class="col-5">
							<img src="{{ asset('frontend/images/404.svg') }}" alt="404" class="d-block w-100">
						</div>
						<div class="col-8">
							<h2 class="display-5">Page not Found!</h2>
						</div>
					</div>
			</div>
		</div>
	</section>
@endsection