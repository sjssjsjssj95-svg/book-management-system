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
      <h2>図書館</h2>
    </el-menu-item>
    <el-menu-item index="/user/books">書籍一覧</el-menu-item>
    <el-menu-item index="/user/borrow">貸出・返却履歴</el-menu-item>
    <el-menu-item index="/user/info">お知らせ</el-menu-item>
    <el-menu-item index="/user/center">マイページ</el-menu-item>
     <el-popconfirm
        class="box-item"
        title="ログアウトしてもよろしいですか？"
        placement="bottom-end"
      >
        <template #reference>
          <el-button type="danger" style="height: 101%;border-radius: 0%;">ログアウト</el-button> 
        </template>
        <template #actions="{ confirm, cancel }">
          <el-button size="small" @click="cancel">キャンセル</el-button>
          <el-button
            @click="exitLogin"
            size="small"
            type="danger"
          >
            確認
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
      message: '先にログインしてください。',
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
        message: 'ログアウトしました。まもなく移動します。',
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
