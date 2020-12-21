import axios from 'axios';
import Cookies from 'js-cookie';
import {Message} from 'element-ui';

const service = axios.create({
  baseURL: '/api',
  timeout: 15000
});

service.interceptors.request.use(
  config => {
    config.params = config.params || {};
    config.headers.Authorization = 'Bearer ' + Cookies.get('token');
    return config;
  },
  error => {
    return Promise.reject(error);
  }
);

service.interceptors.response.use(
  (response) => {
    if (typeof (response.data.status) === 'string') {
      Message({
        message: response.data.status,
        type: 'success',
        duration: 5000,
        showClose: true
      });
    } else if (response.data.status === false) {
      Message({
        message: response.data.message,
        type: 'error',
        duration: 5000,
        showClose: true
      });
      return Promise.reject(new Error(response.data.message));
    } else if (response.data.status && response.data.message) {
      Message({
        message: response.data.message,
        type: 'success',
        duration: 5000,
        showClose: true
      });
    }
    return response.data;
  },
  error => {
    if (error.response.data.errors) {
      Message({
        message: error.response.data.message,
        type: 'error',
        duration: 5000,
        showClose: true
      });
    } else if (typeof (error.response.data.status) === 'string') {
      Message({
        message: error.response.data.status,
        type: 'success',
        duration: 5000,
        showClose: true
      });
    } else {
      Message({
        message: error.response.data.message ? error.response.data.message : error.message,
        type: 'error',
        duration: 5000,
        showClose: true
      });
    }
    return Promise.reject(error);
  }
);

export default service;
