<template>
  <el-card shadow="hover">
    <el-row>
      <el-col :span="8">
        <div class="analog-form-item">
          <el-input v-model="search" placeholder="Enter the query..." suffix-icon="el-icon-search" @blur="toSearch" @keyup.enter.native="toSearch"></el-input>
        </div>
      </el-col>
      <el-col :span="8" :offset="4">
        <div class="analog-form-item">
          <el-date-picker
            v-model="dateRange"
            type="daterange"
            :clearable="false"
            range-separator="-"
            start-placeholder="От"
            end-placeholder="До"
            format="dd-MM-yyyy"
            :picker-options="pickerOptions"
            @change="toFilter">
          </el-date-picker>
        </div>
      </el-col>
      <el-col :span="2" :offset="2">
        <div class="analog-form-item">
          <el-button
            type="primary"
            @click="toReset"
            icon="el-icon-close"
          >
          </el-button>
        </div>
      </el-col>
    </el-row>
    <el-table
      :data="transactions"
      v-loading="isLoading"
      @sort-change="sorting">
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
        prop="sender"
        label="Sender">
        <template v-slot:default="scope">
          {{ scope.row.sender.name }}
        </template>
      </el-table-column>
      <el-table-column
        prop="recipient"
        label="Recipient">
        <template v-slot:default="scope">
          {{ scope.row.recipient.name }}
        </template>
      </el-table-column>
      <el-table-column
        prop="amount"
        label="Amount"
        sortable="custom">
        <template v-slot:default="scope">
          {{ scope.row.amount }} pw
        </template>
      </el-table-column>
      <el-table-column
        prop="created_at"
        label="Date"
        sortable="custom">
        <template v-slot:default="scope">
          {{ scope.row.created_at | parseDate }}
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
  name: 'Transactions',
  components: {SearchBar},
  middleware: ['auth'],
  metaInfo() {
    return {title: 'Transactions'}
  },
  data() {
    return {
      user: {},
      dateRange: [],
      search: undefined,
      pickerOptions: {
        firstDayOfWeek: 1
      },
    }
  },
  filters: {
    parseDate(date) {
      return moment(date).format('hh:mm DD-MM-YYYY')
    },
  },
  computed: mapGetters({
    transactions: 'transactions/transactions',
    total: 'transactions/total',
    isLoading: 'transactions/isLoading',
    page: 'transactions/page',
    perPage: 'transactions/perPage',
  }),
  methods: {
    async sorting(data) {
      await this.$store.dispatch('transactions/setSort', sortFormat(data))
      await this.$store.dispatch('transactions/getTransactions')
    },
    handleCurrentChange(val) {
      this.$store.dispatch('transactions/setPage', val);
      this.$store.dispatch('transactions/getTransactions');
    },
    toReset() {
      this.$store.dispatch(`transactions/setSearch`, undefined)
      this.search = undefined
      this.$store.dispatch('transactions/getTransactions');
    },
    toSearch() {
      this.$store.dispatch(`transactions/setSearch`, this.search ? this.search : undefined)
      this.$store.dispatch(`transactions/setPage`, 1)
      this.$store.dispatch('transactions/getTransactions');
    },
    async toFilter() {

      let dateFormat = [
        moment(this.dateRange[0]).format('DD-MM-YYYY'), moment(this.dateRange[1]).format('DD-MM-YYYY')
      ];
      this.$store.dispatch(`transactions/setDateRange`, dateFormat)
      await this.$store.dispatch('transactions/getTransactions');
    }
  },
  mounted() {
    this.$store.dispatch('transactions/getTransactions')
  }
}
</script>

<style scoped>
.el-pagination {
  margin-top: 15px;
}
</style>
