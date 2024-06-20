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
@section('content')
    <div class="row">
        <div class="col-md-12">
            @component('admin.components.box')
                {!! Form::model($city, ['url' => url($resource_url.'/'.$city->id),'method'=>$city->exists?'PUT':'POST','class'=>'']) !!}

                <div class="row">
                    <div class="col-md-6">
                        {!! PearlsForm::text('name','Name', true, optional($city)->name ) !!}
                    </div>
                    <div class="col-md-6">
                        {!! PearlsForm::textarea('description','Description', true, $city->description, ['rows'=>15, 'class'=>'summernote']) !!}
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
    editorSummernote();
</script>
@endsection