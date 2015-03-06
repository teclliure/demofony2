'use strict';

var services = angular.module('discussionShowApp.services', []);

services.factory('Security', function($q, CFG) {
    return {
        canVoteInProcessParticipation: function(state, is_logged) {
              return $q(function(resolve, reject) {
                  console.log('entra123');
                if (!is_logged) {
                    reject();
                }else if (state === CFG.PROCESS_PARTICIPATION_STATE.DEBATE && is_logged) {
                    resolve();
                }else {

                }
            });
        }
    };
});
