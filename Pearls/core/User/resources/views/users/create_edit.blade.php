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
                {!! Form::model($user, ['url' => url($resource_url.'/'.$user->hashed_id),'method'=>$user->exists?'PUT':'POST','class'=>'']) !!}

                <div class="row">
                    <div class="col-md-4">
                        {!! PearlsForm::text('first_name','First Name', true, optional($user)->first_name ) !!}
                        {!! PearlsForm::text('last_name','Last Name', true, optional($user)->last_name ) !!}
                        @if(optional($user)->exists)
                            {{ Form::label('username', 'Username') }}
                            <h4>{{ optional($user)->username }}</h4>
                        @else
                            {!! PearlsForm::text('username','Username', true, optional($user)->username ) !!}
                        @endif
                    </div>
                    <div class="col-md-4">
                        {!! PearlsForm::email('email', 'Email', true, optional($user)->email) !!}


                        {!! PearlsForm::password('password', 'Password', $required = !optional($user)->exists  ) !!}

                        {!! PearlsForm::password('password_confirmation', 'Password Confirmation', $required = !optional($user)->exists  ) !!}
                    </div>
                    <div class="col-md-4">
                        {!! PearlsForm::select('status', 'Status', ['active'=>'Active','pending'=>'Pending'], true, optional($user)->status) !!}


                        {!! PearlsForm::checkboxes(
                            'roles[]',
                            'Roles',
                            true,
                            $options = Roles::getRolesList(['admin_roles' => \Request::is('*users*')]),
                            $selected = $user ? $user->roles->pluck('id')->toArray():[])

                         !!}
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
