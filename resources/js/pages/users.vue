<template>
  <el-card shadow="hover">
    <search-bar class="my-3" :module="`users`"/>
    <el-table
      :data="users"
      stripe
      @sort-change="sorting"
      v-loading="isLoading">
      <el-table-column
        prop="id"
        label="Id"
        width="80"
        sortable="custom">
        <template v-slot:default="scope">
          {{ scope.row.id }}
        </template>
      </el-table-column>
      <el-table-column
        prop="name"
        label="Имя"
        sortable="custom">
        <template v-slot:default="scope">
          {{ scope.row.name }}
        </template>
      </el-table-column>
      <el-table-column
        prop="email"
        label="E-Mail"
        sortable="custom">
        <template v-slot:default="scope">
          {{ scope.row.email }}
        </template>
      </el-table-column>
      <el-table-column
        prop="is_blocked"
        label="Is Blocked"
        sortable="custom">
        <template v-slot:default="scope">
          {{ scope.row.is_blocked | isBlockedFilter }}
        </template>
      </el-table-column>
      <el-table-column
        fixed="right"
        label="Operations"
        width="120">
        <template slot-scope="scope">
          <el-button
            @click.native.prevent="blockedRow(scope.$index)"
            type="text"
            size="small">
            {{ scope.row.is_blocked ? 'Unblocked' : 'Blocked' }}
          </el-button>
        </template>
      </el-table-column>
    </el-table>
    <el-pagination
      v-if="total > 0 && total/perPage > 1"
      :current-page="page"
      :total="total"
      :page-size="perPage"
      layout="prev, pager, next"
      :background="true"
      @current-change="handleCurrentChange"
    />
  </el-card>
</template>

<script>
import SearchBar from "../components/SearchBar";
import sortFormat from "../filters"
import {mapGetters} from 'vuex';
import * as moment from "moment";

export default {
  name: 'Users',
  components: {SearchBar},
  middleware: ['auth', 'role:admin'],
  data() {
    return {
      user: {}
    }
  },
  filters: {
    isBlockedFilter(isBlocked) {
      return isBlocked ? 'Blocked' : 'Active'
    },
  },
  computed: mapGetters({
    users: 'users/users',
    total: 'users/total',
    isLoading: 'users/isLoading',
    page: 'users/page',
    perPage: 'users/perPage',
  }),
  methods: {
    async sorting(data) {
      await this.$store.dispatch('users/setSort', sortFormat(data))
      await this.$store.dispatch('users/getUsers')
    },

    blockedRow(index) {
      let user = this.users[index];
      this.$confirm(`Do you really want to ${user.is_blocked ? 'unblock' : 'block' } this user?`, 'Warning', {
        confirmButtonText: 'OK',
        cancelButtonText: 'Cancel',
        type: 'warning'
      }).then(() => {
        this.$store.dispatch('users/blockUser', user.id)
      });
    },

    handleCurrentChange(val) {
      this.$store.dispatch('users/setPage', val);
      this.$store.dispatch('users/getUsers');
    },
  },
  mounted() {
    this.$store.dispatch('users/getUsers')
  }
}
</script>

<style scoped>
.el-pagination {
  margin-top: 15px;
}
</style>
