export class AdminDashboard {
  static get $inject () {
    return ['UserService', 'AuthService', '$location'];
  }
  
  constructor (UserService, AuthService, $location) {
    
    this.loc = $location
    this.auth = AuthService;
    if (!this.auth.isAdmin()) {
      $location.path(`/`).search({});
    }
    this.isLoading = true;
    this.svc = UserService;
    this.svc.getUsers()
      .then((response) => this.users = response)
      .catch(error => toastr.error(error.message))
      .finally(() => this.isLoading = false);
  }
  
  editUser(id) {
    this.loc.path(`/user/${id}`);
  }
  createUser() {
    this.loc.path(`/user`);
  }
  
}
