<template>
  <el-card shadow="hover" class="card-custom">
    <div slot="header" class="clearfix">
      <span>Register</span>
    </div>
    <el-form ref="form" :model="form" :rules="rules" label-position="left" class="form-custom">
      <el-row>
        <el-col :span="24">
          <el-form-item label="Name" prop="name">
            <el-input
              v-model="form.name"
              name="name"
              placeholder="Enter your name"
            />
          </el-form-item>
          <el-form-item label="E-Mail" prop="email">
            <el-input
              v-model="form.email"
              type="email"
              name="email"
              placeholder="Enter your email address"
            />
            <has-error :form="form" field="email"></has-error>
          </el-form-item>
          <el-form-item label="Password" prop="password">
            <el-input
              v-model="form.password"
              type="password"
              name="password"
              placeholder="Enter your password"
            />
          </el-form-item>
          <el-form-item label="Confirm password" prop="password_confirmation">
            <el-input
              v-model="form.password_confirmation"
              type="password"
              name="password_confirmation"
              placeholder="Enter your password again"
            />
          </el-form-item>
          <el-form-item class="mt-4">
            <el-button type="primary" @click.prevent="register">
              Sign up
            </el-button>
          </el-form-item>
        </el-col>
      </el-row>
    </el-form>
  </el-card>
</template>

<script>
import Form from 'vform'
import {Message} from "element-ui";

export default {
  middleware: 'guest',
  data() {
    const validatePassword = (rule, value, callback) => {
      if (!value || value === '') {
        callback(new Error('Password confirmation is required!'))
      } else if (value !== this.form.password) {
        callback(new Error('The passwords don\'t match!'))
      }
      callback()
    }
    return {
      form: new Form({
        name: '',
        email: '',
        password: '',
        password_confirmation: ''
      }),
      mustVerifyEmail: false,

      rules: {
        name: [
          {required: true, message: 'Name required!', trigger: 'change'}
        ],
        email: [
          {required: true, message: 'E-Mail required!', trigger: 'change'},
          {type: 'email', message: 'Please enter a correct email!', trigger: 'change'}
        ],
        password: [
          {required: true, message: 'Password required!', trigger: 'change'},
          {min: 6, message: 'Password must be at least 6 characters!'}
        ],
        password_confirmation: [
          {required: true, validator: validatePassword, trigger: 'change'}
        ]
      }
    }
  },

  methods: {
    async register() {
      this.$refs.form.validate(async (valid) => {
        if (valid) {
          // Register the user.
          const {data} = await this.form.post('/api/register')
            .catch(error => {
              if (error.response) {
                Message({
                  message: error.response.data.errors,
                  type: 'error',
                  duration: 5000,
                  showClose: true
                });
              }
          });
          const {data: {token}} = await this.form.post('/api/login')
          this.$store.dispatch('auth/saveToken', {token})
          await this.$store.dispatch('auth/updateUser', {user: data})
          this.$router.push({path: '/'})

        } else {
          return false
        }
      })
    }
  }
}
</script>

<style scoped>
.form-custom {
  padding: 0 4rem;
}

.card-custom {
  max-width: 600px;
  margin-left: auto;
  margin-right: auto;
}
</style>
