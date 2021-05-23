export class LoginController {
  static get $inject () {
    return ['AuthService', '$location', '$routeParams'];
  }
  
  constructor (AuthService, $location, $routeParams) {
    this.svc = AuthService;
    this.loc = $location;
    this.routeParams = $routeParams;
    this.email = null;
    this.password = null;
    this.loggingIn = false;
    
  }
  
  login () {
    this.loggingIn = true;
    this.svc.login(this.email, this.password, (response) => {
      this.svc.storeToken(response.token);
      this.svc.isAdmin();
      //Do other stuff
      
      this.loggingIn = false;
      // Implementation of next route, due to awkwardness of Approval/Rejection integration
      if (this.routeParams.next !== undefined) {
        const nextPath = this.routeParams.next.substring(
          this.routeParams.next.indexOf('!') + 1,
        );
        this.loc.path(nextPath).search({});
      } else {
        this.loc.path(`/`);
      }
      
    }, (error) => {
      this.loggingIn = false;
      toastr.error(error.message);
    });
  }
}
