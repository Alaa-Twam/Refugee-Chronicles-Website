@extends('admin.layouts.master')

@section('title',$title)
@section('content_header')
    @component('admin.components.content_header')
        @slot('page_title')
            {{ $title }}
        @endslot
        @slot('breadcrumb')
            {{ Breadcrumbs::render($breadcrumb) }}
        @endslot
    @endcomponent
@endsection
@section('content')
<div class="row g-0">
    <div class="col-lg-4 col-md-6">
        <div class="card border">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="d-flex no-block align-items-center">
                            <div>
                                <h3><i class="fa fa-newspaper"></i></h3>
                                <p class="text-muted">ALL CHRONICLES</p>
                            </div>
                            <div class="ms-auto">
                                <h2 class="counter text-cyan">{{ \CMS::getAllChronicles() }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="progress">
                            <div class="progress-bar bg-cyan" role="progressbar" style="width: 100%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6">
        <div class="card border">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="d-flex no-block align-items-center">
                            <div>
                                <h3><i class="fa fa-newspaper"></i></h3>
                                <p class="text-muted">ACTIVE CHRONICLES</p>
                            </div>
                            <div class="ms-auto">
                                <h2 class="counter text-success">{{ \CMS::getActiveChronicles() }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="progress">
                            <div class="progress-bar bg-success" role="progressbar" style="width: 100%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6">
        <div class="card border">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="d-flex no-block align-items-center">
                            <div>
                                <h3><i class="fa fa-newspaper"></i></h3>
                                <p class="text-muted">DISABLED CHRONICLES</p>
                            </div>
                            <div class="ms-auto">
                                <h2 class="counter text-primary">{{ \CMS::getDisabledChronicles() }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="progress">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: 100%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
