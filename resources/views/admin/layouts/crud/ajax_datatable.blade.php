<div class="row">
    <div class="col-md-12">
        @if(!empty($dataTable->filters()))
            <div id="{{ $dataTable->getTableAttributes()['id'] }}_filtersCollapse"
                 class="filtersCollapse collapse">
                <br/>
                {!! $dataTable->filters() !!}
            </div>
        @endif
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive pagination2"
             style="min-height: 200px;padding-bottom: 100px;margin-top: 10px;">
            {!! $dataTable->table(['class' => 'table table-hover table-striped table-condensed dataTableBuilder','style'=>'width:100%;']) !!}
        </div>
    </div>
    {!! $dataTable->rowDetailsTemplate() !!}
</div>


@include('layouts.crud.filters_script')

{!! $dataTable->assets() !!}
{!! $dataTable->scripts() !!}

<script>
    initSelect2Ajax();
</script>
