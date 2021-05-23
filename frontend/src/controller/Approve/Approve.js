export class ApproveController {
  static get $inject () {
    return ['AuthService', 'LeaveService', '$routeParams', '$location'];
  }
  
  constructor (AuthService, LeaveService, $routeParams, $location) {
    this.auth = AuthService;
    this.svc = LeaveService;
    
    if (!this.auth.isAdmin()) {
      $location.path(`/`);
      return;
    }
    if ($routeParams.leaveId === undefined) {
      toastr.error('Unspecified leave to approve');
      $location.path(`/`);
      return;
    }
    this.isLoading = true;
    this.svc.approveLeave($routeParams.leaveId)
      .then(response => toastr.success('Leave was approved!'))
      .catch(error => toastr.error(error.message))
      .finally(() => {
        this.isLoading = false;
        $location.path(`/admin`);
      });
    
  }
}
