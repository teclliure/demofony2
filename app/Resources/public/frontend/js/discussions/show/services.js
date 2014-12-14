'use strict';

var services = angular.module('discussionShowApp.services', []);

services.factory('Security', function ($q) {
    return {
        canVoteInProcessParticipation: function(state, is_logged) {
              return $q(function(resolve, reject) {
                if (state === 2 && is_logged) {
                    resolve();
                } else {
                    reject('Vote is not open');
                }
            });
        }
    };
});
