<template>
  <el-card shadow="hover">
    <el-row>
      <el-col :span="12">
        <div class="analog-form-item h2">
          Ballance : {{ this.user.account.balance }} PW
        </div>
      </el-col>
      <el-col :span="12">
        <div class="analog-form-item" v-if="user">
          <el-button
            type="primary"
            @click="makeTransaction()"
            style="float: right">
            New transaction
          </el-button>
        </div>
      </el-col>
    </el-row>
    <create-transaction
      :isOpen="isOpenTransactionDialog"
      :users="allUsers"
      @dialog="isOpenTransactionDialog = false"
      :form="form"/>
    <div class="mt-4 mb-3 h4 text-center">
      Last transactions
    </div>
    <el-table
      :data="lastTransactions"
      v-loading="isLoading">
      <el-table-column
        prop="id"
        label="Id"
        width="80">
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
        label="Amount">
        <template v-slot:default="scope">
          {{ (scope.row.sender.id === user.id ? '-' : '') + scope.row.amount }} pw
        </template>
      </el-table-column>
      <el-table-column
        prop="created_at"
        label="Date">
        <template v-slot:default="scope">
          {{ scope.row.created_at | parseDate }}
        </template>
      </el-table-column>
      <el-table-column
        fixed="right"
        label="Operations"
        width="120">
        <template slot-scope="scope">
          <el-button
            @click.native.prevent="repeatTransaction(scope.row)"
            type="text">
            <i class="el-icon-refresh"></i>
          </el-button>
        </template>
      </el-table-column>
    </el-table>
  </el-card>
</template>

<script>
import CreateTransaction from "~/components/modals/CreateTransaction";
import * as moment from "moment";
import { mapGetters } from "vuex";

export default {
  name: "SearchBar",
  middleware: 'auth',
  components: { CreateTransaction },
  data() {
    return {
      search: undefined,
      isOpenTransactionDialog: false,
      form: {
        recipient_id: undefined,
        amount: null,
      },
    }
  },
  filters: {
    parseDate(date) {
      return moment(date).format('hh:mm DD-MM-YYYY')
    },
  },
  computed: mapGetters({
    user: 'auth/user',
    allUsers: 'users/allUsers',
    lastTransactions: 'transactions/lastTransactions',
    isLoading: 'transactions/isLoading',
  }),
  methods: {
    makeTransaction() {
      this.isOpenTransactionDialog = !this.isOpenTransactionDialog
      this.form = {
        recipient_id: undefined,
        amount: null,
      }
    },
    repeatTransaction(transaction) {

      this.form.recipient_id = transaction.recipient_id
      this.form.amount = transaction.amount


      this.isOpenTransactionDialog = !this.isOpenTransactionDialog
    }
  },
  async created() {
    await this.$store.dispatch('auth/fetchUser')
  },
  async mounted() {
    await this.$store.dispatch('users/getAllUsers')
    await this.$store.dispatch('transactions/fetchLastTransactions')
  }
}
</script>

<style scoped>
.analog-form-item {
  margin: 0 1rem 0.5rem 1rem;
}
.el-icon-refresh {
font-size: 20px;
}
</style>
