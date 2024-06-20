class TimeLine {
    constructor(customConfig = {}) {
        this.languages = {
            en: {
                monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                monthNamesShort: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                dayNames: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
                dayNamesShort: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
                dayNamesMin: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
            }
        };
        this.defaults = {
            element: '',

            legend: '',
            // user defined data array of event [source]
            data: [],
            // data URL
            dataURL: '',

            dataURLAttributes: {},

            startDate: '',

            endDate: '',
            // cell width px
            cellWidth: 40,
            // cell height px
            cellHeight: 25,
            // mouse scroll
            mouseScroll: false,

            mouseDragging: false,

            stickyHeader: true,
            // mouse scroll px
            mouseScrollpx: 120,

            headerWidth: 200,
            // language
            language: 'en',
            // onInit event
            onInit: $.noop,
            // onDestroy event
            onDestroy: $.noop,
        };

        let config = $.extend(this.defaults, customConfig);

        config.lang = this.languages[config.language];

        //attach config to the class
        for (let property in config) {
            this[property] = config[property];
        }

        this.buildData();
    }

    static getLoaderContent(full = true, cellHeight = 40) {
        let loaderContainer = $('<div>', {class: 'ctm-loader-container'});

        loaderContainer.html(`
            <div class="loader">
                <div class="rect1"></div>
                <div class="rect2"></div>
                <div class="rect3"></div>
                <div class="rect4"></div>
                <div class="rect5"></div>
            </div>
            `);

        if (full) {
            loaderContainer
                .css('height', '50px')
                .append($('<h5>', {}).text('Loading'));
        }

        return loaderContainer;
    }

    static removeLoader() {
        $('.ctm-loader-container').remove();
    }

    buildData() {
        let errors = this.validateConfig();

        if (errors.length > 0) {
            let message = "";

            $.each(errors, function (key, val) {
                message += val + "\n";
            });

            themeNotify({message: message, level: 'error'});

            return false;
        }

        //Reset the overview HTML element
        let element = this.element;

        $(element).html('');

        console.log('Start building...');
        let startBuildingTime = performance.now();

        let _self = this;

        $(element).append(TimeLine.getLoaderContent());

        let urlAttributes = $.extend({
            'start_date': TimeLine.getFormattedDate(this.startDate),
            'end_date': TimeLine.getFormattedDate(this.endDate),
        }, this.dataURLAttributes);

        $.ajax({
            url: `${_self.dataURL}`,
            type: 'GET',
            data: $.param(urlAttributes),
            dataType: 'json',
            processData: false,
            contentType: false
        }).success(function (data) {
            let endGettingDataTime = performance.now();
            console.log("Time to Getting data took " + (endGettingDataTime - startBuildingTime) / 1000 + " seconds.")
            _self.data = data;

            // draw the data
            _self.draw();

            let endDrawTime = performance.now();
            console.log("Time to Drawing data took " + (endDrawTime - endGettingDataTime) / 1000 + " seconds.")
        }).error(function (error) {
            console.error(error);
        });
    }

    validateConfig() {
        let errors = [];

        //validate the target DOM element
        if ($(this.element).length === 0) {
            errors.push('Invalid Target Element!: ' + this.element);
        }

        //Validate data url
        if (TimeLine.isBlank(this.dataURL)) {
            errors.push('Invalid Data URL!');
        }

        let validDates = true;
        if (!(this.startDate instanceof Date)) {
            errors.push('Invalid start date!');
            validDates = false;
        }

        if (!(this.endDate instanceof Date)) {
            errors.push('Invalid end date!');
            validDates = false;
        }

        // start date and end date validation
        if (validDates && this.startDate.getTime() >= this.endDate.getTime()) {
            errors.push('Invalid Date Period!');
        }

        return errors;
    }

    draw() {
        this.renderLegends();

        let html = '';

        let element = this.element;

        //Used to draw header, Grids, and the events[reservations]
        html = this.renderContainer();

        $(element).html(html);

        this.renderReservations();

        this.attachEvents(element, this);

        this.tooltipHandler(element);
    }

    reDraw(customConfig) {
        //attach config to the class
        for (let property in customConfig) {
            this[property] = customConfig[property];
        }

        this.buildData();
    }


    //--------------------------------------- RENDERING -----------------------------------------
    renderContainer() {
        let template = $('<div>', {class: 'ctm-container tr-w-' + this.cellWidth + ' tr-h-' + this.cellHeight});
        let templateWrapper = $('<div>', {class: 'ctm-wrapper'});

        let arrowLeft = '<div class="arrow arrow-left"><span class="arrow-icon"></span></div>';
        let arrowRight = '<div class="arrow arrow-right"><span class="arrow-icon"></span></div>';

        // difference between start-date and end-date
        let diffInDays = TimeLine.dateDiffInDays(this.startDate, this.endDate, true, true);
        let totalWidth = this.getTotalWidth(diffInDays);

        let templateTable = $('<table>', {class: 'table-striped', width: totalWidth})
            .css('margin-bottom', '0');

        let templateHeader = this.renderHeader(diffInDays);

        let templateGrid = this.renderGrid(diffInDays, totalWidth);

        templateTable.append(templateHeader).append(templateGrid);

        template.append(templateTable);

        templateWrapper.append(arrowLeft).append(template).append(arrowRight);

        return templateWrapper;
    }

    renderHeader(num) {
        let cellWidth = this.cellWidth;

        let templateHeader = $('<thead>', {class: 'ctm-header'})
        let templateHeaderMonths = $('<tr>');
        let templateHeaderDays = $('<tr>');
        let templateHeaderDaysMin = $('<tr>');

        /*
        * Add the lines names header in the first column
        * */

        templateHeaderMonths.append($('<th>').css('min-width', this.headerWidth).html('&nbsp;'));
        templateHeaderDays.append($('<th>').css('min-width', this.headerWidth).html(this.data.title));
        templateHeaderDaysMin.append($('<th>').css('min-width', this.headerWidth).html(this.data.lines_header));

        this.setConfigDatesHours();

        let startDate = new Date(this.startDate.getTime());

        for (let i = 0; i <= num; i += 1) {
            let templateHeaderDay = $('<th>');
            let templateHeaderDayMin = $('<th>');

            let weekOfDay = startDate.getDay();// which day of the week [0-6] => [sunday-saturday]
            let day = startDate.getDate();//which day of the month [1-30]
            let month = startDate.getMonth() + 1;//which month [1-12]
            let year = startDate.getFullYear();//which year [2018]
            let monthWidth = TimeLine.daysInMonth(month, year) * cellWidth;

            let dayTemplate = $(templateHeaderDay).text(day);

            $(templateHeaderDays).append(dayTemplate);

            let weekOfdayName = this.lang.dayNamesMin[weekOfDay]; //day name [Sa/Su/Mo,...]

            let dayMinTemplate = $(templateHeaderDayMin).text(weekOfdayName);

            $(templateHeaderDaysMin).append(dayMinTemplate);

            let firstMonthIsNotFull = i === 0;

            let firstDay = TimeLine.firstDayMonth(year, month); //first day of the current month [date object]
            let firstDayEndDateMonth = TimeLine.firstDayMonth(
                this.endDate.getFullYear(),
                this.endDate.getMonth() + 1);//last day of the current month [date object]

            startDate.setHours(0, 0, 0, 0);

            let checkFirstDayMonth = TimeLine.isEqual(startDate, firstDay);
            let checkFirstDayMonthEndDate = TimeLine.isEqual(startDate, firstDayEndDateMonth);

            /*
            * if it's the first day in the current month
            * OR
            * the first date in the loop [ since start date may be in the middle of the month]
            * => in this case, we add a month
            * */
            if (checkFirstDayMonth || firstMonthIsNotFull) {
                if (firstMonthIsNotFull) {
                    monthWidth -= ((day - 1) * cellWidth);
                }

                //in the last month, we need to check how many days are included to specify the width
                if (checkFirstDayMonthEndDate) {
                    monthWidth = (this.endDate.getDate() * cellWidth);
                }

                let colSpan = 1;

                if (monthWidth > cellWidth) {
                    colSpan = Math.floor(monthWidth / cellWidth);
                }

                let templateHeaderMonth = $('<th>', {class: 'ctm-header-month', colspan: colSpan});

                let monthName = this.lang.monthNames[month - 1];

                let monthTemplate = $(templateHeaderMonth).text(`${monthName} ${year}`).css({width: monthWidth});

                $(templateHeaderMonths).append(monthTemplate);
            }
            //increment the date
            startDate.setDate(startDate.getDate() + 1);
        }

        templateHeader.append($(templateHeaderMonths));
        templateHeader.append($(templateHeaderDays));
        templateHeader.append($(templateHeaderDaysMin));
        return templateHeader;
    }

    renderGrid(num, totalWidth) {
        let lines = this.data.lines;

        let tbodyScrollWidth = (lines.length * this.cellHeight > 520) ? 10 : 0;

        let templateGrid = $('<tbody>').css('width', totalWidth + tbodyScrollWidth);

        let templateGridRow = [];

        if (!lines.length) {
            let emptyRow = $('<tr>');

            let emptyDiv = $('<div>').css('padding-left', '10px').html('<strong class="text-info">No Records Found</strong>');

            let emptyTd = $('<td>', {
                colspan: num + 2,
            }).css('width', '100%')
                .append(emptyDiv);

            emptyRow.append(emptyTd);

            templateGrid.append(emptyRow);
        } else {
            for (let key in lines) {

                let line = lines[key];

                let startDate = new Date(this.startDate.getTime());

                // to build each location grid only one time
                if (!templateGridRow[line.company_location_code]) {

                    templateGridRow[line.company_location_code] = $('<tr>');

                    for (let i = 0; i <= num; i++) {
                        let formattedDate = TimeLine.getFormattedDate(startDate);

                        let day = TimeLine.addZero(startDate.getDate());
                        let month = TimeLine.addZero(startDate.getMonth() + 1);

                        let templateGridCell = $('<td>', {
                            "data-date": formattedDate
                        }).text(day);

                        if (this.data.special_dates.hasOwnProperty(day + '' + month)) {
                            let specialDate = this.data.special_dates[day + '' + month];
                            templateGridCell.addClass(specialDate.class);
                            templateGridCell.prop('title', specialDate.name);
                        }
                        if (this.data.special_dates.hasOwnProperty(day + '' + month + '' + line.company_location_code)) {
                            let specialDate = this.data.special_dates[day + '' + month + '' + line.company_location_code];
                            templateGridCell.addClass(specialDate.class);
                            templateGridCell.prop('title', specialDate.name);
                        }

                        templateGridRow[line.company_location_code].append(templateGridCell);

                        startDate.setDate(startDate.getDate() + 1);
                    }
                }

                let templateGridRowClone = templateGridRow[line.company_location_code].clone();

                let templateGridLineContent = $('<div>', {
                    class: 'hastip'
                }).data('tooltip', line.tooltip_url)
                    .html(line.formatted_name);

                let templateGridLineCell = $('<td>').css('min-width', this.headerWidth);

                templateGridLineCell.append(templateGridLineContent);

                templateGridRowClone.prepend(templateGridLineCell);

                let reservationHref = `${line.reservation_url}?res_starts_at=`;

                templateGridRowClone.attr('id', line.hashed_id);
                templateGridRowClone.data('reservation_href', reservationHref);
                templateGridRowClone.attr('title', line.name);

                templateGrid.append(templateGridRowClone);
            }
        }

        return templateGrid;
    }

    renderReservations() {
        let lines = this.data.lines;
        let _self = this;

        for (let key in lines) {
            let line;

            line = lines[key];

            let reservations = line.reservations;

            for (let key in reservations) {
                let reservationGroup;

                reservationGroup = reservations[key];

                _self.processReservationHtml(reservationGroup, line);
            }
        }
    }

    processReservationHtml(reservationGroup, line) {
        let $firstTd = null;
        let prevReservationEndDate = null;
        let totalAccumulativeWidth = 0;
        let colSpan = 0;

        for (let index in reservationGroup) {
            TimeLine.logger(line.name, 'Vehicle');
            let reservation = reservationGroup[index];

            let $line_tr = $('#' + line.hashed_id);

            let reservationStartDate = new Date(reservation.starts_at);
            let reservationEndDate = new Date(reservation.ends_at);

            /*
             * if the overview StartDate is greater than the StartDate of the reservation,
            * the rendering starts from overview StartDate
            *
            * if the overview EndDate is less than the endDate of the reservation,
            * the rendering ends at overview EndDate
            * */

            let startDate = moment(reservationStartDate).isBefore(this.startDate, 'day') ? new Date(this.startDate) : reservationStartDate;
            let endDate;


            let shouldBreakAfterCurrentLoop = false;

            if (moment(reservationEndDate).isAfter(this.endDate, 'day')) {
                endDate = new Date(this.endDate);
                endDate.setDate(endDate.getDate() + 1);
                shouldBreakAfterCurrentLoop = true;
            } else {
                endDate = reservationEndDate;
            }

            TimeLine.logger(reservation.code, 'Code');
            TimeLine.logger(startDate, 'startDate');

            TimeLine.logger(endDate, 'endDate');

            let reservationDuration = TimeLine.dateDiffInDays(startDate, endDate, false);
            let reservationDurationDays = TimeLine.dateDiffInDays(startDate, endDate, true, true);

            TimeLine.logger(reservationDuration, 'reservationDuration');
            TimeLine.logger(reservationDurationDays, 'reservationDurationDays');

            if (!$firstTd) {
                let firstTdSelector = '*[data-date="' + TimeLine.getFormattedDate(startDate) + '"]';

                $firstTd = $(firstTdSelector, $line_tr);

                if (!$firstTd.length || reservationDuration <= 0) {
                    return;
                }
                $firstTd.html('');
                $firstTd.removeClass('sp_date bo_date');
                $firstTd.addClass('reserved');
            }

            let reservationDivWidth = this.cellWidth * reservationDuration;

            TimeLine.logger(reservationDivWidth, 'reservationDivWidth:original');

            let reservationDivMargin = 0;

            let reservationStartHourMinutes = TimeLine.getDateHourMinutes(reservationStartDate);

            if (prevReservationEndDate) {
                reservationDivMargin = (reservationStartHourMinutes - TimeLine.getDateHourMinutes(prevReservationEndDate)) * (this.cellWidth / 24)
            }

            prevReservationEndDate = reservationEndDate;

            let reservationDivContent = $('<div>', {
                class: 'hastip',
            }).data('tooltip', reservation.tooltip_url)
                .css("background-color", reservation.color);

            let tempStartDate = startDate;

            tempStartDate.setDate(tempStartDate.getDate() + 1);

            let removedCellsCount = 0;

            for (removedCellsCount; removedCellsCount < reservationDurationDays; removedCellsCount++) {
                let $selector = $('*[data-date="' + TimeLine.getFormattedDate(tempStartDate) + '"]', $line_tr);

                if (!$selector.length) {
                    break;
                }

                $selector.remove();

                tempStartDate.setDate(tempStartDate.getDate() + 1);
            }

            if (startDate === reservationStartDate && reservationDivMargin === 0) {
                reservationDivMargin = (reservationStartHourMinutes * (this.cellWidth / 24));
            }

            if (removedCellsCount !== 0) {
                // === means reservationStartDate is in the overview range
                if (startDate === reservationStartDate) {
                    reservationDivContent.prepend($('<span>')
                        .html(TimeLine.getAmPmHour(reservationStartDate)));
                } else if (removedCellsCount > 2) {
                    // show full datetime when start date not equal
                    reservationDivContent.prepend($('<span>')
                        .html(TimeLine.getDateTimeLabel(reservationStartDate)));
                } else {
                    // show only icon to indicate that the reservation in the past in case cells couldn't fit
                    reservationDivContent.prepend($('<span>', {
                        title: reservation.starts_at
                    }).addClass('fa fa-calendar fa-fw'));
                }

                // === means reservationEndDate is in the overview range
                if (endDate === reservationEndDate) {
                    reservationDivContent.append($('<span>')
                        .html(TimeLine.getAmPmHour(reservationEndDate)));
                } else if (removedCellsCount > 2) {
                    // show full datetime when end date not equal
                    reservationDivContent.append($('<span>')
                        .html(TimeLine.getDateTimeLabel(reservationEndDate)));
                } else {
                    // show only icon to indicate that the reservation in the future in case cells couldn't fit
                    reservationDivContent.append($('<span>', {
                        title: reservation.ends_at
                    }).addClass('fa fa-calendar fa-fw'));
                }

                colSpan += removedCellsCount;
            } else {
                reservationDivContent.append($('<span>').html('&nbsp;'));
                reservationDivContent.append($('<span>', {
                    title: reservation.code
                }).addClass('fa fa-info-circle fa-fw'));
                reservationDivContent.append($('<span>').html('&nbsp;'));
            }
            // reservationDivMargin = Math.floor(reservationDivMargin);
            // reservationDivWidth = Math.floor(reservationDivWidth);
            reservationDivMargin = TimeLine.roundTo(reservationDivMargin, 10);
            reservationDivWidth = TimeLine.roundTo(reservationDivWidth, 10);

            TimeLine.logger(reservationDivMargin, 'reservationDivMargin');
            TimeLine.logger(reservationDivWidth, 'reservationDivWidth:final');

            totalAccumulativeWidth += reservationDivWidth + reservationDivMargin;

            TimeLine.logger(totalAccumulativeWidth, 'totalAccumulativeWidth');

            if (shouldBreakAfterCurrentLoop) {
                let tdWidth = (colSpan + 1) * this.cellWidth;
                if (totalAccumulativeWidth > tdWidth) {
                    reservationDivWidth -= totalAccumulativeWidth - tdWidth;
                }
                TimeLine.logger(tdWidth, 'tdWidth');
            }

            reservationDivContent
                .css('width', reservationDivWidth)
                .css('margin-left', reservationDivMargin);

            $firstTd.append(reservationDivContent);

            if (shouldBreakAfterCurrentLoop) {
                TimeLine.logger(shouldBreakAfterCurrentLoop, 'shouldBreakAfterCurrentLoop');
                break;
            }

            TimeLine.logger('.......................................................');
        }

        let tdWidth = (colSpan + 1) * this.cellWidth;

        TimeLine.logger(line.preparation_hours, 'vehiclePreparationHours');

        if ((tdWidth - totalAccumulativeWidth) >= (parseInt(line.preparation_hours) * this.cellWidth / 24)) {
            let lastDiv = $('<div>', {
                class: 'hastip stres',//has-tooltip start_reservation
                'data-date': TimeLine.getFormattedDate(prevReservationEndDate),
            }).css('width', Math.floor(tdWidth - totalAccumulativeWidth) + 'px').html('&nbsp;');

            $firstTd.append(lastDiv);
        }

        $firstTd.attr('colspan', colSpan + 1);
        $firstTd.css('min-width', tdWidth);
        TimeLine.logger(tdWidth, 'tdWidth');
        TimeLine.logger('============================================================');
    }

    createLegend(title, legendArray, isColor = true) {
        let container = $('<div>', {class: 'my-legend'});
        let legendTitle = $('<div>', {class: 'legend-title'}).text(title);
        let legendScale = $('<div>', {class: 'legend-scale'});
        let legendLabelsList = $('<ul>', {class: 'legend-labels'});

        _.each(legendArray, function (element, key) {
            let legend = null;
            if (isColor) {
                legend = $('<span>').css("background", element);
            } else {
                legend = $('<img>', {src: element});
            }
            let listItemTitle = $('<label>').text(key);

            let listItem = $('<li>').append(legend, listItemTitle);

            legendLabelsList.append(listItem);
        });

        legendScale.append(legendLabelsList);

        container.append(legendTitle, legendScale);

        return container;
    }

    renderLegends() {
        let colorsConfigArray = this.data.colors;
        let AMPMArray = this.data.am_pm;
        let _self = this;

        $(_self.legend).html('');

        _.each(colorsConfigArray, function (colorsArray, title) {
            $(_self.legend).append(_self.createLegend(title, colorsArray));
        });
    }

    attachEvents(el, options) {

        let self = this;
        // sticky header
        if (options.stickyHeader) {
            let stickyH = function () {
                let top = $('.ctm-wrapper', el).offset().top;
                let height = $('.ctm-wrapper', el).height();
                if (top >= $(this).scrollTop() || $(this).scrollTop() >= ((top + height) - 80)) {
                    $('.arrow', el).css('position', 'static');
                } else {
                    $('.arrow', el).css({
                        position: 'relative',
                        top: `${$(this).scrollTop() - top + 50}px`,
                    });
                }
            };
            $(window).scroll(stickyH);
        }

        // scroll page horizontally with mouse wheel
        if (options.mouseScroll) {
            $('.ctm-container', el).on('wheel mousewheel', function (event) {
                if (event.originalEvent.wheelDelta > 0 || event.originalEvent.detail < 0) {
                    this.scrollLeft -= options.mouseScrollpx;
                } else {
                    this.scrollLeft += options.mouseScrollpx;
                }

                event.preventDefault();
            });
        }

        if (options.mouseDragging) {
            // scroll drag
            $('.ctm-container', el)
                .on('mousedown', function (event) {
                    $(this).data('down', true)
                        .data('x', event.clientX)
                        .data('scrollLeft', this.scrollLeft);
                    return false;
                })
                .on('mousemove', function (event) {
                    if ($(this).data('down') === true) {
                        let scroll = ($(this).data('scrollLeft') + $(this).data('x')) - event.clientX

                        if (Math.abs(scroll) > 30) {
                            $(this).addClass('dragging');
                            this.scrollLeft = scroll;
                        } else {
                            $(this).removeClass('dragging');
                        }
                    }
                })
                .on('mouseleave', function (event) {
                    $(this).data('down', false).removeClass('dragging');
                });
        }

        // arrow button click
        $('.arrow', el).on('click mousemove', function (event) {
            let direction = $(this).hasClass('arrow-right');
            let scrollLeft = $('.ctm-container', el).scrollLeft();
            if (direction) {
                $('.ctm-container', el).scrollLeft(scrollLeft + 70);
            } else {
                $('.ctm-container', el).scrollLeft(scrollLeft - 70);
            }
        });

        $('body').on('mouseup', 'td:not(.reserved), .stres', function (event) {
            // let ctmContainer = $('.ctm-container');
            //
            // if (ctmContainer.hasClass('dragging') === true) {
            //     ctmContainer.data('down', false);
            //     ctmContainer.removeClass('dragging');
            //     return false;
            // }
            //
            // e.preventDefault();
            //check mouse left button
            if (event.which !== 1) {
                return false;
            }

            let href_date = $(this).data('date');

            if (href_date && href_date.length) {
                let href = $(this).closest('tr').data('reservation_href') + href_date;

                let modal = TimeLine.startReservationModal(href, self.data.available_types, self.data.colors['Reservation Types']);

                $('body').append(modal);

                $("#start-reservation-modal").modal();
            }
        });

        $("td:not(.reserved), .stres").hover(function (event) {
            let date = $(this).data('date');
            $('*[data-date="' + date + '"]').addClass('ac');// active-day
        }, function (event) {
            let date = $(this).data('date');
            // $('.' + date).removeClass('ac');// active-day
            $('*[data-date="' + date + '"]').removeClass('ac');// active-day
        });
    }

    static startReservationModal(reservationUrl, reservation_types, colors) {
        let links = $('<div>');

        for (let type in reservation_types) {
            let color = colors[type];

            let typeContainer = $('<div>', {
                class: 'reservation-type-block',
            }).css('background-color', color);

            let typeLink = $('<a>', {
                href: reservationUrl + '&type=' + type,
                // target: '_blank',
                class: 'reservation-type-link'
            }).text(reservation_types[type]);

            typeContainer.append(typeLink);

            links.append(typeContainer);
        }

        let renderedLinks = links.html();

        return `
        <div id="start-reservation-modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Select Reservation Type</h4>
            </div>
            <div class="modal-body"><div class="reservation-type-block-container">${renderedLinks}</div></div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>`
    }

    static tooltipView(title, description) {
        return `
<div id="tooltip-modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">${title}</h4>
            </div>
            <div class="modal-body">
                    ${description}
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>`;
    }

    tooltipHandler(element) {
        let el = $(element);

        let cellHeight = this.cellHeight;

        // tooltip mouse enter & leave
        $('.hastip', el).on('mouseup', function (event) {
            if (event.which !== 1 || $(this).data('date')) {
                return;
            }

            let dataUrl = $(this).data('tooltip');

            $(this).append(TimeLine.getLoaderContent(false, cellHeight));

            $.ajax({
                url: `${dataUrl}`,
                type: 'GET',
                dataType: 'json',
                processData: false,
                contentType: false
            }).success(function (tooltipDetails) {
                TimeLine.removeLoader();

                let data = TimeLine.tooltipView(tooltipDetails.title, tooltipDetails.description);

                $('body').append(data);

                $("#tooltip-modal").modal();
            }).error(function (error) {
                console.error(error);
            });
        });

        $(document).on('hidden.bs.modal', '#tooltip-modal, #start-reservation-modal', function (event) {
            TimeLine.removeModals();
        });
    }

    static removeModals() {
        $(document).find('#tooltip-modal').remove();
        $(document).find('#start-reservation-modal').remove();
    }

    getTotalWidth(day) {
        return day === 0 ? 0 : ((day + 1) * this.cellWidth) + this.headerWidth;
    }

    getTotalHeight(len) {
        return len * this.cellHeight;
    }

    setConfigDatesHours() {
        this.startDate.setHours(0, 0, 0, 0);
        this.endDate.setHours(0, 0, 0, 0);
    }

    static firstDayMonth(year, month) {
        return new Date(year, month - 1, 1);
    }

    static lastDayMonth(year, month) {
        return new Date(year, month, 0);
    }

    static daysInMonth(month, year) {
        return new Date(year, month, 0).getDate();
    }

    static getFormattedDate(date) {
        let day = date.getDate();//which day of the month [1-30]
        let month = date.getMonth() + 1;//which month [1-12]
        let year = date.getFullYear();//which year [2018]

        if (day < 10) {
            day = "0" + day;
        }

        if (month < 10) {
            month = "0" + month;
        }

        return year + "-" + month + "-" + day;
    }

    static isEqual(a, b) {
        return a.getTime() === b.getTime();
    }

    static isBlank(string) {
        return (_.isUndefined(string) || _.isNull(string) || string.trim().length === 0);
    }

    //a: Start Date
    //b: End Date
    static dateDiffInDays(date1, date2, withFloor = true, onlyDays = false) {
        let MS_PER_DAY = 1000 * 60 * 60 * 24;

        let a = new Date(date1.getTime());
        let b = new Date(date2.getTime());

        if (onlyDays) {
            a.setHours(0, 0, 0, 0);
            b.setHours(0, 0, 0, 0);
        }

        let utc1 = Date.UTC(a.getFullYear(), a.getMonth(), a.getDate(), a.getHours(), a.getMinutes());
        let utc2 = Date.UTC(b.getFullYear(), b.getMonth(), b.getDate(), b.getHours(), b.getMinutes());
        let diff = (utc2 - utc1) / MS_PER_DAY;

        if (withFloor) {
            return Math.floor(diff);
        } else {
            return TimeLine.roundTo(diff);
        }
    }

    static getAmPm(date) {
        let hours = date.getHours();
        return hours >= 12 ? 'pm' : 'am';
    }

    static addZero(i) {
        if (i < 10) {
            i = "0" + i;
        }
        return i;
    }

    static getAmPmHour(date, separator = "") {
        let hours = TimeLine.addZero(date.getHours());

        let min = TimeLine.addZero(date.getMinutes());

        let am_pm = hours >= 12 ? 'PM' : 'AM';

        hours = hours >= 13 ? (hours - 12) : hours;

        return hours + ":" + min + separator + am_pm;
    }

    static getDateTimeLabel(date) {
        let formattedDate = TimeLine.getFormattedDate(date);

        return formattedDate + ' ' + TimeLine.getAmPmHour(date);
    }

    static getDateHourMinutes(date) {
        let hours = date.getHours();
        let minutes = date.getMinutes() / 60;
        return hours + minutes;
    }

    static logger(message, title = '') {
        return;
        console.log(title, message);
    }

    static roundTo(number, factor = 100) {
        return Math.round(number * factor) / factor;
    }
}
