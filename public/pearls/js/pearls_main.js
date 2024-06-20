$(document).ajaxComplete(function () {
    initElements();
});

$(document).ready(function () {
    initElements();
    initSelect2Ajax();
});

$(document).ajaxStart(function (event) {
    var panelEl = $(".panel");

    if (panelEl.closest('.panel').hasClass('no-block-ui')) {
        return false;
    }

    if (panelEl.length === 0) {
        // panelEl = $('body');
        return false;
    }

    $.blockUI(panelEl);
});

$(document).ajaxStop(function () {
    var panelEl = $(".panel");
    if (panelEl.length === 0) {
        panelEl = $('body');
    }
    $.unblockUI(panelEl);
});

let addNewModalTargetField;
let addNewModalTarget;

$('body').on('click', '[data-type=add-new-modal]', function (e) {
    let element = $(this);

    addNewModalTarget = element.data('target');

    addNewModalTargetField = element.data('target_field');

    $(addNewModalTarget).on('hidden.bs.modal', function (e) {
        let $form = $(addNewModalTarget).find('form')[0];
        clearForm($form);
    });
});

function handleAddNewModalResponse(response, $form) {
    $(addNewModalTarget).modal('hide');
    selectViaAjax($(addNewModalTargetField), [response.details.id]);
}

$('body').on('click', '[data-action]', function (e) {
    e.preventDefault();

    var $element = $(this);

    var action = $element.data('action');

    var url = $element.prop('href');

    var page_action = $element.data('page_action');

    var confirmation_message = $element.data('confirmation');

    var table = $element.data('table');

    var requestData = $element.data('request_data');

    if (undefined === requestData) {
        requestData = {};
    }

    if (action === 'delete') {

        themeConfirmation(
            'Are you sure?',
            'You won\'t be able to revert this!',
            'warning',
            'Yes, delete it!',
            'Cancel',
            function () {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function (response, textStatus, jqXHR) {
                        handleAjaxSubmitSuccess(response, textStatus, jqXHR, page_action, table);
                    },
                    error: function (data, textStatus, jqXHR) {
                        themeNotify(data);
                    }
                });
            });

        return;
    }

    if (action === 'load') {
        var load_to = $element.data('load_to');
        $(load_to).load(url);
        if (undefined !== page_action) {
            window[page_action]();
        }
    }
    if (action === 'post' || action === 'get') {
        if (table && !$element.parents(table).length && !page_action) {
            table = undefined;
            page_action = 'site_reload'
        }
        if (undefined !== confirmation_message) {
            themeConfirmation(
                'Are you sure?',
                confirmation_message,
                'info',
                'Yes, sure!',
                'Close', function () {
                    ajaxRequest(url, requestData, table, page_action, action);
                });
        } else {
            ajaxRequest(url, requestData, table, page_action, action);
        }
    }
});

$('body').on('click', '.ajax-submit', function (e) {
    e.preventDefault();

    $('.summernote').each(function () {
         $(this).text();
    });

    $submit_button = $(this);

    var confirmation_message = $submit_button.data('confirmation');

    var $form = $submit_button.closest('form');

    if (undefined !== confirmation_message) {
        themeConfirmation(
            'Are you sure?',
            confirmation_message,
            'info',
            'Yes, sure!',
            'Close', function () {
                clearFormValidationBeforeSubmit($form);

                ajax_form($form, $submit_button);
            });
    } else {
        clearFormValidationBeforeSubmit($form);

        ajax_form($form, $submit_button);
    }
});

/*
 * Select2 dependency handler
 * The following attributes must be added to the main select:
 *
 * 'class'=>'dependent-select'
 * 'data-dependency-field'=>'field_id',// the target element Id
 * 'data-dependency-args'=>'arg1_id,arg2_id'//any additional fields that their values are required to get the data
 * 'data-dependency-ajax-url'=>url('') //ajax url that handles the dependency
 * */
$('.dependent-select').on('change', function () {
    var thisVal = this.value;
    var thisId = this.id;
    var dependencyArgs = [];

    if ($(this).data('dependency-args')) {
        dependencyArgs = $(this).data('dependency-args').split(',');
    }
    var dependencyFieldId = $(this).data('dependency-field');

    if (!thisVal.length) {
        return;
    }
    var ajaxParams = thisId + "=" + thisVal + "&";

    $.each(dependencyArgs, function (index, arg) {
        argValue = $('#' + arg).val();
        ajaxParams += arg + "=" + argValue + "&";
    });

    var targetUrl = $(this).data('dependency-ajax-url');
    var ajaxUrl = targetUrl + "?" + ajaxParams;

    $.ajax(ajaxUrl,   // request url
        {
            success: function (data, status, xhr) {// success callback function
                var targetElementData = [];

                targetElementData.push({'id': '', 'text': ''});

                $.each(data, function (index) {
                    targetElementData.push({'id': index, 'text': data[index]});
                });
                $("#" + dependencyFieldId).select2().empty().select2({
                    data: targetElementData
                });
            }
        });
});

$(document).on('click', '.modal-load, [data-action="modal-load"]', function (e) {
    e.preventDefault();
    var title = $(this).data('modal_title');

    var modal_class = $(this).data('modal_class');

    var view_url = $(this).attr('href');

    $.get(view_url, function (data) {
        loadContentInGlobalModal(data, title, modal_class)
    });

});

$(document).on('shown.bs.tab', '.nav-tabs a', function (event) {
    let tab = $(event.target);

    let tabContentSelector = tab.attr('href');

    let url = tab.data('content_url');

    let loaded = tab.data('content_loaded');

    if (loaded || !url) {
        return false;
    }

    $.get(url, {tab_html: true}, function (data, textStatus, jqXHR) {
        $(tabContentSelector).html(data);
        tab.data('content_loaded', true);
    });
});
