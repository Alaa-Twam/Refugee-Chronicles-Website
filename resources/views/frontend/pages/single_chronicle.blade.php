@extends('frontend.layouts.master')
@section('content')
	<section class="page-title">
		<div class="container">
			<div class="page-title-row">
				<div class="page-title-content">
					<h1>Chronicles</h1>
				</div>
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('chronicles') }}">Chronicles</a></li>
						<li class="breadcrumb-item active" aria-current="page">{{ $chronicle->title }}</li>
					</ol>
				</nav>
			</div>
		</div>
	</section>
	<section id="content">
		<div class="content-wrap">
			<div class="container">
            <div class="single-post mb-0">
                <div class="entry">
                    <div class="entry-title mb-5">
                        <h2>{{ strip_tags($chronicle->title) }}</h2>
                    </div>
                    <div class="entry-video mb-5" data-id="{{ $chronicle->id }}" data-video-id="{{ $chronicle->youtube_link }}">
						<div  class="youtube-video">
							<div id="player{{ $chronicle->id }}" class="video-id"></div>
						</div>
						<img src="https://img.youtube.com/vi/{{ $chronicle->youtube_link }}/maxresdefault.jpg" class="youtube-image" />
						<img src="{{ asset('frontend/images/play.png') }}" class="play-icon" />
					</div>
                    <div class="entry-content mt-0">
						{!! htmlspecialchars_decode($chronicle->description) !!}
                    </div>
                </div>
			</div>
		</div>
	</section>
@endsection