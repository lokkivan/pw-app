import request from '~/utils/request'

export function fetchList(query) {
  return request({
    url: 'transactions',
    method: 'get',
    params: query
  })
}

export function fetchLast() {
  return request({
    url: 'transactions/last',
    method: 'get',
  })
}

export function create(form) {
  return request({
    url: 'transactions/create',
    method: 'post',
    params: form
  })
}


