<template>
  <el-card class="text-center" shadow="hover">
    <h3 class="mb-4">
      We're sorry, page not found
    </h3>

    <div class="links">
      <router-link :to="{ name: 'welcome' }">
        Go to main page
      </router-link>
    </div>
  </el-card>
</template>

<script>
export default {
  name: 'NotFound'
}
</script>
