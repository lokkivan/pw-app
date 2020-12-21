<template>
  <el-card shadow="hover" class="card-custom">
    <div slot="header" class="clearfix">
      <span>Log in</span>
    </div>
    <el-form ref="form" :model="form" :rules="rules" label-position="left" class="form-custom">
      <el-row>
        <el-col :span="24">
          <el-form-item label="E-Mail" prop="email">
            <el-input
              v-model="form.email"
              type="email"
              name="email"
              placeholder="Enter your email"
            />
          </el-form-item>
          <el-form-item label="Password" prop="password">
            <el-input
              v-model="form.password"
              type="password"
              name="password"
              placeholder="Enter your password"
            />
          </el-form-item>
          <el-form-item>
            <el-button type="primary" @click.prevent="login">
              Log in
            </el-button>
          </el-form-item>
        </el-col>
      </el-row>
    </el-form>
  </el-card>
</template>

<script>
import Form from 'vform'
import Cookies from 'js-cookie'
import { Message } from 'element-ui'

export default {
  middleware: 'guest',
  data: () => ({
    form: new Form({
      email: '',
      password: ''
    }),
    remember: false,

    rules: {
      email: [{ required: true, message: 'Email is required!', trigger: 'change' }, { type: 'email', message: 'Please input valid E-Mail!', trigger: 'change' }],
      password: [{ required: true, message: 'Password is required!', trigger: 'change' }]
    }
  }),

  methods: {
    async login () {
      this.$refs.form.validate(async (valid) => {
        if (valid) {
          await this.$store.dispatch('auth/login', this.form)
          this.redirect()
        } else {
          return false
        }
      })
    },

    redirect () {
      const intendedUrl = Cookies.get('intended_url')
      if (intendedUrl) {
        Cookies.remove('intended_url')
        this.$router.push({ path: intendedUrl })
      } else {
        this.$router.push({ name: 'home' })
      }
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
