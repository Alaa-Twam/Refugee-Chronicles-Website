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
						<li class="breadcrumb-item active" aria-current="page">Chronicles</li>
					</ol>
				</nav>
			</div>
		</div>
	</section>
	<section id="content">
		<div class="content-wrap">
			<div class="container">
				<div class="row col-mb-50">
					<div class="col-md-8 offset-md-2">
						<form action="{{ url('chronicles') }}" method="POST">
							@csrf
							<div class="input-group">
								<input type="text" name="search" class="form-control" value="{{ !empty($search) ? $search : '' }}" placeholder="Search..." aria-label="Search">
								<div class="input-group-append">
									<button type="submit" class="btn btn-primary search-icon" type="button"><i class="fas fa-search"></i></button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div class="container">
				<div id="posts" class="post-grid row grid-container gutter-40">
					@forelse($chronicles as $chronicle)
					<div class="entry col-md-4 col-sm-6 col-12">
						<div class="grid-inner">
							<div class="entry-video-box" data-id="{{ $chronicle->id }}" data-video-id="{{ $chronicle->youtube_link }}">
								<div  class="youtube-video">
									<div id="player{{ $chronicle->id }}" class="video-id"></div>
								</div>
								<img src="https://img.youtube.com/vi/{{ $chronicle->youtube_link }}/maxresdefault.jpg" class="youtube-image" />
								<img src="{{ asset('frontend/images/play.png') }}" class="play-icon" />
						    </div>
							<div class="entry-title">
								<h2>
									<a href="{{ url('chronicles/'.$chronicle->id) }}">
									{!! htmlspecialchars_decode(strip_tags(\Str::limit($chronicle->title, $limit = 80, $end = '...'))) !!}
									</a>
								</h2>
							</div>
							<div class="entry-content">
								{!! htmlspecialchars_decode(strip_tags(\Str::limit($chronicle->description, $limit = 300, $end = '...'))) !!}
								<br />
								<br />
								<a href="{{ url('chronicles/'.$chronicle->id) }}" class="more-link">Read More</a>
							</div>
						</div>
					</div>
					@empty
						<p class="no-results">There is No Chronicles to Display.</p>
					@endforelse
				</div>
				{{ $chronicles->links() }}
			</div>
		</div>
	</section>
@endsection