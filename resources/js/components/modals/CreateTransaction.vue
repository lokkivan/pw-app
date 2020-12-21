<template>
  <el-dialog :title="`Make new transaction`" :visible="isOpen" @close="clearForm">
    <el-form ref="transactionForm" :model="form" :rules="rules" label-position="top">
      <el-row>
        <el-col :span="14">
          <el-form-item label="Recipient" prop="recipient_id">
            <el-select v-model="form.recipient_id" filterable>
              <el-option
                label="Сhoose recipient"
                :value="undefined"
                disabled>
              </el-option>
              <el-option v-for="(user, index) in usersWithoutCurrent()" :key="index"
                :label="user.name"
                :value="user.id">
              </el-option>
            </el-select>
          </el-form-item>
        </el-col>
        <el-col :span="8">
          <div class="d-flex">
            <el-form-item label="Amount" prop="amount" class="ml-auto">
              <el-input-number v-model="form.amount" controls-position="right" :min="1" :max="this.user.account.balance"></el-input-number>
            </el-form-item>
          </div>
        </el-col>
      </el-row>
    </el-form>
    <div class="analog-form-item">
      <el-button
        class="mt-3 ml-auto"
        type="primary"
        @click="sendTransaction">
        Send
      </el-button>
    </div>
  </el-dialog>
</template>

<script>
import {mapGetters} from "vuex";

export default {
  name: "CreateTransaction",
  props: {
    isOpen: Boolean,
    users: Array,
    form: Object,
  },
  data() {
    return {
      rules: {
        recipient_id: [{ required: true,  message: 'The recipient must be selected!', trigger: 'change' }],
        amount: [{ required: true, message: 'The amount must be filled in', trigger: 'change' }],
      },
    }
  },
  computed: mapGetters({
    user: 'auth/user',
  }),
  watch: {
    model() {
      this.form.recipient_id = this.model.recipient_id
      this.form.amount = this.model.amount
    },
  },
  methods: {
    sendTransaction() {
      this.$refs['transactionForm'].validate(async (valid) => {
        if (valid) {
          await this.$store.dispatch(
            'transactions/createTransaction',
            {
              sender_id: this.user.id,
              recipient_id: this.form.recipient_id,
              amount: this.form.amount,
            })
          await this.$store.dispatch('auth/fetchUser')
          this.$refs['transactionForm'].resetFields();
          this.$emit('dialog');
        }
      });
    },
    usersWithoutCurrent() {
      return this.users.filter( i => i.id !== this.user.id)
    },
    clearForm() {
      this.$emit('dialog')
    },
  }
}
</script>
