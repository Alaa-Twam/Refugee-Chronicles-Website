/**
 * init elements on page loading and ajax complete
 */
function initThemeElements(is_ajax) {
    $('[data-toggle="tooltip"]').tooltip();
    $('[data-toggle="popover"]').popover();

    $('td .item-actions').addClass('pull-left');

    // inputSelect();
    handleiCheck();

    $('input').on('ifClicked', function (event) {
        $(event.target).click()
    });
    $('input').on('ifChanged', function (event) {
        $(event.target).trigger('change');
    });

    initPlugins(is_ajax);
}

function initPlugins(is_ajax) {
    if (is_ajax) {
    }
    editorSummernote();
    //bDatepicker();
    // inputSelect();
    // inputSelectWithImages();
    // inputSelect2Tags();
    // inputTags();
    // numericStepper();
}

function themeConfirmation(title, text, type, confirm_btn, cancel_btn, callback, dismiss_callback) {
    Swal.fire({
        title: title,
        text: text,
        type: type,
        showCancelButton: true,
        animation: false,
        customClass: 'animated tada',
        confirmButtonColor: "#FF7043",
        confirmButtonText: confirm_btn,
        cancelButtonText: cancel_btn
    }).then((result) => {
    if (result.value) {
            // User clicked confirm, result.value is true
            if (typeof callback === "function") {
                callback();
            }
        } else {
            // User clicked cancel or dismissed the modal
            if (typeof dismiss_callback === "function") {
                dismiss_callback();
            }
        }
    });
}

function themeNotify(data) {
    if (undefined == data.level && undefined == data.message) {
        data = data.responseJSON;
        var level = 'error';
        var message = data.message;
        var errors = data.errors;
    } else {
        var level = data.level;
        var message = data.message;
    }

    if (undefined != errors) {
        message += "<br>";
        $.each(errors, function (key, val) {
            message += val + "<br>";
        });
    }
    if (undefined == level && undefined == message) {
        level = 'error';
        message = 'Something went wrong!!';
    }

    toastr[level](message, ucfirst(level));
}

function set_menu_classes() {
    $('.nav-parent .nav-parent .children').css('display', 'none');

    var items = $(".nav-sidebar").find('.active');

    items.each(function (i) {
        var item = $(this);
        item.closest('li').addClass('active');
        item.closest('.nav-parent').addClass('active');
        item.closest('.children').parent().addClass('active');
        item.closest('ul').css('display', 'block');
    });
}
// Handles custom checkboxes & radios using jQuery iCheck plugin
function handleiCheck() {

    if (!$().iCheck) return;
    $(':checkbox:not(.js-switch, .switch-input, .switch-iphone, .onoffswitch-checkbox, .ios-checkbox, .md-checkbox), :radio:not(.md-radio)').each(function () {

        if ($(this).css('opacity') != "0") {
            var checkboxClass = $(this).attr('data-checkbox') ? $(this).attr('data-checkbox') : 'icheckbox_flat-green';
            var radioClass = $(this).attr('data-radio') ? $(this).attr('data-radio') : 'iradio_minimal-grey';

            if (checkboxClass.indexOf('_line') > -1 || radioClass.indexOf('_line') > -1) {
                $(this).iCheck({
                    checkboxClass: checkboxClass,
                    radioClass: radioClass,
                    insert: '<div class="icheck_line-icon"></div>' + $(this).attr("data-label")
                });
            } else {
                $(this).iCheck({
                    checkboxClass: checkboxClass,
                    radioClass: radioClass
                });
            }
        }
    });
}

function editorSummernote() {
    if ($('.summernote').length && $.fn.summernote) {
        $('.summernote').each(function () {
            $(this).summernote({
                placeholder: 'Write Something hehe ...',
                tabsize: 1,
                height: 400,
                toolbar: [
                  ['style', ['style']],
                  ['font', ['bold', 'underline', 'italic', 'clear']],
                  ['color', ['color', 'forecolor']],
                  ['para', ['paragraph']],
                ]
            });
        });
    }
}