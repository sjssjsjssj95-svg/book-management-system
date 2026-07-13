<template>
    <mainRouter style="width: calc(100vw);height: calc(10vh);"/>

    <div class="parent" >
         <el-form :model="form" label-width="auto" :class="animass">
            <h2 style="text-align: center;width: 100%;" >登录</h2>
            <el-form-item label="账户">
                <el-input v-model="form.account" />
            </el-form-item>

            <el-form-item label="密码">
                <el-input v-model="form.password" type="password" show-password/>
            </el-form-item>

            <el-form-item>
                <div style="text-align: center;width: 100%;">
                    <el-button type="primary"  @click="login">立马登录</el-button>
                    <el-button @click="toRegister">前往注册</el-button>
                    <el-button @click="findPasswordBut" type="warning">忘记密码</el-button>
                    <el-button @click="findUserNameBut" type="warning">忘记账户</el-button>
                </div>
            </el-form-item>
        </el-form>
    </div>

   
   <el-footer style="width: 100%;background-color: #DEDEDE;height: calc(10vh);">
    <div style=" display: flex;
                  align-items: center;     /* 垂直居中 */
                  justify-content: center; /* 水平居中（如果需要） */
                  height: 100%; /* 水平居中 */">
      <el-text>图书馆管理系统</el-text>
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
            message: '请输入完整信息',
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
                    message: '登录成功，即将跳转',
                    type: 'success',
                })
                localStorage.setItem('token',res.token)
                window.setTimeout(()=>{
                    router.push('/user/home')
                },500)
            }
            else if (code==2002){
                ElMessage({
                    message: '密码错误',
                    type: 'warning',
                })
            }
            else if (code==2001){
                ElMessage({
                    message: '没有该用户',
                    type: 'warning',
                })
            }
            else {
                ElMessage({
                    message: '错误！',
                    type: 'error',
                })
            }
        })
    }
}

const findUserNameBut = () => {
    ElMessageBox.prompt('请输入邮箱，您的账户将会在邮件中，请注意查收', '找回账户', {
        confirmButtonText: '发送邮件',
        cancelButtonText: '取消发送',
        inputPattern:
        /[\w!#$%&'*+/=?^_`{|}~-]+(?:\.[\w!#$%&'*+/=?^_`{|}~-]+)*@(?:[\w](?:[\w-]*[\w])?\.)+[\w](?:[\w-]*[\w])?/,
        inputErrorMessage: '请输入正确的邮箱！',
    })
    .then(({ value }) => {
        findUserName(value).then(res=>{
            if (res.code==3000){
                ElMessage({
                    message: '此邮箱还未注册',
                    type: 'warning',
                })
            }
            else if (res.code==1000){
                ElMessage({
                    message: '发送频繁，请稍后再试',
                    type: 'warning',
                })
            }
            else if (res.code==200) {
                ElMessage({
                    message: '发送成功',
                    type: 'success',
                })
            }
            else {
                ElMessage({
                    message: '错误！',
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
  justify-content: center; /* 水平居中 */
  align-items: center;    /* 垂直居中 */
}
</style>