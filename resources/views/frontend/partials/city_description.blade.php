<div class="fbox-content px-0">
    <h3><b>{{ strip_tags($city->name) }}</b></h3>
    <div class="city-content">
        {!! htmlspecialchars_decode($city->description) !!}
    </div>
    <a href="#" class="city-read-more-btn">Read more</a>
</div>