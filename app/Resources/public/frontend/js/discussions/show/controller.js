'use strict';
var app = angular.module('discussionShowApp.controllers', []);

app.controller('MainCtrl', ['CFG', 'uiGmapGoogleMapApi', '$scope', '$timeout', '$routeParams', '$log', 'Restangular', '$q', 'Security',  function (CFG, uiGmapGoogleMapApi, $scope, $timeout, $routeParams, $log, Restangular, $q, Security) {

        $scope.init = function(discussion, comments, isLogged) {
            $scope.discussion = angular.fromJson(discussion);
            $scope.comments = angular.fromJson(comments);
            $scope.is_logged = isLogged;
           $scope.canVotePromise = Security.canVoteInProcessParticipation($scope.discussion.state, $scope.is_logged);


        };

        $scope.voteProposal = function(answer) {
            $scope.canVotePromise.then(function() {
                var url = Routing.generate('api_post_processparticipation_answers_vote', {id: $scope.discussion.id, answer_id: answer.id});
                var vote = Restangular.all(url.substring(1));

                if (!answer.user_has_vote_this_proposal_answer) {

                    //substring is to resolve a bug between routing.generate and restangular
                var data = {'comment':null};

                    vote.post(data).then(function(result){
                        answer.votes_count =  result.votes_count;
                        answer.user_has_vote_this_proposal_answer =  true;
                        $scope.discussion.user_already_vote = true;
                    });
                    return;
                }

                vote.remove().then(function(result){
                    answer.votes_count--;
                    answer.user_has_vote_this_proposal_answer =  false;
                    $scope.discussion.user_already_vote = false;
                });



            }, function() {
                alert('Failed: ');
            });
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
