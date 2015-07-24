angular.module('admin42').controller('CalendarController',
    function ($scope, $locale, $timeout, $log, toaster, uiCalendarConfig) {

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
                toaster.pop('calendar', event.title, 'Updated Event Date');
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
                toaster.pop('calendar', event.title, 'Updated Event Duration');
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
            $scope.event = sanitizeEventModel(angular.copy(event));
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
                lang: $locale.id.split('-')[0],
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

            $scope.events.push({
                title: 'New Event',
                startTimestamp: momentDate.unix(),
                start: momentDate.format(),
                allDay: true,
                className: ['b-l b-2x b-primary'],
                stick: true // prevents new events from disappearing when switching views
            });
        };

        $scope.removeEvent = function (index) {
            $scope.events.splice(index, 1);
        };

        // a wrapper to update angular models properly from fullcalendar events
        // additionaly cleans up dates
        $scope.updateEvent = function (event, delta, revertFunc, jsEvent, ui, view) {
            $scope.events.map(function (eventModel) {
                if (eventModel.$$hashKey === event.$$hashKey) {
                    if (event.start) {
                        eventModel.startTimestamp = moment(event.start).unix();
                        eventModel.start = moment(event.start).format();
                    }
                    if (event.end) {
                        eventModel.end = moment(event.end).format();
                    }
                }
            });
        };

        $scope.eventSources = getTestEventSources();

        function sanitizeEventModel(eventModel) {
            eventModel.start = moment(eventModel.start).format();
            if (eventModel.start && !eventModel.startTimestamp) {
                eventModel.startTimestamp = moment(eventModel.start).unix();
            }
            if (eventModel.end) {
                eventModel.end = moment(eventModel.end).format();
            }
            eventModel.className = ['b-l b-2x b-primary'];
            return eventModel;
        }

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
                    title: 'All Day',
                    start: new Date(y, m, 1, 9),
                    end: new Date(y, m, 1, 21),
                    allDay: true,
                    location: 'New York',
                    info: 'All day event test - time truncated'
                },
                {
                    title: 'Two days all day',
                    start: new Date(y, m, 3),
                    end: new Date(y, m, 4),
                    allDay: true,
                    location: 'London',
                    info: 'Two days'
                },
                {
                    title: 'Long Event Test',
                    start: new Date(y, m, d - 5, 9, 30),
                    end: moment().format(), // needs to be formatted for angular view
                    allDay: true,
                    location: 'HD City',
                    info: 'Long event test - time truncated'
                },
                {
                    title: 'Simple',
                    start: new Date(y, m, 28, 13, 30),
                    info: 'Specific Date & Time'
                }
            ];

            // transform api event list into angular-ui fullcallendar format
            $scope.events.map(sanitizeEventModel);

            return [$scope.events];
        }
    }
);