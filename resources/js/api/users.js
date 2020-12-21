import request from '~/utils/request'

export function fetchList(query) {
  return request({
    url: 'users',
    method: 'get',
    params: query
  })
}

export function blockingUser(userId) {
  return request({
    url: '/users/block',
    method: 'put',
    params: {
      id: userId
    }
  })
}

export function fetchAll() {
  return request({
    url: 'users/all',
    method: 'get'
  })
}

