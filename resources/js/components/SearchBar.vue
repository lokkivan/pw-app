<template>
  <el-row>
    <el-col :span="8">
      <div class="analog-form-item">
        <el-input v-model="search" placeholder="Enter the query..." suffix-icon="el-icon-search" @blur="toSearch" @keyup.enter.native="toSearch"></el-input>
      </div>
    </el-col>
    <el-col :span="2">
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
</template>

<script>
import { mapGetters } from "vuex";
export default {
  name: "SearchBar",
  components: {  },
  props: {
    module: String,
    isFiltering: Boolean
  },
  data() {
    return {
      search: undefined
    }
  },
  computed: mapGetters({
    user: 'auth/user'
  }),
  methods: {
    toReset() {
      this.$store.dispatch(`${this.module}/setSearch`, undefined)
      this.search = undefined
      this.$store.dispatch(`${this.module}/get${this.module.charAt(0).toUpperCase() + this.module.slice(1)}`)
    },
    toSearch() {
      this.$store.dispatch(`${this.module}/setSearch`, this.search ? this.search : undefined)
      this.$store.dispatch(`${this.module}/setPage`, 1)
      this.$store.dispatch(`${this.module}/get${this.module.charAt(0).toUpperCase() + this.module.slice(1)}`)
    }
  }
}
</script>

<style scoped>
.analog-form-item {
  margin: 0 1rem 0.5rem 1rem;
}
</style>
