function initTabHash() {
    let hash = window.location.hash;

    if (hash.length) {
        let hasURLParameters = hash.indexOf('?');//-1 if not exist
        let indexOfHash = hash.indexOf('#');

        if (hasURLParameters && hasURLParameters > indexOfHash) {
            hash = _.split(hash, '?')[0];
        }

        $('ul.nav a[href="' + hash + '"]').tab('show');
    }

    $('.nav-tabs a').click(function (e) {
        $(this).tab('show');
        window.location.hash = this.hash;
        let scroll = $('body').scrollTop();
        $('html,body').scrollTop(scroll);
    });
}

function clearFormValidationBeforeSubmit(form) {
    $('.has-error .help-block', form).html('');

    $('.form-group', form).removeClass('has-error');

    $('.nav.nav-tabs li a').removeClass('c-red');
}

function refreshDataTable(table) {
    var $table = $(table);
    if (undefined !== table && $table.length) {
        $table.DataTable().ajax.reload();
    } else {
        site_reload();
    }
}

function ucfirst(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

function redirectTo(data) {
    setTimeout(function () {
        window.location.replace(data.url);
    }, 1000);
}

function site_reload() {
    setTimeout(function () {
        location.reload();
    }, 1000);
}

function handleAjaxSubmitError(response, textStatus, jqXHR, $form) {
    if (response.status === 422) {
        var errors = $.parseJSON(response.responseText)['errors'];
        // Iterate through errors object.
        $.each(errors, function (field, message) {
            //console.error(field+': '+message);
            //handle arrays
            if (field.indexOf('.') !== -1) {
                field = field.replace('.', '[');
                //handle multi dimensional array
                for (i = 1; i <= (field.match(/./g) || []).length; i++) {
                    field = field.replace('.', '][');
                }
                field = field + "]";
            }
            var formGroup = $('[name="' + field + '"]', $form).closest('.form-group');
            //Try array name
            if (formGroup.length === 0) {
                formGroup = $('[name="' + field + '[]"]', $form).closest('.form-group');
            }

            // try data-name
            if (formGroup.length === 0) {
                formGroup = $('[data-name="' + field + '"]', $form).closest('.form-group');
            }

            if (formGroup.length === 0) {
                field = field.replace(/[0-9]/, '');
                formGroup = $('[name="' + field + '"]', $form).closest('.form-group');
            }
            // console.log(field);
            // console.log(formGroup);

            var tabIndex = formGroup.closest('.tab-pane').index();
            $form.find('.nav.nav-tabs li').eq(tabIndex).find('a').addClass('c-red');
            formGroup.addClass('has-error').append('<p class="help-block">' + message + '</p>');
        });

    }
    var data = {};
    data.message = $.parseJSON(response.responseText)['message'];
    data.level = 'error';
    themeNotify(data);
    Ladda.stopAll();
}

function handleAjaxSubmitSuccess(response, textStatus, jqXHR, page_action, table, $form) {
    if (response.message) {
        themeNotify(response);
    }

    if (response.action) {
        window[response.action](response, $form);
    }

    if (undefined !== table) {
        refreshDataTable(table);
    }

    if (undefined !== page_action) {
        window[page_action](response, $form);
    }
}

function ajax_form($form, button = null) {
    var page_action = $form.data('page_action');
    var table = $form.data('table');

    var formData = new FormData($form[0]);

    if (!button) {
        button = $('button[name]:focus', $form);
    }

    if (button.length && button.attr('name')) {
        formData.append(button.attr('name'), button.attr('value'));
    }

    var url = $form.attr('action');

    $('.has-error .help-block').html('');

    $form.find('.nav.nav-tabs li a').removeClass('c-red');

    $.ajax({
        url: url,
        type: 'POST',
        data: formData,
        cache: false,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function (response, textStatus, jqXHR) {
            Ladda.stopAll();
            handleAjaxSubmitSuccess(response, textStatus, jqXHR, page_action, table, $form);
        },
        error: function (response, textStatus, jqXHR) {
            handleAjaxSubmitError(response, textStatus, jqXHR, $form);
        }
    });
}

function readURL(area, input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            if (area.find('.preview').length) {
                area.find('.preview').attr('src', e.target.result);
                area.find('.preview').removeClass('hidden');
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}

$(document).on("change", ".upload-file-area", function () {
    var $area = $(this);
    var $input = $("#" + $area.data('input'));

    if ($area.find(".file-name").length) {
        var fileNameSpan = $area.find(".file-name");

        var names = '';

        for (var i = 0; i < $input[0].files.length; ++i) {
            names += $input[0].files[i].name + ' | ';
        }
        names = _.trim(names, ' | ');

        fileNameSpan.text(names);
    }

    readURL($area, $input[0]);
});

function calculateDays(date1, date2, useAbs = true) {
    //Get 1 day in milliseconds
    var one_day = 1000 * 60 * 60 * 24;    // Convert both dates to milliseconds
    var date1_ms = date1.getTime();
    var date2_ms = date2.getTime();    // Calculate the difference in milliseconds
    var difference_ms = date2_ms - date1_ms;        // Convert back to days and return

    var difference_days = Math.round(difference_ms / one_day);

    if (useAbs) {
        return Math.abs(difference_days);
    } else {
        return difference_days;
    }
}

function initElements() {
    $.ajaxSetup({
        beforeSend: function (xhr) {
            xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
        }
    });
    try {
        $('.icp-auto').iconpicker();
    } catch (e) {
    }

    //init tabs hash
    initTabHash();

    if ($('.copy-button').length) {
        var clipboard = new Clipboard('.copy-button');
        clipboard.on('success', function (e) {
            // e.clearSelection();
            var message = {
                level: 'success',
                message: 'Copied to clipboard!'
            };
            themeNotify(message);
        });
    }

    if (window.initFunctions) {
        $.each(window.initFunctions, function (index, element) {
            window[element]();
        });
        window.initFunctions = [];
    }
}

function ajaxRequest(url, requestData, table, page_action, method) {
    $.ajax({
        url: url,
        type: method,
        processData: false,
        contentType: false,
        dataType: 'json',
        data: JSON.stringify(requestData),
        success: function (data, textStatus, jqXHR) {
            handleAjaxSubmitSuccess(data, textStatus, jqXHR, page_action, table);
        },
        error: function (data, textStatus, jqXHR) {
            themeNotify(data);
        }
    });
}

function submitStepOnNext(wizardElement, step, url, wizard_navigator, lastStep = false) {
    $('.has-error .help-block').html('');

    let stepData = $("#" + step).find('input, textarea, select').serializeArray();

    let formData = new FormData();

    $.each(stepData, function (index, element) {
        formData.append(element.name, element.value);
    });

    let $step = $("#" + step);

    $.ajax({
        url: url,
        type: 'POST',
        processData: false,
        contentType: false,
        dataType: 'json',
        data: formData,
        success: function (data, textStatus, jqXHR) {
            Ladda.stopAll();
            if (data.message) {
                themeNotify(data);
            }
            if (data.alertDetails) {
                setWizardAlert(data.alertDetails);
            }
        },
        error: function (response, textStatus, jqXHR) {
            handleAjaxSubmitError(response, textStatus, jqXHR, $step);
        }
    }).done(function (response, textStatus, jqXHR) {
        $("#blockUIMessage .message").html('');
        if (lastStep) {
            handleAjaxSubmitSuccess(response, textStatus, jqXHR);
        } else {
            wizard_navigator.response = response;
            wizard_navigator.canGoNext = true;
            wizardElement.next();
        }
    });
}

function setWizardAlert(alertDetails) {
    let alert = $("#wizard-alert");

    alert.addClass('alert-' + alertDetails.level);

    $("#wizard-alert #alert-level").html(ucfirst(alertDetails.level) + "!");

    $("#wizard-alert #alert-body").html(alertDetails.message);

    alert.fadeIn();

    $("#wizard-alert-close").click(function (event) {
        event.preventDefault();
        alert.fadeOut();
    });
}

function clearForm($form) {
    clearFormValidationBeforeSubmit($form);
    $($form)[0].reset();
    $($form).find("select").select2('val', null);
}

function loadContentInGlobalModal(data, title, modal_class) {
    if (modal_class) {
        $('#global-modal .modal-dialog').removeClass('modal-lg');
        $('#global-modal .modal-dialog').removeClass('modal-sm');
        $('#global-modal .modal-dialog').addClass(modal_class);
    }

    $('#modal-body-global-modal').html(data);
    $('#global-modal .modal-header .modal-title').html(title);

    $('#global-modal').modal();

    $('#global-modal').on('shown.bs.modal', function () {
    });

    $('#global-modal').on('hidden.bs.modal', function () {
        $('#global-modal .modal-body').data('');
    });
}

function updateParentObjectField(parents, parent_type, parentObject) {
    parent_type.change(function () {
        let selectedType = $(this).val();
        let parent = parents[selectedType];

        parentObject.select2('val', null);

        parentObject.data('model', parent['model_class']);
        parentObject.data('columns', parent['columns']);
        parentObject.data('where', parent['where']);
    });
}
