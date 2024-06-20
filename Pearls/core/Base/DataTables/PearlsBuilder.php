<?php

namespace Pearls\Base\DataTables;

use Pearls\Base\Facades\PearlsForm;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\HtmlString;

class PearlsBuilder extends Builder
{
    public $filters;
    public $filters_rendered;
    public $options;
    public $bulk_actions;
    public $bulk_actions_rendered;
    public $resource_url;
    public $row_details_template;

    public function setFilters($filters = [])
    {
        $this->filters = $filters;
        return $this;
    }

    public function setOptions($options = [])
    {
        $this->options = $options;
        return $this;
    }

    public function setRowDetailsTemplate($template)
    {

        $this->row_details_template = $template;

        return $this;
    }

    public function setBulkActions($actions = [])
    {
        $this->bulk_actions = $actions;
        return $this;
    }

    public function setTableId(string $id): static
    {
        return parent::setTableId($id);
    }

    /**
     * @return string
     * @throws \Exception
     */


    public function bulkActions()
    {
        if (isset($this->bulk_actions_rendered)) {
            return $this->bulk_actions_rendered;
        }
        $this->bulk_actions_rendered = $this->renderBulkActions();
        return $this->bulk_actions_rendered;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function renderBulkActions()
    {

        $bulk_actions = $this->bulk_actions;

        $tableId = $this->getTableAttribute('id');

        if (!$bulk_actions) {
            $this->bulk_actions_rendered = "";
            return $this->bulk_actions_rendered;
        }
        $action_links = "";
        foreach ($bulk_actions as $bulk_action_key => $bulk_action) {
            if ($bulk_action['permission']) {
                if (!user()->hasPermissionTo($bulk_action['permission'])) {
                    continue;
                }

                $confirmation = "";
                if ($bulk_action['confirmation']) {
                    $confirmation = ' data-confirmation="' . $bulk_action['confirmation'] . '" ';
                }
                $action_links = '<li><a href="' . $this->resource_url . '" ' . $confirmation . ' data-action="' . $bulk_action_key . '">' . $bulk_action['title'] . '</a></li>';

            }

        }

        $actions = ' 
                <div class="btn-group" id="bulk_actions_' . $tableId . '" data-table="' . $tableId . '">
                  <button type="button" class="btn btn-primary">' . trans('Pearls::labels.actions') . '</button>
                  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu" role="menu">';
        $actions .= $action_links;

        $actions .= ' </ul>
                </div>';


        return $actions;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function filters()
    {

        if (isset($this->filters_rendered)) {
            return $this->filters_rendered;
        }

        $this->filters_rendered = $this->renderFilters();
        return $this->filters_rendered;
    }


    /**
     * @return string
     * @throws \Exception
     */
    public function rowDetailsTemplate()
    {

        $row_details_template = $this->row_details_template;
        if ($row_details_template) {
            $tableId = $this->getTableAttribute('id');

            $row_details_template = '<script id="' . $tableId . '-details-template" type="text/x-handlebars-template">
                ' . $row_details_template . '
            </script>';
        }
        return $row_details_template;

    }

    /**
     * @return string
     * @throws \Exception
     */
    private function renderFilters()
    {
        $filtersFields = $this->filters;

        $tableId = $this->getTableAttribute('id');

        $filters = '<div class="filters" data-table="' . $tableId . '" id="' . $tableId . '_filters">';

        $rowColumns = 0;

        foreach ($filtersFields as $key => $field) {
            if (!$field['active']) {
                continue;
            }

            $classArray = explode('-', $field['class']);

            $colNumber = $classArray[count($classArray) - 1];

            if ($rowColumns == 0) {
                $filters .= '<div class="row" >';
            }

            if ($rowColumns > 0 && ($rowColumns + $colNumber) > 12) {
                $rowColumns = 0;
                //row closing
                $filters .= '</div>';
                //start new row
                $filters .= '<div class="row" >';
            }

            $filters .= '<div class="' . $field['class'] . '">';

            $attributes = ['class' => 'filter', 'placeholder' => $field['placeholder'] ?? $field['title']];

            switch ($field['type']) {
                case 'text':
                    $filters .= PearlsForm::text($key, '', false, null, $attributes);
                    break;
                case 'numeric':
                    $filters .= PearlsForm::number($key, '', false, null, $attributes);
                    break;
                case 'date':
                    $filters .= PearlsForm::date($key, '', false, null, $attributes);
                    break;
                case 'date_range':
                    $filters .= PearlsForm::dateRange($key, '', false, null, $attributes);
                    break;
                case 'select':
                    if (isset($field['placeholder'])) {
                        $attributes['placeholder'] = $field['placeholder'];
                    } else {
                        $attributes['placeholder'] = 'Select ' . $field['title'];
                    }

                    $filters .= PearlsForm::select($key, '', $field['options'], false, null, $attributes);
                    break;
                case 'select2':
                    if (isset($field['placeholder'])) {
                        $attributes['data-placeholder'] = $field['placeholder'];
                    } else {
                        $attributes['data-placeholder'] = 'Select ' . $field['title'];
                    }

                    $filters .= PearlsForm::select2($key, '', $field['options'], false, null, $attributes);
                    break;
                case 'select2-ajax':
                    $filters .= PearlsForm::select($key, '', null, false, null, [
                        'class' => 'select2-ajax filter',
                        'placeholder' => 'Select ' . array_get($attributes, 'placeholder'),
                        'data' => [
                            'model' => $field['model'],
                            'columns' => json_encode($field['columns']),
                            'text_columns' => json_encode($field['text_columns'] ?? $field['columns']),
                            'selected' => json_encode([]),
                            'where' => json_encode($field['where'] ?? []),
                        ],
                    ], 'select2');
                    break;
                case 'boolean':
                    $filters .= PearlsForm::checkbox($key, '', false, 1, $attributes);
                    break;
            }
            //col closing
            $filters .= '</div>';

            if (is_numeric($colNumber)) {
                if (($rowColumns + $colNumber) >= 12) {
                    $rowColumns = 0;
                    //row closing
                    $filters .= '</div>';
                } else {
                    $rowColumns += $colNumber;
                }
            }
        }

        if (!empty($filtersFields)) {
            if ($rowColumns + 1 > 12) {
                //row closing
                $filters .= '</div>';
                $rowColumns = 0;
            }
            if ($rowColumns == 0) {
                $filters .= '<div class="row" >';
            }

            $filters .= '<div class="col-md-1 p-r-0"><div class="form-group"> ' .
                \PearlsForm::button('<i class="fa fa-search p-r-0"></i>', ['class' => 'btn btn-primary p-l-10 p-r-10 filterBtn', 'data-table' => $tableId]) .
                \PearlsForm::button('<i class="fa fa-eraser p-r-0"></i></a>', ['class' => 'btn btn-primary p-l-10 p-r-10 clearBtn', 'data-table' => $tableId])
                . '</div></div></div></div>';
        } else {
            $filters = '';
        }

        return $filters;
    }

    /**
     * Add a action column.
     *
     * @param  array $attributes
     * @param  bool  $prepend
     * @return $this
     */
    public function addAction(array $attributes = [], $prepend = false): static
    {
        $options = $this->options;

        if (isset($options['has_action']) && !$options['has_action']) {
            return $this;
        }

        $attributes = array_merge([
            'defaultContent' => '',
            'data' => 'action',
            'name' => 'action',
            'title' => trans('Pearls::labels.action'),
            'render' => null,
            'orderable' => false,
            'searchable' => false,
            'exportable' => false,
            'printable' => true,
            'footer' => '',
        ], $attributes);

        if ($prepend) {
            $this->collection->prepend(new Column($attributes));
        } else {
            $this->collection->push(new Column($attributes));
        }

        return $this;
    }

    /**
     * Add a action column.
     *
     * @param  array $attributes
     * @return $this
     */
    public function addDetails(array $attributes = [])
    {
        $options = $this->options;

        if (!$this->row_details_template) {
            return $this;
        }

        $attributes = array_merge([
            'defaultContent' => '',
            'data' => 'row-details',
            'title' => '',
            'className' => 'details-control',
            'orderable' => false,
            'searchable' => false,
            'exportable' => false,
            'printable' => false,
        ], $attributes);
        $this->collection->prepend(new Column($attributes));

        return $this;
    }

    public function assets()
    {
        $options = $this->options;

        static::DataTableScripts();
        if (isset($options['ordering']) && $options['ordering']) {
            \Assets::add(asset('assets/pearls/plugins/datatables-reorder/dataTables.rowReorder.min.js'));
            \Assets::add(asset('assets/pearls/plugins/datatables-reorder/rowReorder.dataTables.min.css'));
        }

        if ($this->row_details_template) {
            \Assets::add(asset('assets/pearls/plugins/handlebars/handlebars.js'));
        }

    }

    public static function DataTableScripts()
    {
        \Assets::add(asset('assets/pearls/plugins/datatables.net-bs/css/dataTables.bootstrap4.min.css'));
        \Assets::add(asset('assets/pearls/plugins/datatables.net/js/jquery.dataTables.min.js'));
        \Assets::add(asset('assets/pearls/plugins/datatables.net-bs/js/dataTables.bootstrap4.min.js'));
        \Assets::add(asset('assets/pearls/plugins/datatables-buttons/js/dataTables.buttons.min.js'));
        \Assets::add(asset('assets/pearls/plugins/datatables-buttons/js/buttons.bootstrap4.min.js'));
        \Assets::add(asset('assets/pearls/plugins/datatables-buttons/css/buttons.bootstrap4.min.css'));
        \Assets::add(asset('assets/pearls/plugins/datatables.net/js/buttons.server-side.js'));
        \Assets::add(asset('assets/pearls/plugins/handlebars/handlebars.js'));

    }

    /**
     * @param null $script
     * @param array $attributes
     * @return \Illuminate\Support\HtmlString
     * @throws \Exception
     */
    public function scripts($script = null, array $attributes = ['type' => 'text/javascript']): HtmlString
    {
        $tableId = $this->getTableAttribute('id');

        $script = $script ?: $this->generateScripts();

        $options = $this->options;
        if ($this->bulkActions()) {
            $script .= "
            $(document).on('change', '#{$tableId} .datatable-check-all', function(event){
                if($(this).prop('checked')){
                    $('#{$tableId} .datatable-row-checkbox').prop('checked',true).iCheck('update');
                }else{
                    $('#{$tableId} .datatable-row-checkbox').prop('checked',false).iCheck('update');
                }
            });
            
            $(document).on('change', '#{$tableId} .datatable-row-checkbox', function(event){
                var checkboxes = $('#{$tableId} .datatable-row-checkbox');
                
                if (checkboxes.length == checkboxes.filter(':checked').length) {
                    $('#{$tableId} .datatable-check-all').prop('checked', 'checked').iCheck('update');
                } else {
                    $('#{$tableId} .datatable-check-all').prop('checked', false).iCheck('update');
                }
            });
            
            $(document).on('click', '#bulk_actions_{$tableId} a', function(event){
                event.preventDefault();
                var confirmation_message = $(this).data('confirmation');
                var action = $(this).data('action');
				if(confirmation_message){
					themeConfirmation(
						pearls.confirmation.title,
						confirmation_message,
						'warning',
						pearls.confirmation.yes,
						pearls.confirmation.cancel,
						function () {
                            do_bulk_action_{$tableId}(action);
                        }
					)
            
				}else{
                     do_bulk_action_{$tableId}(action);
				}  
			});
         
        function do_bulk_action_{$tableId}(action , data_table ){

            checked_ids = $('#$tableId tbody input:checkbox:checked').map(function () {
                return $(this).val();
            }).get();

        
            $.ajax({
                url: '" . $options['resource_url'] . "/bulk-action',
                type: 'POST',
                data: { selection:  JSON.stringify(checked_ids) , action : action , _token: '" . csrf_token() . "'},
                dataType: 'json',
                success: function (json) {
                $('#$tableId').DataTable().ajax.reload(); // now refresh datatable
                $.each(json, function (key, msg) {
                    themeNotify(msg);
                });
            }
            });
        } 
            
            ";
        }


        if ($this->row_details_template) {


            $script .= "    
            var template = Handlebars.compile($('#{$tableId}-details-template').html());
                // Add event listener for opening and closing details
                var table = $('#{$tableId}').DataTable();

                $('#{$tableId} tbody').on('click', 'td.details-control', function () {
                    var tr = $(this).closest('tr');
                    var row = table.row( tr );
            
                    if ( row.child.isShown() ) {
                        // This row is already open - close it
                        row.child.hide();
                        tr.removeClass('shown');
                    }
                    else {
                        // Open this row
                        row.child( template(row.data()) ).show();
                        tr.addClass('shown');
                    }
                });";
        }
        if (isset($options['ordering']) && $options['ordering']) {
            $script .= "
            var table = $('#{$tableId}').DataTable();
            
            table.on('row-reorder', function (e, diff, edit) {
                var orderArray = [];
                for (var i = 0, ien = diff.length; i < ien; i++) {
                    var rowData = table.row(diff[i].node).data();
                    orderArray.push({
                        id: rowData.id,			// record id from datatable
                        position: diff[i].newPosition		// new position
                    });
                }
                var jsonString = JSON.stringify(orderArray);
                $.ajax({
                    url: '" . $options['resource_url'] . "/reorder',
                    type: 'POST',
                    data: jsonString,
                    dataType: 'json',
                    success: function (json) {
                    $('#{$tableId}').DataTable().ajax.reload(); // now refresh datatable
                    $.each(json, function (key, msg) {
                        themeNotify(msg);
                    });
                }
                });
            });";
        }

        return parent::scripts($script, $attributes);
    }

    /**
     * Add a checkbox column.
     * @param array $attributes
     * @param bool $position
     * @return $this|Builder
     * @throws \Exception
     */
    public function addCheckbox(array $attributes = [], bool|int $position = false): static
    {
        if (!$this->bulkActions()) {
            return $this;
        }
        $dataTableId = array_pull($attributes, 'datatable_id');

        $attributes = array_merge([
            'defaultContent' => '',
            'title' => '<input type="checkbox" class="datatable-check-all" id="' . $dataTableId . '_dataTablesCheckbox' . '"/>',
            'data' => 'checkbox',
            'name' => 'checkbox',
            'orderable' => false,
            'searchable' => false,
            'exportable' => false,
            'printable' => true,
            'width' => '10px',
        ], $attributes);
        $column = new Column($attributes);

        if ($position === true) {
            $this->collection->prepend($column);
        } elseif ($position === false || $position >= $this->collection->count()) {
            $this->collection->push($column);
        } else {
            $this->collection->splice($position, 0, [$column]);
        }

        return $this;
    }
}
