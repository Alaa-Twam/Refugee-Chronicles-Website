@extends('admin.layouts.crud.index')

@section('content_header')
    @component('admin.components.content_header')
        @slot('page_title')
            {{ $title }}
        @endslot
        @slot('breadcrumb')
            {{ Breadcrumbs::render('roles') }}
        @endslot
    @endcomponent
@endsection