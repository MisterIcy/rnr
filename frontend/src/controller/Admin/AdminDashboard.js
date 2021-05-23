export class AdminDashboard {
  static get $inject () {
    return ['UserService'];
  }
  
  constructor (UserService) {
    
    this.svc = UserService;
    this.svc.getUsers()
      .then((response) => this.users = response)
      .catch(error => toastr.error(error.message));
  }
  editUser(userId) {
  
  }
  
}
