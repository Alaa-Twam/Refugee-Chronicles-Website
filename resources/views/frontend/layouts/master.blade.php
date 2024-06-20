<!DOCTYPE html>
<html dir="ltr" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta http-equiv="x-ua-compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Bazaard">
    <meta name="description" content="Explore Palestine's History. Stories of Al-Nakba through first-hand accounts. Sharing the strength and resilience of people who survived one of humanity’s biggest catastrophes.">
    <meta name='keywords' content="Refugee Chronicles, Refugee, Chronicles" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <link rel="stylesheet" href="{{ asset('frontend/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/font-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/swiper.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/custom.css') }}">

    <title>Refugee Chronicles | {{ $pageTitle }}</title>

    <script type="text/javascript">
        window.base_url = '{!! url('/') !!}';
        let loader = '<div class="panel-body"><div class="clearfix"><div class="loader"><div class="col-sm-12"><p class="animate-bg"><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /></p></div><div class="col-sm-12"><p class="animate-bg col-sm-11"></p><p class="animate-bg col-sm-9"></p><p class="animate-bg col-sm-10"></p><p class="animate-bg col-sm-9"></p></div><br /><div class="col-sm-6"><p class="animate-bg col-sm-11"></p><p class="animate-bg col-sm-9"></p><p class="animate-bg col-sm-10"></p><p class="animate-bg col-sm-9"></p><p class="animate-bg"></p></div><div class="col-sm-6"></div></div></div></div>';
    </script>
    @yield('css')

</head>
<body class="stretched">

    <div id="wrapper">

        @include('frontend.partials.header')

        @yield('content')

        @include('frontend.partials.footer')
    </div>

    <div id="gotoTop" class="uil uil-angle-up"></div>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js" crossorigin="anonymous"></script>
    @yield('js')

    <script src="{{ asset('frontend/js/plugins.min.js') }}"></script>
    <script src="{{ asset('frontend/js/functions.bundle.js') }}"></script>


    <script>
        $(document).ready(function() {
        
            var tag = document.createElement('script');
            tag.src = "https://www.youtube.com/iframe_api";
            var firstScriptTag = document.getElementsByTagName('script')[0];
            firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

            var player;

            function onPlayerReady(event) {
                //event.target.playVideo();
            }

            function onPlayerStateChange(event) {
                if(event.data == YT.PlayerState.ENDED) {
                    player.destroy();
                }
            }
            $(document).on('click', '.youtube-image, .play-icon', function() {
                let container = $(this).parent();
                let id = container.data('id');
                let videoId = container.data('video-id');
                let playerId = 'player' + id;

                container.find('.youtube-image, .play-icon').fadeOut(200);
                
                player = new YT.Player(playerId, {
                    videoId : videoId,
                    playerVars: { 'autoplay': 1 },
                    events : {
                        'onReady' : onPlayerReady,
                        'onStateChange' : onPlayerStateChange
                    }
                });
            });
        });
    </script>
</body>
</html>