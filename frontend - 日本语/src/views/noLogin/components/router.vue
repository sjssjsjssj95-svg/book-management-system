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
      <h2>図書館</h2>
    </el-menu-item>
    <el-menu-item index="/book">書籍一覧</el-menu-item>
    <el-menu-item index="/info">お知らせ</el-menu-item>
    <el-menu-item index="/login">ログイン / 新規登録</el-menu-item>
    <el-menu-item index="/root/login">管理者</el-menu-item>
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
