import request from '~/utils/request'

export function login(payload) {
  return request({
    url: 'login',
    method: 'post',
    params: {
      email: payload.email,
      password: payload.password
    }
  })
}

export function logout() {
  return request({
    url: 'logout',
    method: 'post'
  })
}

export function fetchOauthUrl(provider) {
  return request({
    url: `oauth/${provider}`,
    method: 'post'
  })
}

export function registration(form) {
  return request({
    url: 'register',
    method: 'post',
    params: form
  })
}

