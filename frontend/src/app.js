import angular from 'angular';
import ngRoute from 'angular-route';

import toastr from 'toastr';
import 'toastr/build/toastr.min.css';
import { run } from './run';

import { ApiService } from './services/ApiService';
import { AuthService } from './services/AuthService';
import { UserService } from './services/UserService';
import { LeaveService } from './services/LeaveService';

import { LoginController } from './controller/Login/LoginController';
import { Dashboard } from './controller/Dashboard/dashboard';
import {LeaveController} from './controller/Leave/leave';
import {AdminDashboard} from './controller/Admin/AdminDashboard';
import {UserDashboard} from './controller/User/UserDashboard';

window.toastr = toastr;

angular.module('rnr', [ngRoute]);

/** Register Services */
angular.module('rnr')
  .service('ApiService', ApiService)
  .service('AuthService', AuthService)
  .service('UserService', UserService)
  .service('LeaveService', LeaveService);

/** Register Controllers */
angular.module('rnr')
  .controller('LoginController', LoginController);

/** Config */
angular.module('rnr')
  .config(['$routeProvider', '$locationProvider', ($routeProvider, $locationProvider) => {
    $locationProvider.html5Mode(false);
    $routeProvider.when(`/login`, {
      controller: LoginController,
      controllerAs: 'loginCtrl',
      template: require(`./controller/Login/login.html`),
    })
      .when(`/`, {
        controller: Dashboard,
        controllerAs: 'dashCtrl',
        template: require(`./controller/Dashboard/dashboard.html`),
      })
      .when(`/leave`, {
        controller: LeaveController,
        controllerAs: 'leaveCtrl',
        template: require(`./controller/Leave/leave.html`)
      })
      .when(`/admin`, {
        controller: AdminDashboard,
        controllerAs: 'adminCtrl',
        template: require(`./controller/Admin/admin.html`)
      })
      .when(`/user/:userId?`, {
        controller: UserDashboard,
        controllerAs: 'userCtrl',
        template: require(`./controller/User/user.html`)
      })
      .otherwise(`/`);
  }]);

angular.module('rnr')
  .run(run);

export default angular.module('rnr')
  .name;
