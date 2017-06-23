'use strict';

angular.module('citizenForumsShowApp', [
        'citizenForumsShowApp.services',
        'ngCookies',
        'ngResource',
        'ngSanitize',
        'ngRoute',
        'uiGmapgoogle-maps',
        'xeditable',
        'restangular',
        'nl2br'
    ]).config(['$interpolateProvider', function($interpolateProvider) {
        $interpolateProvider.startSymbol('[[');
        $interpolateProvider.endSymbol(']]');

    }]).config(function(uiGmapGoogleMapApiProvider) {
        uiGmapGoogleMapApiProvider.configure({
//            key: '', // TODO set Google Maps API key
            v: '3.17',
            language: 'es',
            sensor: false,
            libraries: 'drawing,geometry,visualization'
        });
    })

    .run(function(editableOptions) {
        editableOptions.theme = 'bs3'; // X-editable form theme
    })

     .constant('CFG', {
        DELAY: 600,
        RANGE_STEPS: 20,
        GMAPS_ZOOM: 14,
        GPS_CENTER_POS: { lat: 41.4926867, lng: 2.3613954}, // Premià de Mar (Barcelona) center
        PROCESS_PARTICIPATION_STATE: { DRAFT: 1, PRESENTATION: 2, DEBATE: 3, CLOSED: 4 }
    })
;

