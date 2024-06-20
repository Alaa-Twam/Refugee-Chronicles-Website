@extends('frontend.layouts.master')

@section('content')
	<section class="page-title">
		<div class="container">
			<div class="page-title-row">
				<div class="page-title-content">
					<h1>Support Our Work</h1>
				</div>
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
						<li class="breadcrumb-item active" aria-current="page">Support Our Work</li>
					</ol>
				</nav>
			</div>
		</div>
	</section>
	<section id="content">
		<div class="content-wrap">
			<div class="container">
				<div class="row col-mb-50 mb-0">
					<div class="col-lg-12">
						<p class="font-20">
                        As this marks the final generation of Nakba survivors, it is imperative that we meticulously document their narratives to preserve them for future generations. Your support plays a pivotal role in covering travel expenses to various locations for conducting interviews with these individuals, as well as facilitating the editing and translation of their testimonials.
						</p>
						<p>
                            <a href="https://www.patreon.com/RefugeeChronicles" class="button button-3d button-rounded text-uppercase button-green" target="_blank">
                                Support our work
                                <i class="bi-arrow-right-circle-fill"></i>
                            </a>
                        </p>
					</div>
				</div>
			</div>
		</div>
	</section>
@endsection