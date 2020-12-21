import * as types from '../mutation-types'
import { fetchList, create, fetchLast} from "~/api/transactions";

// state
export const state = {
  listQuery: {
    sort: '+id',
    page: 1,
    total: 0,
    perPage: 10,
    dateRange: [],
    search: undefined,
  },
  transactions: [],
  isLoading: false,
  lastTransactions: [],
}

// getters
export const getters = {
  transactions: state => state.transactions,
  sort: state => state.listQuery.sort,
  search: state => state.listQuery.search,
  page: state => state.listQuery.page,
  total: state => state.listQuery.total,
  perPage: state => state.listQuery.perPage,
  isLoading: state => state.isLoading,
  lastTransactions: state => state.lastTransactions,
  dateRange: state => state.listQuery.dateRange,
}

// mutations
export const mutations = {
  [types.SET_TRANSACTIONS] (state, transactions) {
    state.transactions = transactions
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
  [types.SET_LAST_TRANSACTIONS] (state, lastTransactions) {
    state.lastTransactions = lastTransactions
  },
  [types.SET_DATE_RANGE] (state, dateRange) {
    state.listQuery.dateRange = dateRange
  },
}

// actions
export const actions = {
  async getTransactions ({ commit, state }) {
    return new Promise((resolve, reject) => {
      commit('SET_IS_LOADING', true);
      fetchList(state.listQuery).then((response) => {
        commit(types.SET_TRANSACTIONS, response.data.transactions.data)
        commit(types.SET_TOTAL, response.data.transactions.total)
        commit('SET_IS_LOADING', false);
        resolve();
      }).catch(error => {
        commit('SET_IS_LOADING', false);
        reject(error);
      })
    })
  },

  fetchLastTransactions({ commit }) {
    return new Promise((resolve, reject) => {
      commit('SET_IS_LOADING', true);
      fetchLast().then((response) => {

        commit(types.SET_LAST_TRANSACTIONS, response.data.transactions)

        commit('SET_IS_LOADING', false);
        resolve();
      }).catch(error => {
        commit('SET_IS_LOADING', false);
        reject(error);
      })
    })
  },


  async createTransaction({ commit, dispatch }, form) {
    return new Promise((resolve, reject) => {
      commit('SET_IS_LOADING', true);

      create(form).then(() => {
        // My Transactions
        dispatch('fetchLastTransactions')

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
  },
  setDateRange ({ commit }, dateRange) {
    commit(types.SET_DATE_RANGE, dateRange)
  }
}
