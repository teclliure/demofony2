'use strict';

angular.module('discussionShowApp', [
        'discussionShowApp.services',
        'ngCookies',
        'ngResource',
        'ngSanitize',
        'ngRoute',
        'uiGmapgoogle-maps',
        'xeditable',
        'restangular'

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

'use strict';

angular.module('discussionShowApp').controller('MainCtrl', ['CFG', 'uiGmapGoogleMapApi', '$scope', '$timeout', '$routeParams', '$log', 'Restangular', '$q', 'Security', '$http', function(CFG, uiGmapGoogleMapApi, $scope, $timeout, $routeParams, $log, Restangular, $q, Security, $http) {

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
        $log.log('[init] discussion', $scope.discussion);
        $log.log('[init] comments', $scope.comments);
        $log.log('[init] pages', $scope.pages);
    };

    $scope.vote = function(answer) {
        $scope.canVotePromise.then(function() {
            var url = Routing.generate('api_post_processparticipation_answers_vote', { id: $scope.discussion.id, answer_id: answer.id });
            var vote = Restangular.all(url.substring(1)); // substring is to resolve a bug between routing.generate and restangular
            if (!answer.user_has_vote_this_proposal_answer) {
                var data = { comment: null };
                vote.post(data).then(function() {
                    answer.votes_count++;
                    answer.user_has_vote_this_proposal_answer = true;
                    $scope.discussion.user_already_vote = true;
                    $scope.discussion.total_votes_count++;
                });
            } else {
                vote.remove().then(function () {
                    answer.votes_count--;
                    answer.user_has_vote_this_proposal_answer = false;
                    $scope.discussion.user_already_vote = false;
                    $scope.discussion.total_votes_count--;
                });
            }
            $scope.fetchProposalAnswersTotalVotesCount();
        }, function() {
             $scope.showModal.login();
        });
    };

    $scope.comment = {
        like: function(comment, index) {
             $scope.canVotePromise.then(function() {
                 var url = Routing.generate('api_post_processparticipation_comments_like', {
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
                var url = Routing.generate('api_post_processparticipation_comments_unlike', { id: $scope.discussion.id, comment_id: comment.id });
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
                var url = Routing.generate('api_post_processparticipation_comments', { id: $scope.discussion.id });
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
                var url = Routing.generate('api_put_processparticipation_comments', { id: $scope.discussion.id, comment_id: commentTosend.id });
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
            $http.get(Routing.generate('api_get_processparticipation_comments', { id: $scope.discussion.id, page: page }, false)).success(function (data) {
                $scope.comments = data ;
                $scope.comment.update();
                $scope.currentPage = page;
            });
        },
        getAnswers: function (comment) {
            $http.get(Routing.generate('api_get_processparticipation_comments_childrens', { id: $scope.discussion.id, comment_id: comment.id }, false)).success(function (data) {
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
        return Routing.generate('fos_user_profile_public_show', { username: username });
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

'use strict';

var services = angular.module('discussionShowApp.services', []);

services.factory('Security', function($q, $log, CFG) {
    return {
        canVoteInProcessParticipation: function(state, is_logged) {
              return $q(function(resolve, reject) {
                  //$log.log('entra123');
                  if (!is_logged) {
                      //$log.log('!is_logged');
                      reject();
                  } else if (state === CFG.PROCESS_PARTICIPATION_STATE.DEBATE && is_logged) {
                      //$log.log('else if');
                      resolve();
                  } else {
                      //$log.log('else');
                  }
              });
        }
    };
});
