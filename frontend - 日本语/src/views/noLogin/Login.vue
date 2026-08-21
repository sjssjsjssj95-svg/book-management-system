<template>
    <mainRouter style="width: calc(100vw);height: calc(10vh);"/>

    <div class="parent" >
         <el-form :model="form" label-width="auto" :class="animass">
            <h2 style="text-align: center;width: 100%;" >ログイン</h2>
            <el-form-item label="アカウント">
                <el-input v-model="form.account" />
            </el-form-item>

            <el-form-item label="パスワード">
                <el-input v-model="form.password" type="password" show-password/>
            </el-form-item>

            <el-form-item>
                <div style="text-align: center;width: 100%;">
                    <el-button type="primary"  @click="login">ログイン</el-button>
                    <el-button @click="toRegister">新規登録</el-button>
                    <el-button @click="findPasswordBut" type="warning">パスワードを忘れた方</el-button>
                    <el-button @click="findUserNameBut" type="warning">アカウントを忘れた方</el-button>
                </div>
            </el-form-item>
        </el-form>
    </div>

   
   <el-footer style="width: 100%;background-color: #DEDEDE;height: calc(10vh);">
    <div style=" display: flex;
                  align-items: center;     /* 垂直方向に中央揃え */
                  justify-content: center; /* 水平方向に中央揃え（必要に応じて） */
                  height: 100%; /* 水平方向に中央揃え */">
      <el-text>図書館管理システム</el-text>
    </div>
  </el-footer>
</template>

<script setup>
import mainRouter from './components/router.vue';
import { ref , reactive } from 'vue';
import { findUserName, passwordLogin } from '@/api/user';
import { ElMessage , ElMessageBox } from 'element-plus'
import { useRoute , useRouter } from 'vue-router'
const route = useRoute()
const router = useRouter()

const animass = ref('animate__animated animate__flipInY')

const form = reactive({
  account: '',
  password:''
})

const toRegister = () => {
    animass.value = 'animate__animated animate__flipOutY'
    window.setTimeout(()=>{
        router.push('/register')
    },1000)
}

const login = () => {
    if (form.account==''||form.password==''){
        ElMessage({
            message: 'すべての項目を入力してください',
            type: 'warning',
        })
    }
    else {
        const data = {
            username: form.account,
            password: form.password,
        }
        passwordLogin(data).then(res=>{
            let code = res.code
            if (code==200) {
                ElMessage({
                    message: 'ログインしました。まもなく移動します',
                    type: 'success',
                })
                localStorage.setItem('token',res.token)
                window.setTimeout(()=>{
                    router.push('/user/home')
                },500)
            }
            else if (code==2002){
                ElMessage({
                    message: 'パスワードが正しくありません',
                    type: 'warning',
                })
            }
            else if (code==2001){
                ElMessage({
                    message: 'ユーザーが見つかりません',
                    type: 'warning',
                })
            }
            else {
                ElMessage({
                    message: 'エラーが発生しました。',
                    type: 'error',
                })
            }
        })
    }
}

const findUserNameBut = () => {
    ElMessageBox.prompt('メールアドレスを入力してください。アカウント情報をメールでお送りします。', 'アカウントを確認', {
        confirmButtonText: 'メールを送信',
        cancelButtonText: '送信をキャンセル',
        inputPattern:
        /[\w!#$%&'*+/=?^_`{|}~-]+(?:\.[\w!#$%&'*+/=?^_`{|}~-]+)*@(?:[\w](?:[\w-]*[\w])?\.)+[\w](?:[\w-]*[\w])?/,
        inputErrorMessage: '正しいメールアドレスを入力してください。',
    })
    .then(({ value }) => {
        findUserName(value).then(res=>{
            if (res.code==3000){
                ElMessage({
                    message: 'このメールアドレスは登録されていません',
                    type: 'warning',
                })
            }
            else if (res.code==1000){
                ElMessage({
                    message: '送信回数が多すぎます。しばらくしてからお試しください',
                    type: 'warning',
                })
            }
            else if (res.code==200) {
                ElMessage({
                    message: '送信しました',
                    type: 'success',
                })
            }
            else {
                ElMessage({
                    message: 'エラーが発生しました。',
                    type: 'error',
                })
            }
        })
    })
}

const findPasswordBut = () => {
    animass.value = 'animate__animated animate__flipOutY'
    window.setTimeout(()=>{
        router.push('/findpassword')
    },1000)
}
</script>

<style>
.parent {
  height: 80vh;          /* 或固定高度 */
  display: flex;
  justify-content: center; /* 水平方向に中央揃え */
  align-items: center;    /* 垂直方向に中央揃え */
}
</style>