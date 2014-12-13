'use strict';

var app =angular.module('discussionShowApp', [
        'discussionShowApp.controllers',
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
