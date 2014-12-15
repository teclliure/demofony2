'use strict';

angular.module('discussionShowApp').controller('MainCtrl', ['CFG', 'uiGmapGoogleMapApi', '$scope', '$timeout', '$routeParams', '$log', 'Restangular', '$q', 'Security', function(CFG, uiGmapGoogleMapApi, $scope, $timeout, $routeParams, $log, Restangular, $q, Security) {

    $scope.init = function(discussion, comments, isLogged, username) {
        $scope.discussion = angular.fromJson(discussion);
        $scope.comments = angular.fromJson(comments);
        $scope.is_logged = isLogged;
        $scope.username = username;
        $scope.canVotePromise = Security.canVoteInProcessParticipation($scope.discussion.state, $scope.is_logged);
        $scope.map = { zoom: CFG.GMAPS_ZOOM };
        $scope.map.options = {
            scrollwheel: true,
            draggable: true,
            maxZoom: 15
        };
        $scope.map.control = {};
        $log.log($scope.discussion);
        $log.log($scope.comments);
    };

    $scope.vote = function(answer) {
        $scope.canVotePromise.then(function() {
            var url = Routing.generate('api_post_processparticipation_answers_vote', { id: $scope.discussion.id, answer_id: answer.id });
            //substring is to resolve a bug between routing.generate and restangular
            var vote = Restangular.all(url.substring(1));

            if (!answer.user_has_vote_this_proposal_answer) {
                var data = {'comment': null};
                vote.post(data).then(function() {
                    answer.votes_count++;
                    answer.user_has_vote_this_proposal_answer = true;
                    $scope.discussion.user_already_vote = true;
                    $scope.discussion.total_votes_count++;
                });

                return;
            }
            vote.remove().then(function() {
                answer.votes_count--;
                answer.user_has_vote_this_proposal_answer = false;
                $scope.discussion.user_already_vote = false;
                $scope.discussion.total_votes_count--;
            });
        }, function() {
            //failed
        });
    };

    $scope.comment = {
        like: function(comment, index) {
            console.log('entra 123');
            $scope.canVotePromise.then(function() {
                var url = Routing.generate('api_post_processparticipation_comments_like', { id: $scope.discussion.id, comment_id: comment.id });
                //substring is to resolve a bug between routing.generate and restangular
                var like = Restangular.all(url.substring(1));
                if (!comment.user_already_like) {
                    like.post().then(function(result) {
                        $scope.comments.comments[index] = result;
                    });

                    return;
                }
                like.remove().then(function(result) {
                    $scope.comments.comments[index] = result;
                });
            });
        },
        unlike: function(comment, index) {
            $scope.canVotePromise.then(function() {
                var url = Routing.generate('api_post_processparticipation_comments_unlike', { id: $scope.discussion.id, comment_id: comment.id });
                //substring is to resolve a bug between routing.generate and restangular
                var like = Restangular.all(url.substring(1));
                if (!comment.user_already_unlike) {
                    like.post().then(function(result) {
                        $scope.comments.comments[index] = result;
                    });

                    return;
                }
                like.remove().then(function(result) {
                    $scope.comments.comments[index] = result;
                });
            });
        },
        post: function (commentTosend, parent) {

            $scope.canVotePromise.then(function() {
                var url = Routing.generate('api_post_processparticipation_comments', { id: $scope.discussion.id});
                var comment = Restangular.all(url.substring(1));
                console.log(commentTosend);
                comment.post(commentTosend).then(function(result) {
                    $scope.comments.comments.push(result);
                        console.log(result);
                });



            });
        },
        put: function (commentTosend) {
            $scope.canVotePromise.then(function() {
                var url = Routing.generate('api_put_processparticipation_comments', { id: $scope.discussion.id, comment_id: commentTosend.id});
                var comment = Restangular.all(url.substring(1));
                var tosend = {title: commentTosend.title, comment: commentTosend.comment};
                comment.customPUT(tosend).then(function(result) {

                });
            });
        }
    };

    uiGmapGoogleMapApi.then(function (maps) {
        // promise done
        $log.log('uiGmapGoogleMapApi loaded', maps);
    });

}]);
