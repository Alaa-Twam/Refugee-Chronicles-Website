@if(!isset($empty))
    <li class="time-label {{isset($date) ? '' : 'hidden'}}"><span class="bg-red">{{ $date ?? '' }}</span>
    </li>
    <li>
        <i class="{{$icon ?? ''}}"></i>

        <div class="timeline-item {{$class ?? ''}}">


            {!! $timeline_summary ?? '' !!}

            <h3 class="timeline-header {{ isset($timeline_header) ? '' : 'hidden' }}{{$header_class ?? ''}}" id="{{$header_id ??  "timeline-header-$id" }}">
                {!! $timeline_header ?? '' !!}
            </h3>

            <div class="timeline-body {{$body_class ?? ''}}" id="{{$body_id ??  "timeline-body-$id" }}">
                {!! $slot??'' !!}
            </div>

            <div class="timeline-footer {{$footer_class ?? ''}} {{ !empty($timeline_footer)?'':'hidden' }}" id="timeline-footer-{{$id}}">
                {!! $timeline_footer or '' !!}
            </div>
        </div>
    </li>
@else
    <li>
        <i class="fa fa-info"></i>

        <div class="timeline-item">

            <h3 class="timeline-header no-border">
                {!! $empty_text !!}
            </h3>
        </div>
    </li>

@endif