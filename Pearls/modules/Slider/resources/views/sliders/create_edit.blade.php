@extends('admin.layouts.crud.create_edit')

@section('content_header')
    @component('admin.components.content_header')
        @slot('page_title')
            {{ $title_singular }}
        @endslot

        @slot('breadcrumb')
            {{ Breadcrumbs::render($breadcrumb, $title_singular) }}
        @endslot
    @endcomponent
@endsection
@section('css')

@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            @component('admin.components.box')
                {!! Form::model($slider, ['url' => url($resource_url.'/'.$slider->hashed_id),'method'=>$slider->exists?'PUT':'POST','class'=>'']) !!}

                <div class="row">
                    <div class="col-md-6">
                        {!! PearlsForm::text('name','Title', true, optional($slider)->name ) !!}
                        {!! PearlsForm::select('status', 'Status', ['active'=>'Active','disabled'=>'Disabled'], true, optional($slider)->status) !!}
                        {!! PearlsForm::file('image', 'Add Image', true, $slider->image, ['class' => 'field']) !!}
                        <p class="help-block"><span  style="font-weight: bold; font-style: italic">Hint: </span>The recommended image dimensions are 2000 pixels in width and 1300 pixels in height.</p>
                        @if ($slider->hasMedia('slider-media'))
                            <div style="width: 100%; height: 300px; margin-bottom: 30px;">
                                <img src="{{ $slider->getFirstMediaUrl('slider-media') }}" style="width: 100%; height: 100%;" />
                            </div>
                        @endif
                    </div>
                    <div class="col-md-6">
                        {!! PearlsForm::textarea('caption','Caption', false, $slider->caption, ['rows'=>15, 'class'=>'summernote']) !!}    
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        {!! PearlsForm::formButtons() !!}
                    </div>
                </div>
                {!! Form::close() !!}
            @endcomponent
        </div>
    </div>
@endsection

@section('js')
<script>
editorSummernote()
</script>
@endsection