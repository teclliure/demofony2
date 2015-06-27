'use strict';

var services = angular.module('proposalShowApp.services', []);

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
