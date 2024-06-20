@if(!empty($actions))
    <div class="item-actions">
        <div class="btn-group pull-right">
            <button type="button" class="btn btn-sm btn-default dropdown-toggle" style="padding: 2px 8px 0 8px;"
                    data-bs-toggle="dropdown"
                    aria-expanded="false"><i class="fa fa-ellipsis-v" style="font-size: 1.2em;"></i></button>
            <ul class="dropdown-menu" role="menu">
                @foreach($actions as $action)
                    <li>
                        <a target="{{ $action['target']??'_self' }}" href="{{ $action['href'] }}"
                        @foreach($action['data'] as $key=>$data) {!! 'data-'.$key.'="'.$data.'" ' !!} @endforeach >
                            <i class="{{ $action['icon'] }}"></i> {!! $action['label'] !!}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="clearfix"></div>
@endif
