export class ApiService {
  /**
   * Dependency Injection
   * @return {string[]}
   */
  static get $inject () {
    return ['$http', '$q'];
  }
  
  /**
   * Service Constructor
   * @param $http
   * @param $q
   */
  constructor ($http, $q) {
    this.http = $http;
    this.q = $q;
    
    this.setBasePath(API_ENDPOINT);
    
  }
  
  /**
   * Gets the base path of the API
   * @return {string}
   */
  getBasePath () {
    return this.basePath;
  }
  
  /**
   * Sets the base path of the API
   * @param {string} path New API Path
   * @return {self}
   */
  setBasePath (path) {
    //Remove trailing slash. Endpoints CAN have a trailing slash
    if (path.endsWith('/')) {
      path = path.slice(0, -1);
    }
    this.basePath = path;
    return this;
  }
  
  /**
   * Performs an HTTP Request
   * @param {string} method HTTP Method to be Performed
   * @param {string} endpoint API Endpoint, with or without leading slash
   * @param {array|object|null} data Data to be passed along with the request
   * @return {Promise}
   */
  request (method = 'GET', endpoint = null, data = null) {
    if (endpoint === null) {
      throw 'Endpoint must be specified!';
    }
    
    const url = `${this.basePath}` +
    (endpoint.startsWith('/')) ? endpoint : `/${endpoint}`;
    
    return this.http({
      method: method,
      url: url,
      data: data,
    }).then(ApiService.handleSuccess)
      .catch(ApiService.handleFailure);
  }
  
  /**
   * Handles a successful request
   * @param response HTTP Response
   * @return {*}
   */
  static handleSuccess (response) {
    return response.data;
  }
  
  /**
   * Handles a failed request
   * @param response HTTP Response
   */
  static handleFailure (response) {
    throw response.data;
  }
  
  /**
   * Perform a GET Request
   * @param {string} endpoint
   * @return {Promise}
   */
  get (endpoint) {
    return this.request('GET', endpoint);
  }
  
  /**
   * Performs a POST Request
   * @param {string} endpoint
   * @param {array|object} data
   * @return {Promise}
   */
  post (endpoint, data) {
    return this.request('POST', endpoint, data);
  }
  
  /**
   * Performs a PUT Request
   * @param {string} endpoint
   * @param {array|object} data
   * @return {Promise}
   */
  put (endpoint, data) {
    return this.request('PUT', endpoint, data);
  }
  
  /**
   * Performs a PATCH request
   * @param {string} endpoint
   * @param {array|object} data
   * @return {Promise}
   */
  patch (endpoint, data) {
    return this.request('PATCH', endpoint, data);
  }
  
  /**
   * Performs a DELETE Request
   * @param {string} endpoint
   * @return {Promise}
   */
  delete (endpoint) {
    return this.request('DELETE', endpoint);
  }
  
  /**
   * Performs a PROPFIND Request
   * @param {string} endpoint
   * @return {Promise}
   */
  propfind (endpoint) {
    return this.request('PROPFIND', endpoint);
  }
}
