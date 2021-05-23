import { ApiService } from './ApiService';

export class UserService extends ApiService
{
  static get $inject() {
    return ['$http', '$q']
  };
  constructor ($http, $q) {
    super($http, $q);
  }
  getUser(userId) {
    return this.get(`/user/${userId}`);
  }
  getUsers() {
    return this.get(`/users`);
  }
}
