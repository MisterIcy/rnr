import { ApiService } from './ApiService';
import jwt_decode from 'jwt-decode';

export class AuthService extends ApiService {
  static get $inject() {
    return ['$http', '$q']
  };
  constructor ($http, $q) {
    super($http, $q);
  }
  
  /**
   * Gets a JWT from LocalStorage (if exists)
   */
  getToken() {
    return localStorage.getItem('auth_token');
  }
  
  /**
   * Validates the JWT if exists
   */
  validateToken() {
    const token = this.getToken();
    if (token === null)
    {
      return false;
    }
    const decoded = jwt_decode(token);
    
    if (decoded.exp * 1000 < Date.now()) {
      //Expired token
      return false;
    }
    //All other validations are performed by the backend.
    return true;
  }
  
  /**
   * Logs in the user
   * @param email User's Email
   * @param password User's Password
   * @param success Success Callback
   * @param failure Error Callback
   */
  login(email, password, success, failure) {
    this.post(`/login`, {email: email, password: password})
      .then( (response) => success(response))
      .catch( (error) => failure(error));
  }
  
  /**
   * Stores the token in local storage
   * @param token
   */
  storeToken(token) {
    localStorage.removeItem('auth_token');
    localStorage.setItem('auth_token', token);
  }
  
  /**
   * Checks if the user is an administrator
   * @return boolean
   */
  isAdmin() {
    const token = this.getToken();
    if (token === null) {
      return false;
    }
    const decoded = jwt_decode(token);
    
    return decoded.data.isAdmin;
  }
  getUserId() {
    const token = this.getToken();
    if (token === null) {
      return null;
    }
    const decoded = jwt_decode(token);
  
    return decoded.data.userId;
  }
  
}
