<div class="feature-box media-box">
    <div class="fbox-media" data-id="{{ $chronicle->id }}" data-video-id="{{ $chronicle->youtube_link }}">
        <div  class="youtube-video">
            <div id="player{{ $chronicle->id }}" class="video-id"></div>
        </div>
        <img src="https://img.youtube.com/vi/{{ $chronicle->youtube_link }}/maxresdefault.jpg" class="youtube-image" />
        <img src="{{ asset('frontend/images/play.png') }}" class="play-icon" />
    </div>
    <div class="fbox-content px-0">
        <h3 style="text-align: left;" class="font-16">{{ strip_tags($chronicle->title) }}</h3>
        <div class="chronicle-content font-13"  style="font-size: 13px; text-align:left;">
                {!! htmlspecialchars_decode($chronicle->description) !!}
        </div>
        <a href="#" class="chronicle-read-more-btn font-13">Read more</a>
    </div>
</div>