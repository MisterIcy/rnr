export class UserDashboard {

  static get $inject() {
    return ['UserService', '$routeParams'];
  }
  
  constructor (UserService, $routeParams) {
    this.svc = UserService;
    this.userTypes = [
      {
        id: 1,
        name: 'Employee'
      },
      {
        id: 2,
        name: 'Admin'
      }
    ]
    this.user = {};
    if ($routeParams.userId !== undefined) {
      this.svc.getUser($routeParams.userId)
        .then( response => this.user = response)
        .catch( error => toastr.error(error.message));
    }
  }
}
