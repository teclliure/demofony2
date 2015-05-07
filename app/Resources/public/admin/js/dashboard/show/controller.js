'use strict';

angular.module('dashboardApp').controller('MainCtrl', ['$scope', '$timeout', '$log', '$http', function($scope, $timeout, $log, $http) {

    $scope.init = function() {
        $log.log('[init] pages');
        $scope.date = {startDate: null, endDate: null};
        $scope.opts = {
            format: 'DD-MM-YYYY',
            timePicker: false
        };

        var now = moment();
        var startOn =  moment(now).subtract(7, 'day').format('DD-MM-YYYY');
        var endOn =  moment(now).format('DD-MM-YYYY');
        moment.locale('ca');

        $scope.drawVisitsChart(startOn, endOn);
    };

    $scope.test = function()
    {
        console.log('entra');
        console.log($scope.date.startDate);
        var startOn = moment($scope.date.startDate).format('DD-MM-YYYY');
        console.log(startOn);
        //$scope.dr
    };

    $scope.drawVisitsChart = function(startOn, endOn)
    {
       var start =  moment(startOn, "DD-MM-YYYY");
       var end=  moment(endOn, "DD-MM-YYYY");
       $http.get(Routing.generate('api_get_visits', {startAt: startOn, endAt: endOn})).success(function (data) {
            if('day' == data.type) {
                $scope.drawVisitsChartByDay(data.data)
            }
        });
    };

    $scope.drawVisitsChartByDay = function(values)
    {
        $scope.labels = [];
        $scope.series = [];
        $scope.data = [];
        angular.forEach(values, function(value, key) {
            var date = moment(value.date, "YYYYMMDD").format("dddd DD MMMM");
            $scope.data.push(value.value);
            $scope.labels.push(date);
        });

        console.log($scope.data);

        //$scope.labels = ["January", "February", "March", "April", "May", "June", "July"];
        $scope.series = ['Visites'];
        $scope.data = [
            $scope.data
        ];
        console.log($scope.data);

    };
}]);
