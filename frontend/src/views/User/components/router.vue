<template>
  <el-menu
    :default-active="choose"
    class="el-menu-demo"
    mode="horizontal"
    :ellipsis="false"
    @select="handleSelect"
  >
    <el-menu-item index="/user/home">
      <img
        style="width: calc(3vw)"
        src="../../../assets/img/logo.png"
        alt="Element logo"
      />
      <h2>图书馆</h2>
    </el-menu-item>
    <el-menu-item index="/user/books">图书浏览</el-menu-item>
    <el-menu-item index="/user/borrow">借还记录</el-menu-item>
    <el-menu-item index="/user/info">公告</el-menu-item>
    <el-menu-item index="/user/center">个人中心</el-menu-item>
     <el-popconfirm
        class="box-item"
        title="确定退出登录吗？"
        placement="bottom-end"
      >
        <template #reference>
          <el-button type="danger" style="height: 101%;border-radius: 0%;">退出登录</el-button> 
        </template>
        <template #actions="{ confirm, cancel }">
          <el-button size="small" @click="cancel">取消</el-button>
          <el-button
            @click="exitLogin"
            size="small"
            type="danger"
          >
            确定
          </el-button>
        </template>
      </el-popconfirm>
  </el-menu>
</template>

<script setup>
import { ref } from 'vue'
import { loginIO , logout } from '@/api/user'
import { ElMessage } from 'element-plus'
import { useRoute , useRouter } from 'vue-router'
import { lo } from 'element-plus/es/locale/index.mjs'
const route = useRoute()
const router = useRouter()
const choose = ref('')

const token = ref('')
token.value = localStorage.getItem('token')

loginIO(token.value).then(res=>{
  if (res.code!='1'){
    ElMessage({
      message: '请先登录!',
      type: 'error',
    })
    window.setTimeout(()=>{
      router.push('/home')
    },500)
  }
})

choose.value=route.path
if (route.path=='/register'){
  choose.value='/login'
}

const handleSelect = (key) => {
  router.push(key)
}

const exitLogin = () => {
  logout(token.value).then(res=>{
    if (res.code==200){
      ElMessage({
        message: '退出成功，即将跳转!',
        type: 'success',
      })
      window.setTimeout(()=>{
        router.push('/')
      },500)
    }
  })
}
</script>

<style scoped>
.el-menu--horizontal > .el-menu-item:nth-child(1) {
  margin-right: auto;
}
</style>
