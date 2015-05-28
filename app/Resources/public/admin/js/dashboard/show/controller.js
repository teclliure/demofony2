'use strict';

angular.module('dashboardApp').controller('VisitsChartCtrl', ['$scope', '$timeout', '$log', '$http', function ($scope, $timeout, $log, $http) {

    $scope.init = function () {
        var now = moment();
        var startOn = moment().subtract(6, 'days');
        var endOn = moment().subtract(0, 'days');
        $scope.date = {startDate: startOn, endDate: endOn};
        console.log($scope.date);
        $scope.opts = {
            format: 'DD-MM-YYYY',
            timePicker: false,
            startDate: startOn,
            endDate: endOn,
            maxDate: '12/31/2015',
            //maxDate: moment().format('DD-MM-YYYY'),
            ranges: {
                'Últims 7 dies': [moment().subtract(6, 'days').format('DD-MM-YYYY'), moment().format('DD-MM-YYYY')],
                'Últims 30 dies': [moment().subtract(29, 'days').format('DD-MM-YYYY'), moment().format('DD-MM-YYYY')],
                'Aquest mes': [moment().startOf('month').format('DD-MM-YYYY'), moment().endOf('month').format('DD-MM-YYYY')],
                'Últim mes': [moment().subtract(1, 'month').startOf('month').format('DD-MM-YYYY'), moment().subtract(1, 'month').endOf('month').format('DD-MM-YYYY')]
            },
            opens: 'right',
            drops: 'down',
            locale: {
                applyLabel: 'Guardar',
                cancelLabel: 'Cancel·lar',
                fromLabel: 'Desde',
                toLabel: 'Fins',
                customRangeLabel: 'Personalitzat',
                daysOfWeek: ['dl', 'dt', 'dc', 'dj', 'dv', 'ds','dj'],
                monthNames: ['gener', 'febrer', 'març', 'abril', 'maig', 'juny', 'juliol', 'agost', 'setembre', 'octubre', 'novembre', 'desembre'],
                firstDay: 1
            }

        };
        moment.locale('ca');
        $scope.chart.draw(startOn.format('DD-MM-YYYY'), endOn.format('DD-MM-YYYY'));
    };

    $scope.transformDatesAndUpdateChart = function () {
        console.log($scope.date);
        var startOn = moment($scope.date.startDate).format('DD-MM-YYYY');
        var endOn = moment($scope.date.endDate).format('DD-MM-YYYY');
        $scope.chart.draw(startOn, endOn);
    };

    $scope.chart = {
        draw: function (startOn, endOn) {
            $http.get(Routing.generate('api_get_visits', {startOn: startOn, endOn: endOn})).success(function (data) {

                if ('day' == data.type) {
                    $scope.chart.drawByDay(data.data);
                } else if ('week' == data.type) {
                    $scope.chart.drawByWeek(data.data);
                } else if ('month' == data.type) {
                    $scope.chart.drawByMonth(data.data);
                } else if ('year' == data.type) {
                    $scope.chart.drawByYear(data.data);
                }
            });

        },
        drawByDay: function (values) {
            $scope.labels = [];
            $scope.series = [];
            var data = [];
            angular.forEach(values, function (value, key) {
                var date = moment(value.date, "YYYYMMDD").format("dddd DD MMMM");
                data.push(value.value);
                $scope.labels.push(date);
            });
            $scope.series = ['Visites'];
            $scope.data = [
                data
            ];
        },
        drawByWeek: function (values) {
            $scope.labels = [];
            $scope.series = [];
            var data = [];
            angular.forEach(values, function (value, key) {
               var from = moment().day(1).week(value.date).format("DD-MM");
               var to = moment().day(7).week(value.date).format("DD-MM");
                $scope.labels.push(from + ' a ' + to);
                data.push(value.value);
            });
            $scope.series = ['Visites'];
            $scope.data = [
                data
            ];
        },
        drawByMonth: function (values) {
            $scope.labels = [];
            $scope.series = [];
            var data = [];
            angular.forEach(values, function (value, key) {
                var date = moment(value.date, "YYYYMM").format("MMMM YYYY");
                data.push(value.value);
                $scope.labels.push(date);
            });
            $scope.series = ['Visites'];
            $scope.data = [
                data
            ];
        },
        drawByYear: function (values) {
            $scope.labels = [];
            $scope.series = [];
            var data = [];
            angular.forEach(values, function (value, key) {
                var date = moment(value.date, "YYYY").format("YYYY");
                data.push(value.value);
                $scope.labels.push(date);
            });
            $scope.series = ['Visites'];
            $scope.data = [
                data
            ];
        }
    };

}]);

angular.module('dashboardApp').controller('ParticipationChartCtrl', ['$scope', '$timeout', '$log', '$http', function ($scope, $timeout, $log, $http) {

    $scope.init = function () {
        var now = moment();
        var startOn = moment().subtract(6, 'day');
        var endOn = moment().subtract(0, 'day');
        $scope.date = {startDate: startOn, endDate: endOn};
        $scope.opts = {
            format: 'DD-MM-YYYY',
            timePicker: false,
            startDate: startOn,
            endDate: endOn,
            maxDate: '12/31/2015',
            //maxDate: moment().format('DD-MM-YYYY'),
            ranges: {
                'Últims 7 dies': [moment().subtract(6, 'days').format('DD-MM-YYYY'), moment().format('DD-MM-YYYY')],
                'Últims 30 dies': [moment().subtract(29, 'days').format('DD-MM-YYYY'), moment().format('DD-MM-YYYY')],
                'Aquest mes': [moment().startOf('month').format('DD-MM-YYYY'), moment().endOf('month').format('DD-MM-YYYY')],
                'Últim mes': [moment().subtract(1, 'month').startOf('month').format('DD-MM-YYYY'), moment().subtract(1, 'month').endOf('month').format('DD-MM-YYYY')]
            },
            opens: 'right',
            drops: 'down',
            locale: {
                applyLabel: 'Guardar',
                cancelLabel: 'Cancel·lar',
                fromLabel: 'Desde',
                toLabel: 'Fins',
                customRangeLabel: 'Personalitzat',
                daysOfWeek: ['dl', 'dt', 'dc', 'dj', 'dv', 'ds','dj'],
                monthNames: ['gener', 'febrer', 'març', 'abril', 'maig', 'juny', 'juliol', 'agost', 'setembre', 'octubre', 'novembre', 'desembre'],
                firstDay: 1
            }

        };
        moment.locale('ca');
        $scope.chart.draw(startOn.format('DD-MM-YYYY'), endOn.format('DD-MM-YYYY'));
    };

    $scope.transformDatesAndUpdateChart = function () {
        var startOn = moment($scope.date.startDate).format('DD-MM-YYYY');
        var endOn = moment($scope.date.endDate).format('DD-MM-YYYY');
        $scope.chart.draw(startOn, endOn);
    };

    $scope.chart = {
        draw: function (startOn, endOn) {
            $http.get(Routing.generate('api_get_participation', {startOn: startOn, endOn: endOn})).success(function (data) {

                if ('day' == data.type) {
                    $scope.chart.drawByDay(data.data);
                } else if ('week' == data.type) {
                    $scope.chart.drawByWeek(data.data);
                } else if ('month' == data.type) {
                    $scope.chart.drawByMonth(data.data);
                } else if ('year' == data.type) {
                    $scope.chart.drawByYear(data.data);
                }
            });

        },
        drawByDay: function (values) {
            $scope.labels = [];
            $scope.series = [];
            var comments = [];
            var votes = [];
            var proposals = [];
            var total = [];

            angular.forEach(values, function (value, key) {
                var date = moment(value.date, "YYYYMMDD").format("dddd DD MMMM");
                comments.push(value.comments);
                votes.push(value.votes);
                proposals.push(value.proposals);
                total.push(value.total);
                $scope.labels.push(date);
            });
            $scope.series = ['Comentaris', 'Vots', 'Núm. Digues la teva', 'Total'];
            $scope.data = [
                comments,
                votes,
                proposals,
                total
            ];
        },
        drawByWeek: function (values) {
            $scope.labels = [];
            $scope.series = [];
            var comments = [];
            var votes = [];
            var proposals = [];
            var total = [];

            angular.forEach(values, function (value, key) {
               var from = moment().day(1).week(value.date).format("DD-MM");
               var to = moment().day(7).week(value.date).format("DD-MM");
                $scope.labels.push(from + ' a ' + to);
                comments.push(value.comments);
                votes.push(value.votes);
                proposals.push(value.proposals);
                total.push(value.total);
            });
            $scope.series = ['Comentaris', 'Vots', 'Núm. Digues la teva', 'Total'];
            $scope.data = [
                comments,
                votes,
                proposals,
                total
            ];
        },
        drawByMonth: function (values) {
            $scope.labels = [];
            $scope.series = [];
            var comments = [];
            var votes = [];
            var proposals = [];
            var total = [];

            angular.forEach(values, function (value, key) {
                var date = moment(value.date, "YYYYMM").format("MMMM YYYY");
                $scope.labels.push(date);
                comments.push(value.comments);
                votes.push(value.votes);
                proposals.push(value.proposals);
                total.push(value.total);
            });
            $scope.series = ['Comentaris', 'Vots', 'Núm. Digues la teva', 'Total'];
            $scope.data = [
                comments,
                votes,
                proposals,
                total
            ];
        },
        drawByYear: function (values) {
            $scope.labels = [];
            $scope.series = [];
            var comments = [];
            var votes = [];
            var proposals = [];
            var total = [];
            angular.forEach(values, function (value, key) {
                var date = moment(value.date, "YYYY").format("YYYY");
                $scope.labels.push(date);
                comments.push(value.comments);
                votes.push(value.votes);
                proposals.push(value.proposals);
                total.push(value.total);
            });
            $scope.series = ['Comentaris', 'Vots', 'Núm. Digues la teva', 'Total'];
            $scope.data = [
                comments,
                votes,
                proposals,
                total
            ];
        }
    };

}]);
