@foreach($actions as $action)
    @if(!empty($action))
        {!! PearlsForm::link($action['href'],(isset($action['icon'])?'<i class="'.$action['icon'].'"></i> ':'').$action['label'],[
        'class'=>$action['class']??'',
        'data'=>$action['data']??[],
        'target'=>$action['target']??'_self'
        ]) !!}
    @endif
@endforeach
