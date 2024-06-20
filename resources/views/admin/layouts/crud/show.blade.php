@extends('admin.layouts.master')

@section('css')
@endsection

@section('title', strip_tags($title_singular))

@section('actions')
    @isset($showModel)
        {!! $showModel->getActions() !!}
    @endisset
@endsection

@section('js')
@endsection
