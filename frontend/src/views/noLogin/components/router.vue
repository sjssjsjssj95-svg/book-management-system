<template>
  <el-menu
    :default-active="choose"
    class="el-menu-demo"
    mode="horizontal"
    :ellipsis="false"
    @select="handleSelect"
  >
    <el-menu-item index="/">
      <img
        style="width: calc(3vw)"
        src="../../../assets/img/logo.png"
        alt="Element logo"
      />
      <h2>图书馆</h2>
    </el-menu-item>
    <el-menu-item index="/book">图书浏览</el-menu-item>
    <el-menu-item index="/info">公告</el-menu-item>
    <el-menu-item index="/login">登录/注册</el-menu-item>
    <el-menu-item index="/root/login">管理员</el-menu-item>
  </el-menu>
</template>

<script setup>
import { ref } from 'vue'
import { useRoute , useRouter } from 'vue-router'
import { loginIO } from '@/api/user'
const route = useRoute()
const router = useRouter()
const choose = ref('')

const token = ref('')
token.value = localStorage.getItem('token')

choose.value=route.path
if (route.path=='/register'||route.path=='/findpassword'){
  choose.value='/login'
}

const handleSelect = (key) => {
  router.push(key)
}
</script>

<style scoped>
.el-menu--horizontal > .el-menu-item:nth-child(1) {
  margin-right: auto;
}
</style>
