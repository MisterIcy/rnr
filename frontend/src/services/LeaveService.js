import { ApiService } from './ApiService';

export class LeaveService extends ApiService {
  static get $inject() {
    return ['$http', '$q']
  };
  constructor ($http, $q) {
    super($http, $q);
  }
  listLeaves(userId) {
    return this.get(`/leaves/${userId}`)
  }
  submitLeave(data) {
    return this.post(`/leave`, data);
  }
  
}
