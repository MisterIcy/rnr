export class Dashboard {
  static get $inject () {
    return ['UserService', 'LeaveService', 'AuthService', '$q', '$location'];
  }
  
  constructor (UserService, LeaveService, AuthService, $q, $location) {
    this.userSvc = UserService;
    this.svc = LeaveService;
    this.auth = AuthService;
    this.isLoading = true;
    this.q = $q;
    this.loc = $location;
    if (this.auth.isAdmin()) {
      $location.path(`/admin`);
      return;
    }
    
    
    this.leaves = [];
    let promises = [
      this.userSvc.getUser(this.auth.getUserId())
        .then((response) => {this.user = response;}),
      this.svc.listLeaves(this.auth.getUserId())
        .then(response => this.leaves = response),
    
    ];
    
    $q.all(promises)
      .then(() => {
      
      })
      .catch((error) => toastr.error(error.message))
      .finally(() => this.isLoading = false);
    
  }
  submitLeave() {
    this.loc.path(`/leave`);
  }
  
}
