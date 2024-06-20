@extends('admin.layouts.crud.create_edit')

@section('css')
    <style type="text/css">
        .table, .table .form-group {
            margin-bottom: 0;
        }

        .panel-group {
            margin-bottom: 0;
        }
    </style>
@endsection
@section('content_header')
    @component('admin.components.content_header')
        @slot('page_title')
            {{ $title_singular }}
        @endslot

        @slot('breadcrumb')
            {{ Breadcrumbs::render('role_create_edit', $title_singular) }}
        @endslot
    @endcomponent
@endsection

@section('content')
    <div class="row">
        <div class="col-md-10">
            @component('admin.components.box')
                {!! Form::model($role, ['url' => url($resource_url.'/'.$role->hashed_id),'method'=>$role->exists?'PUT':'POST','class'=>'']) !!}
                <div class="row">
                    <div class="col-md-4">
                        {!! PearlsForm::text('name','Name', true, $role->name ) !!}
                        {!! PearlsForm::select('type', 'Type', ['admin'=>'Admin'], true, $role->type) !!}

                        {!! PearlsForm::formButtons() !!}
                    </div>
                    <div class="col-md-8">
                        <div class="text-right">
                            {!! \PearlsForm::button( 'Toggle collapse state' ,['class'=>'btn btn-sm btn-primary','id'=>'toggle_collapse']) !!}
                            {!! \PearlsForm::button('<i class="fa fa-check"></i> Check All', ['class'=>'btn btn-sm btn-success', 'id'=>'check_all']) !!}
                            {!! \PearlsForm::button('<i class="fa fa-remove"></i> Revoke All', ['class'=>'btn btn-sm btn-warning', 'id'=>'revoke_all']) !!}
                        </div>
                        <div class="">
                            <small class="text-muted">
                                <i class="fa fa-th-large"></i> Package
                            </small>
                            <small class="text-muted m-l-10">
                                <i class="fa fa-square"></i> Model
                            </small>
                            <hr/>
                        </div>
                        @foreach(Roles::getPermissionsTree() as $name => $package)
 
                            <ul class="list-unstyled panel-group" id="{{ $name }}_accordion">
                                <li>
                                    <i class="fa fa-th-large"></i> {{ $name }}
                                    <ul class="list-unstyled" style="margin-left: 25px;">
                                        @foreach($package as $name => $model)
                                            <li>
                                                <a data-bs-toggle="collapse" data-parent="#{{ $name }}_accordion"
                                                   href="#collapse_{{ $colID = $name.\Str::random() }}">
                                                    <i class="fa fa-square"></i> {{ $name }}</a>
                                                <ul class="list-inline panel-collapse collapse"
                                                    id="collapse_{{ $colID }}"
                                                    style="margin-left: 25px;">
                                                    @foreach($model as $id => $name)
                                                        <li>
                                                            {!! PearlsForm::checkbox('permissions[]',$name,$role->permissions->pluck('id')->contains($id),$id,['id'=>'perm_'.$id]) !!}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            </ul>
                        @endforeach
                    </div>
                </div>
                {!! Form::close() !!}
            @endcomponent
        </div>
    </div>
@endsection

@section('js')
    @parent
    <script type="text/javascript">
        $(document).ready(function () {
            $('#check_all').click(function (e) {
                $('input').iCheck('check');
            });
            $('#revoke_all').click(function (e) {
                $('input').iCheck('uncheck');
            });

            $('#toggle_collapse').click(function (e) {
                $('.panel-collapse').collapse('toggle');
            });
        })
    </script>
@endsection
