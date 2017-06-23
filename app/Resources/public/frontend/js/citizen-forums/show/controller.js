'use strict';

angular.module('citizenForumsShowApp').controller('MainCtrl', ['CFG', 'uiGmapGoogleMapApi', '$scope', '$timeout', '$routeParams', '$log', 'Restangular', '$q', 'Security', '$http', '$sce', function(CFG, uiGmapGoogleMapApi, $scope, $timeout, $routeParams, $log, Restangular, $q, Security, $http) {

    $scope.init = function(discussion, comments, isLogged, username) {
        $scope.discussion = angular.fromJson(discussion);
        $scope.comments = angular.fromJson(comments);
        $scope.is_logged = isLogged;
        $scope.username = username;
        $scope.canVotePromise = Security.canVoteInProcessParticipation($scope.discussion.state, $scope.is_logged);
        $scope.map = {
            zoom: CFG.GMAPS_ZOOM,
            center: { latitude: discussion.gps.latitude, longitude: discussion.gps.longitude },
            control : {}
        };
        $scope.map.options = { scrollwheel: true, draggable: true, maxZoom: 20 };
        $scope.currentPage = 1;
        $scope.comment.update();
        $scope.fetchProposalAnswersTotalVotesCount();
        $scope.CFG = CFG;
        // Init logs
        $log.log('[init] citizen forum', $scope.discussion);
        $log.log('[init] comments', $scope.comments);
        $log.log('[init] pages', $scope.pages);
        $log.log('[init] Hi from citizen forums' + username);
    };

    $scope.vote = function(answer) {
        $scope.canVotePromise.then(function() {
            var url = Routing.generate('api_post_citizen_forum_answers_vote', { id: $scope.discussion.id, answer_id: answer.id });
            var vote = Restangular.all(url.substring(1)); // substring is to resolve a bug between routing.generate and restangular
            if (!answer.user_has_vote_this_proposal_answer) {
                var data = { comment: null };
                vote.post(data).then(function() {
                    answer.votes_count++;
                    answer.user_has_vote_this_proposal_answer = true;
                    $scope.discussion.user_already_vote = true;
                    $scope.fetchProposalAnswersTotalVotesCount();
                });
            } else {
                vote.remove().then(function () {
                    answer.votes_count--;
                    answer.user_has_vote_this_proposal_answer = false;
                    $scope.discussion.user_already_vote = false;
                    $scope.fetchProposalAnswersTotalVotesCount();
                });
            }
        }, function() {
             $scope.showModal.login();
        });
    };

    $scope.comment = {
        like: function(comment, index) {
             $scope.canVotePromise.then(function() {
                 var url = Routing.generate('api_post_citizen_forum_comments_like', {
                     id: $scope.discussion.id,
                     comment_id: comment.id
                 });
                 var like = Restangular.all(url.substring(1)); // substring is to resolve a bug between routing.generate and restangular
                 if (!comment.user_already_like) {
                     like.post().then(function (result) {
                         $scope.comments.comments[index] = result;
                     });
                 } else {
                     like.remove().then(function (result) {
                         $scope.comments.comments[index] = result;
                     });
                 }
             }, function() {
                 $scope.showModal.login();
             });
        },
        unlike: function(comment, index) {
            $scope.canVotePromise.then(function() {
                var url = Routing.generate('api_post_citizen_forum_comments_unlike', { id: $scope.discussion.id, comment_id: comment.id });
                var like = Restangular.all(url.substring(1)); // substring is to resolve a bug between routing.generate and restangular
                if (!comment.user_already_unlike) {
                    like.post().then(function(result) {
                        $scope.comments.comments[index] = result;
                    });
                } else {
                    like.remove().then(function (result) {
                        $scope.comments.comments[index] = result;
                    });
                }
            }, function() {
                $scope.showModal.login();
            });
        },

        post: function (commentTosend, parent) {
            $scope.canVotePromise.then(function() {
                var url = Routing.generate('api_post_citizen_forum_comments', { id: $scope.discussion.id });
                var comment = Restangular.all(url.substring(1));
                if (parent) {
                    // comment answer
                    commentTosend.parent = parent.id;
                    comment.post(commentTosend).then(function(result) {
                        if (parent.answers === undefined) {
                            parent.answers = {};
                            parent.answers.comments = [];
                        }
                        parent.answers.comments.push(result);
                        result.likes_count = 0;
                        result.unlikes_count = 0;
                        jQuery('#answer-comment-' + parent.id).find('input:text, textarea').val(''); // reset form fields
                        commentTosend.title = undefined;
                        commentTosend.comment = undefined;
                    });
                } else {
                    // base answer
                    comment.post(commentTosend).then(function(result) {
                        result.likes_count = 0;
                        result.unlikes_count = 0;
                        $scope.comments.comments.unshift(result);
                        jQuery('#top-level-comments-form').find('input:text, textarea').val(''); // reset form fields
                        commentTosend.title = undefined;
                        commentTosend.comment = undefined;
                    });
                }
            }, function() {
                $scope.showModal.login();
            });
        },
        put: function (commentTosend) {
            //$log.log('comment put log');
            $scope.canVotePromise.then(function() {
                var url = Routing.generate('api_put_citizen_forum_comments', { id: $scope.discussion.id, comment_id: commentTosend.id });
                var comment = Restangular.all(url.substring(1));
                var tosend = { title: commentTosend.title, comment: commentTosend.comment };
                comment.customPUT(tosend).then(function() { // avoid unused function parameter function(result)
                });
            }, function() {
                $scope.showModal.login();
            });
        },
        showAnswerCommentForm: function (id) {
            jQuery('#answer-comment-' + id).toggleClass('hide');
        },
        getListLevel1: function (page) {
            $http.get(Routing.generate('api_get_citizen_forum_comments', { id: $scope.discussion.id, page: page }, false)).success(function (data) {
                $scope.comments = data ;
                $scope.comment.update();
                $scope.currentPage = page;
            });
        },
        getAnswers: function (comment) {
            $http.get(Routing.generate('api_get_citizen_forum_comments_childrens', { id: $scope.discussion.id, comment_id: comment.id }, false)).success(function (data) {
                comment.answers = data;
                $scope.disableShowAnswersButton = true;
                $log.log('[getAnswers]', comment);
            });
        },
        update: function () {
            $scope.pages = Math.ceil($scope.comments.count / 10);
        },
        checkIfPostIsAvailable: function () {
            $scope.canVotePromise.then(function() {
                // no business logic
            }, function() {
                $scope.showModal.login();
            });
        }
    };

    $scope.showModal = {
        login: function() {
            if (!$scope.is_logged) {
                jQuery('#login-modal-form').modal({ show: true });
            }
        }
    };

    $scope.range = function(n) {
        return new Array(n);
    };

    $scope.getUserProfileUrl = function(username) {
        return Routing.generate('fos_user_profile_public_show_comments', { username: username });
    };

    $scope.fetchProposalAnswersTotalVotesCount = function() {
        if ($scope.discussion) {
            var total = 0;
            for (var i = 0; i < $scope.discussion.proposal_answers.length; i++) {
                total += $scope.discussion.proposal_answers[i].votes_count;
            }
            if (total === 0) {
                total = 0.001;
            }
            $scope.discussion.proposal_answers.total_votes = total;
        }
    };

    //$scope.refreshGMap = function() {
        //$log.log('[refreshGMap 1]', $scope.gMap);
        //$log.log('[refreshGMap 2]', $scope.map.control.getGMap());
        //$timeout(function() {
        //    $log.log('delay');
        //    google.maps.event.trigger($scope.map.control.getGMap(), 'resize');
        //}, 1000);

        //$scope.$apply(function () {
        //    google.maps.event.trigger($scope.map.control.getGMap(), 'resize');
        //});
    //};

    //uiGmapGoogleMapApi.then(function(map) { // avoid unused function parameter function(maps)
    //    //$log.log('[uiGmapGoogleMapApi]', map);
    //    $scope.gMap = map;
    //});

}]);
