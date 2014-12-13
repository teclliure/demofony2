'use strict';
var app = angular.module('discussionShowApp.controllers', []);

app.controller('MainCtrl', ['CFG', 'uiGmapGoogleMapApi', '$scope', '$timeout', '$routeParams', '$log',  function (CFG, uiGmapGoogleMapApi, $scope, $timeout, $routeParams, $log, $Restangular) {

        $scope.init = function(discussion, comments, isLogged) {
            $scope.discussion = angular.fromJson(discussion);
            $scope.comments = angular.fromJson(comments);
            $scope.is_logged = isLogged;

            $log.log('discussion', $scope.discussion );
            $log.log('is_logged', $scope.is_logged );


        };

        $scope.voteProposal = function(id) {
          console.log('entra 123');
            console.log(id);
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
