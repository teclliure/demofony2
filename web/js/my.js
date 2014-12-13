'use strict';

angular.module('discussionShowApp', [
        'ngCookies',
        'ngResource',
        'ngSanitize',
        'ngRoute',
        'uiGmapgoogle-maps'
    ]).config(['$interpolateProvider', function ($interpolateProvider) {
        $interpolateProvider.startSymbol('[[');
        $interpolateProvider.endSymbol(']]');
    }]).config(function(uiGmapGoogleMapApiProvider) {
        uiGmapGoogleMapApiProvider.configure({
//            key: 'AIzaSyB332MhD5g142kIo79ZagVcXUidQwHbWwk', // TODO set Google Maps API key
            v: '3.17',
            language: 'es',
            sensor: false,
            libraries: 'drawing,geometry,visualization'
        });
    })
    .constant('CFG', {
        DELAY: 600,
        RANGE_STEPS: 20,
        GPS_DEFAULT_ZOOM: 14,
        GPS_DEFAULT_POS: { lat: 41.4926867, lng: 2.3613954} // Premià de Mar center (Barcelona)
    })
;

'use strict';

angular.module('discussionShowApp')
    .controller('MainCtrl', ['CFG', 'uiGmapGoogleMapApi', '$scope', '$timeout', '$routeParams', '$log', function (CFG, uiGmapGoogleMapApi, $scope, $timeout, $routeParams, $log) {

        $scope.init = function(discussion, comments) {
            $scope.discussion = angular.fromJson(discussion);
            $scope.comments = angular.fromJson(comments);
        };

        $scope.map = {
            center: { latitude: CFG.GPS_DEFAULT_POS.lat, longitude: CFG.GPS_DEFAULT_POS.lng },
            zoom: CFG.GPS_DEFAULT_ZOOM,
            bounds: {},
            clusterOptions: { gridSize: 80, maxZoom: 20, averageCenter: true, minimumClusterSize: 1, zoomOnClick: false }
        };
        $scope.map.options = {
            scrollwheel: true,
            draggable: true,
            maxZoom: 15
        };
        $scope.map.control = {};

        uiGmapGoogleMapApi.then(function(maps) {
            // promise done
            $log.log('uiGmapGoogleMapApi loaded', maps);
        });

    }]);
