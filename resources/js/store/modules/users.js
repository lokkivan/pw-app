import * as types from '../mutation-types'
import { fetchList, blockingUser, fetchAll } from "~/api/users";

// state
export const state = {
  listQuery: {
    sort: '+id',
    page: 1,
    total: 0,
    perPage: 10,
    search: undefined,
  },
  users: [],
  isLoading: false,
  allUsers: [],
}

// getters
export const getters = {
  users: state => state.users,
  search: state => state.listQuery.search,
  sort: state => state.listQuery.sort,
  page: state => state.listQuery.page,
  total: state => state.listQuery.total,
  perPage: state => state.listQuery.perPage,
  isLoading: state => state.isLoading,
  allUsers: state => state.allUsers,
}

// mutations
export const mutations = {
  [types.SET_USERS] (state, users) {
    state.users = users
  },
  [types.SET_TOTAL] (state, total) {
    state.listQuery.total = total
  },
  [types.SET_PAGE] (state, page) {
    state.listQuery.page = page
  },
  [types.SET_PER_PAGE] (state, perPage) {
    state.listQuery.perPage = perPage
  },
  [types.SET_IS_LOADING] (state, isLoading) {
    state.isLoading = isLoading
  },
  [types.SET_SEARCH] (state, search) {
    state.listQuery.search = search
  },
  [types.SET_SORT] (state, sort) {
    state.listQuery.sort = sort
  },
  [types.SET_ALL_USERS] (state, allUsers) {
    state.allUsers = allUsers
  },
}

// actions
export const actions = {
  async getUsers ({ commit, state }) {
    return new Promise((resolve, reject) => {
      commit('SET_IS_LOADING', true);
      fetchList(state.listQuery).then((response) => {
        commit(types.SET_USERS, response.data.users.data)
        commit(types.SET_TOTAL, response.data.users.total)
        commit('SET_IS_LOADING', false);
        resolve();
      }).catch(error => {
        commit('SET_IS_LOADING', false);
        reject(error);
      })
    })
  },
  async getAllUsers({ commit}) {
    return new Promise((resolve, reject) => {
      commit('SET_IS_LOADING', true);
      fetchAll().then((response) => {
        commit(types.SET_ALL_USERS, response.data.users)
        commit('SET_IS_LOADING', false);
        resolve();
      }).catch(error => {
        commit('SET_IS_LOADING', false);
        reject(error);
      })
    })
  },
  async blockUser({ commit, dispatch }, userId) {
    return new Promise((resolve, reject) => {
      commit('SET_IS_LOADING', true);
      blockingUser(userId).then(() => {
        dispatch('getUsers')
        commit('SET_IS_LOADING', false);
        resolve();
      }).catch(error => {
        commit('SET_IS_LOADING', false);
        reject(error);
      })
    })
  },
  setPage ({ commit }, page) {
    commit(types.SET_PAGE, page)
  },
  setPerPage ({ commit }, perPage) {
    commit(types.SET_PER_PAGE, perPage)
  },
  setSearch ({ commit }, search) {
    commit(types.SET_SEARCH, search)
  },
  setSort ({ commit }, sort) {
    commit(types.SET_SORT, sort)
  }
}
