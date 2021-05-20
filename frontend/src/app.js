import angular from 'angular';
import ngRoute from 'angular-route';
import { ApiService } from './services/ApiService';

angular.module('rnr', [ngRoute]);

/** Register Services */
angular.module('rnr')
  .service('ApiService', ApiService)

export default angular.module('rnr')
  .name;
