<div id="blockUIMessage" style="display: none;">
    <div class="message"
         style="margin-left: -100px;height: 80px;position: relative;color: #319DB5;width: 250px;text-align: center;">
    </div>
</div>

<script type="text/javascript">
    function selectViaAjax(element, selected) {
        $.ajax({
            url: '{{ url('utilities/select2') }}',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            dataType: 'json',
            delay: 250,
            data: {
                columns: element.data('columns'),
                model: element.data('model'),
                selected: selected
            },
            success: function (data, textStatus, jqXHR) {
                // create the option and append to Select2
                for (var index in data) {
                    if (data.hasOwnProperty(index)) {
                        var selection = data[index];
                        var option = new Option(selection.text, selection.id, true, true);
                        element.append(option).trigger('change');
                    }
                }
            }
        });
    }

    function initSelect2Ajax() {
        let select2Options = {
            tags: $(this).data('tags') ? $(this).data('tags') : false,
            ajax: {
                url: '{{ url('utilities/select2') }}',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        query: params.term, // search term
                        columns: $(this).data('columns'),
                        textColumns: $(this).data('text_columns'),
                        model: $(this).data('model'),
                        where: $(this).data('where'),
                        orWhere: $(this).data('or_where'),
                        resultMapper: $(this).data('result_mapper'),
                        join: $(this).data('join'),
                        page: params.page
                    };
                },
                processResults: function (data, params) {
                    params.page = params.page || 1;
                    return {
                        results: data,
                        pagination: {
                            more: (params.page * 30) < data.total_count
                        }
                    };
                },
                cache: true
            },
            minimumInputLength: 2,
            placeholder: "Please Select",
            allowClear: true
        };

        $(".select2-ajax").select2(select2Options);

        $(".select2-ajax").each(function () {
            var element = $(this);

            var selected = element.data('selected');

            if (selected.length) {
                selectViaAjax(element, selected);
            }
        });
    }
</script>
