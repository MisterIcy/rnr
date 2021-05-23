export class UserDashboard {
  
  static get $inject () {
    return ['UserService', '$routeParams', 'AuthService', '$location'];
  }
  
  constructor (UserService, $routeParams, AuthService, $location) {
    this.loc = $location;
    if (!AuthService.isAdmin()) {
      $location.path(`/`);
      return;
    }
    
    this.svc = UserService;
    this.userTypes = [
      {
        id: 1,
        name: 'Employee',
      },
      {
        id: 2,
        name: 'Admin',
      },
    ];
    this.user = {};
    if ($routeParams.userId !== undefined) {
      
      this.isLoading = true;
      
      this.svc.getUser($routeParams.userId)
        .then(response => this.user = response)
        .catch(error => toastr.error(error.message))
        .finally(() => { this.isLoading = false;});
    }
    
  }
  
  updateUser () {
    
    if (this.user.password !== this.user.confirmPassword) {
      toastr.error('Passwords do not match!');
      return;
    }
    
    this.isLoading = true;
    if (this.user.id !== undefined) {
      this.svc.updateUser(this.user.id, this.user)
        .then(response => this.user = response)
        .catch(error => toastr.error(error.message))
        .finally(() => this.isLoading = false);
    } else {
      this.svc.createUser(this.user)
        .then((response) => this.loc.path(`/user/${response.id}`))
        .catch(error => toastr.error(error.message))
        .finally(() => this.isLoading = false);
    }
  }
}
