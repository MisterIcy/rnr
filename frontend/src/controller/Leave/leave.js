export class LeaveController {
  static get $inject() {
    return ['AuthService', 'LeaveService', '$location'];
  }
  constructor (AuthService, LeaveService, $location) {
    this.leave = {};
    this.auth = AuthService;
    this.svc = LeaveService;
    this.loc = $location;
    
    this.today = Date.now();
    this.isLoading = false;
  }
  
  submitLeave() {
    this.isLoading = true;
    //Setup object
    this.leave.requester = {id: this.auth.getUserId()};
    this.leave.status = {id: 1}; //Default status
    this.leave.createdDate = {timestamp : Math.floor(Date.now() / 1000)};
    this.leave.modifiedDate = {timestamp : Math.floor(Date.now() / 1000)};
    
    this.svc.submitLeave(this.leave)
      .then( (response) => {
        toastr.success("Your leave was submitted successfully!");
      })
      .catch( (error) => {
        toastr.error(error.message);
      })
      .finally( () => {
        this.isLoading = false;
        this.loc.path(`/`);
      })
    
  }
}
