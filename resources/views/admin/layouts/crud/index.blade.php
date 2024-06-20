@extends('admin.layouts.master')

@section('title', $title)

@section('actions')
    @if(!empty($dataTable->bulkActions()))
        {!! $dataTable->bulkActions() !!}
    @endif

    @if(!isset($showCreateButton) or $showCreateButton == true)
        {!! \PearlsForm::link(url($resource_url.'/create'),
        '<i class="fa fa-plus-circle"></i> Create',
        ['class'=>'btn btn-success m-l-15 text-white float-md-end']) !!}
    @endif
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            @component('admin.components.box',['box_class'=>'box-primary'])
                @if(!empty($dataTable->filters()))
                    <div id="{{ $dataTable->getTableAttributes()['id'] }}_filtersCollapse"
                         class="filtersCollapse collapse">
                        <br/>
                        {!! $dataTable->filters() !!}
                    </div>
                @endif
                <div class="table-responsive pagination2"
                     style="min-height: 400px;padding-bottom: 100px;margin-top: 10px;">
                    {!! $dataTable->table(['class' => 'table table-hover table-striped table-condensed dataTableBuilder','style'=>'width:100%;']) !!}
                </div>
            @endcomponent
        </div>
    </div>
    {!! $dataTable->rowDetailsTemplate() !!}
@endsection

@section('js')
    @include('admin.layouts.crud.filters_script')

    {!! $dataTable->assets() !!}
    {!! $dataTable->scripts() !!}
@endsection


