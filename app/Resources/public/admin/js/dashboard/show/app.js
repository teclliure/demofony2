'use strict';

angular.module('dashboardApp', [
        'chart.js','daterangepicker']).config(['$interpolateProvider', function($interpolateProvider) {
        $interpolateProvider.startSymbol('[[');
        $interpolateProvider.endSymbol(']]');
    }]);
