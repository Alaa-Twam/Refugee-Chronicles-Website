<div class="panel {{ $box_class??'' }}">
    <div class="panel-header {{ empty($box_title) && empty($box_actions)?'hidden':'' }}">
        <h3 class="{{ !empty($box_title) || !empty($box_actions)?'':'hidden' }}">
            {{ $box_title ?? '' }}
        </h3>
        <div class="control-btn">
            {!! $box_actions ?? '' !!}
            {{--<a href="#" class="panel-toggle"><i class="fa fa-angle-down"></i></a>--}}
        </div>
    </div>
    <div class="panel-content"
         style="{!! isset($closed) && $closed?'display: none;':'' !!}">
        {{ $slot }}
    </div>

    <div class="panel-footer {{ !empty($box_footer)?'':'hidden' }}">{{ $box_footer ?? '' }}</div>
</div>
