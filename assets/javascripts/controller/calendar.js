angular.module('admin42')

    .filter('exclusiveEndDate', function ($filter) {
        // correct fullcalendar's exclusive end date by using a filter
        var angularDateFilter = $filter('date');
        return function (date, format, allDay) {
            if (allDay) {
                date = moment(date).subtract(1, 'day').format();
            }
            return angularDateFilter(date, format);
        }
    })

    .controller('CalendarController', function ($scope, $attrs, $locale, $timeout, $log, toaster, uiCalendarConfig) {

        $timeout(function () {
            for (var calendar in uiCalendarConfig.calendars) {
                $scope.activeCalendar = calendar;
                break;
            }
        });

        // == fullcalendar

        $scope.eventDrop = function (event, delta, revertFunc, jsEvent, ui, view) {

            $scope.updateEvent(event, delta, revertFunc, jsEvent, ui, view);

            $timeout(function () {

                // TODO: add undo function to toast
                toaster.pop('calendar', event.title, 'Moved Event');
                //revertFunc();
                //$scope.events.map(function(eventModel){
                //    if(eventModel.$$hashKey === event.$$hashKey) {
                //        eventModel.start = event.start;
                //        eventModel.end = event.end;
                //    }
                //});
            });
        };

        $scope.eventResize = function (event, delta, revertFunc, jsEvent, ui, view) {

            $scope.updateEvent(event, delta, revertFunc, jsEvent, ui, view);

            $timeout(function () {

                // TODO: add undo function to toast
                toaster.pop('calendar', event.title, 'Changed Event Duration');
                //revertFunc();
                //$scope.events.map(function(eventModel){
                //    if(eventModel.$$hashKey === event.$$hashKey) {
                //        eventModel.start = event.start;
                //        eventModel.end = event.end;
                //    }
                //});
            });
        };

        $scope.overlay = $('.fc-overlay');
        $scope.eventMouseover = function (event, jsEvent, view) {
            // copy event just for sanitized rollover - otherwise sanitized event will cause errors when dragging
            $scope.event = $scope.sanitizeEventModel(angular.copy(event));
            $scope.overlay.removeClass('left right top').find('.arrow').removeClass('left right top pull-up');
            var wrap = $(jsEvent.target).closest('.fc-event');
            var cal = wrap.closest('.calendar');
            var left = wrap.offset().left - cal.offset().left;
            var right = cal.width() - (wrap.offset().left - cal.offset().left + wrap.width());
            var top = cal.height() - (wrap.offset().top - cal.offset().top + wrap.height());
            if (right > $scope.overlay.width()) {
                $scope.overlay.addClass('left').find('.arrow').addClass('left pull-up')
            } else if (left > $scope.overlay.width()) {
                $scope.overlay.addClass('right').find('.arrow').addClass('right pull-up');
            } else {
                $scope.overlay.find('.arrow').addClass('top');
            }
            if (top < $scope.overlay.height()) {
                $scope.overlay.addClass('top').find('.arrow').removeClass('pull-up').addClass('pull-down')
            }
            (wrap.find('.fc-overlay').length == 0) && wrap.append($scope.overlay);
        };

        // double click to add new event
        $scope.precision = 400;
        $scope.lastClickTime = 0;
        $scope.dayClick = function (date, jsEvent, view) {
            var time = new Date().getTime();
            if (time - $scope.lastClickTime <= $scope.precision) {
                $scope.addEvent(moment(date));
            }
            $scope.lastClickTime = time;
        };

        $scope.uiConfig = {
            calendar: {
                height: 450,
                editable: true,
                header: {
                    left: 'prev',
                    center: 'title',
                    right: 'next'
                },
                //lang: $locale.id.split('-')[0],
                lang: $attrs.activeLocale.split('-')[0],
                //nextDayThreshold: '00:00',
                timezone: 'local', // very essential to correctly preserve timezones between fullcalendar and angular
                dayClick: $scope.dayClick,
                eventDragStart: $scope.eventDragStart,
                eventDrop: $scope.eventDrop,
                eventResize: $scope.eventResize,
                eventMouseover: $scope.eventMouseover,
                viewRender: function (view, element) {
                    //$log.debug("View Changed: ", view.visStart, view.visEnd, view.start, view.end);
                }
            }
        };


        // == ui

        $scope.changeView = function (view) {
            uiCalendarConfig.calendars[$scope.activeCalendar].fullCalendar('changeView', view);
        };

        $scope.renderCalender = function () {
            uiCalendarConfig.calendars[$scope.activeCalendar].fullCalendar('render');
        };

        $scope.today = function () {
            uiCalendarConfig.calendars[$scope.activeCalendar].fullCalendar('today');
        };


        // == crud

        $scope.addEvent = function (momentDate) {

            $log.warn('open modal');

            // add event today by default
            momentDate = momentDate || moment();

            $scope.events.push($scope.sanitizeEventModel({
                title: 'New Event',
                startTimestamp: momentDate.unix(),
                start: momentDate.format(),
                allDay: true,
                stick: true // prevents new events from disappearing when switching views
            }));
        };

        $scope.removeEvent = function (index) {
            $scope.events.splice(index, 1);
        };

        // a wrapper to update angular models properly from fullcalendar events
        // additionaly cleans up dates
        $scope.updateEvent = function (event, delta, revertFunc, jsEvent, ui, view) {
            $scope.events.map(function (eventModel) {
                if (eventModel.$$hashKey === event.$$hashKey) {
                    eventModel.allDay = event.allDay;
                    eventModel.start = event.start;
                    eventModel.end = event.end;
                    $scope.sanitizeEventModel(eventModel);
                }
            });
        };

        $scope.sanitizeEventModel = function(eventModel) {

            eventModel.start = moment(eventModel.start).format();
            eventModel.startTimestamp = moment(eventModel.start).unix();

            if (eventModel.allDay && (
                moment(eventModel.start).format('YYYY-MM-DD') == moment(eventModel.end).subtract(1, 'day').format('YYYY-MM-DD')
                || moment(eventModel.start).format('YYYY-MM-DD') == moment(eventModel.end).format('YYYY-MM-DD'))
                ) {
                eventModel.end = null;
            }

            if (eventModel.end) {
                eventModel.end = moment(eventModel.end).format();
            }

            //eventModel.color = null;

            eventModel.className = [];

            if(eventModel.allDay) {
                //eventModel.className = ['dark']; // dark or light - should match darkness of color
                eventModel.className = ['fc-event-all-day']; // dark or light - should match darkness of color
                eventModel.backgroundColor = '';
            } else {
                eventModel.backgroundColor = '#FFF';
            }

            //if(eventModel.color) {
            //    eventModel.borderColor = eventModel.borderColor ? eventModel.borderColor : eventModel.color;
            //    eventModel.backgroundColor = eventModel.backgroundColor ? eventModel.backgroundColor : eventModel.color;
            //}
            //
            //eventModel.style = '';
            //eventModel.style += eventModel.textColor ? 'color:'+eventModel.textColor+';' : '';
            //eventModel.style += eventModel.borderColor ? 'border-color:'+eventModel.borderColor+';' : '';
            ////eventModel.style += eventModel.backgroundColor ? 'background-color:'+eventModel.backgroundColor+';' : '';
            //

            eventModel.borderColor = eventModel.color;

            eventModel.listStyle = '';
            eventModel.listStyle += eventModel.borderColor ? 'border-color:'+eventModel.borderColor+';' : '';

            return eventModel;
        };

        $scope.eventSources = getTestEventSources();

        function getTestEventSources() {

            var date = new Date();
            var d = date.getDate();
            var m = date.getMonth();
            var y = date.getFullYear();

            // google calendars do not work without api key
            // add gcal, api key and calendar to eventSources array to access google calendars
            //$scope.googleCalendar = {
            //    url: "http://www.google.com/calendar/feeds/usa__en%40holiday.calendar.google.com/public/basic",
            //    className: 'gcal-event',           // an option!
            //    currentTimezone: 'Europe/Vienna' // an option!
            //};

            //{
            //    title: 'All Day',
            //    start: new Date(y, m, 1, 9),
            //    end: new Date(y, m, 1, 21),
            //    allDay: true, // truncates given time - only allDay events my be resized
            //    className: [''],
            //    location: 'New York',
            //    info: 'All day event that will start from 9:00 am to 9:00 pm',
            //    stick: true // prevent event from disappearing when switching views
            //},

            $scope.events = [
                {
                    title: 'All Day Green',
                    start: new Date(y, m, d-4, 9),
                    end: new Date(y, m, d-4, 21), // exclusive
                    allDay: true,
                    location: 'Vienna',
                    info: 'All day event test: time truncated',
                    color: '#27ae60',
                    updateUrl: '', // url to send update to if changing title or confirming add/edit modal
                    deleteUrl: '' // url to send delete action to
                },
                {
                    title: 'Two whole days',
                    start: new Date(y, m, d-5),
                    end: new Date(y, m, d-3), // exclusive
                    allDay: true,
                    location: 'Vienna',
                    info: 'Two whole days',
                    color: '#e74c3c',
                    updateUrl: '', // url to send update to if changing title or confirming add/edit modal
                    deleteUrl: '' // url to send delete action to
                },
                {
                    title: 'Long Event Test',
                    start: moment(new Date(y, m, d - 5, 9, 30)).format('YYYY-MM-DD HH:mm:ssZ'),
                    end: moment().add(1, 'day').format(), // needs to be formatted for angular view
                    allDay: true,
                    location: 'Vienna',
                    info: 'Long event test: times truncated',
                    color: '#e67e22',
                    updateUrl: '', // url to send update to if changing title or confirming add/edit modal
                    deleteUrl: '' // url to send delete action to
                },
                {
                    title: 'Simple',
                    start: moment().format(),
                    info: 'Specific Date & Time without end',
                    color: '#f1c40f',
                    updateUrl: '', // url to send update to if changing title or confirming add/edit modal
                    deleteUrl: '' // url to send delete action to
                }
            ];

            // transform api event list into angular-ui fullcallendar format
            $scope.events.map($scope.sanitizeEventModel);

            return [$scope.events];
        }
    }
);