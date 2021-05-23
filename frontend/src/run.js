run.$inject = ['AuthService', '$http', '$rootScope', '$location'];

export function run (AuthService, $http, $rootScope, $location) {
  let isLogged = AuthService.validateToken();
  
  // Setup header with JWT
  if (isLogged) {
    $http.defaults.headers.common['Authorization'] = `Bearer: ${AuthService.getToken()}`;
  }
  
  $rootScope.$on('$locationChangeStart', (event, next, current) => {
    
    let isLogged = AuthService.validateToken();
    if (isLogged) {
      $http.defaults.headers.common['Authorization'] = `Bearer: ${AuthService.getToken()}`;
    } else {
      $location.path(`/login`);
    }
    /**
    const restrictedPage = ($location.path() !== '/login');
    
    if (restrictedPage && !auth) {
      $location.path(`/login`).search();
    }*/
  });
}
