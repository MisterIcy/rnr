export class LoginController {
  static get $inject() {
    return ['AuthService', '$location', '$routeParams']
  }
  constructor (AuthService, $location, $routeParams) {
    this.svc = AuthService;
    this.loc = $location;
    
    this.email = null;
    this.password = null;
    this.loggingIn = false;
    
  }
  
  login() {
    this.loggingIn = true;
    this.svc.login(this.email, this.password, (response) => {
      this.svc.storeToken(response.token);
      this.svc.isAdmin();
      //Do other stuff
      
      this.loggingIn = false;
      this.loc.path(`/`);
      
    }, (error) => {
      this.loggingIn = false;
      toastr.error(error.message);
    })
  }
}
