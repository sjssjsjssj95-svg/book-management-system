<template>
    <div class="common-layout" style="width: 100%;height: calc(100vh);">
        <el-container style="height: 100%;">
            <el-aside width="calc(15vw)" style="background-color: #2f4050;height: 100%;text-align: center;">
                
                <Router/>
                
            </el-aside>
            <el-container>
                <el-header style="display: flex; justify-content: center;align-items: center;">
                    <h4>请先登录</h4>
                </el-header>

                <el-main style="background-color: #E0E0E0;display: flex; justify-content: center;align-items: center;">
                    <div style="background-color: white;width: calc(30vw);border-radius: 10px;text-align: center;">
                        <p>邮箱</p>
                        <el-input v-model="email" style="width: calc(20vw)" placeholder="请输入邮箱" />
                        <p>密码</p>
                        <el-input v-model="password" style="width: calc(20vw)" placeholder="请输入密码" type="password" show-password/>
                        <br>
                        <el-button style="margin-top: 15px;" type="primary" @click="loginButton">登录</el-button>
                        <el-button style="margin-top: 15px;" type="warning" @click="resetPasswordDrawer=true">忘记密码</el-button>
                        <br>
                        <div style="height: 15px;"></div>
                    </div>
                </el-main>
            </el-container>
        </el-container>
    </div>

    <el-drawer v-model="resetPasswordDrawer" title="I am the title" :with-header="false">
        <div>
            <h3>重置密码</h3>
            <el-form :model="form" label-width="auto" style="max-width: 600px">
                <el-form-item label="邮箱">
                    <el-input v-model="form.email" style="width: 100%" placeholder="邮箱" />
                </el-form-item>
                <el-form-item label="验证码">
                    <el-input v-model="form.code" style="width: 100%" placeholder="验证码" type="number"/>
                </el-form-item>
                <el-form-item label="新密码">
                    <el-input v-model="form.password" style="width: 100%" placeholder="新密码" type="password" show-password/>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="resetPassword">重置密码</el-button>
                    <el-button type="info" @click="sendCode">发送验证码</el-button>
                    <el-button>清空</el-button>
                </el-form-item>
            </el-form>
        </div>
    </el-drawer>
</template>

<script setup>
import Router from './components/Router.vue';
import { ref , reactive } from 'vue';
import { login , findPasswordSned , findPasswrd } from '@/api/root'
import { ElMessage } from 'element-plus'
import { useRoute , useRouter } from 'vue-router'
const route = useRoute()
const router = useRouter()

const email = ref('')
const password = ref('')

const resetPasswordDrawer = ref(false)
const form = reactive({
    email:'',
    code:'',
    password:''
})

const loginButton = () => {
    if (email.value==''||password.value==''){
        ElMessage({
            message: '请输入邮箱和密码！',
            type: 'warning',
        })
    }
    else {
        let data = {
            email : email.value,
            password : password.value
        }
        login(data).then(res=>{
            if (res.code == 2) {
                ElMessage({
                    message: '没有找到该root!',
                    type: 'error',
                })
            }
            else if (res.code == 1) {
                ElMessage({
                    message: '密码错误!',
                    type: 'error',
                })
            }
            else if (res.code == 200){
                ElMessage({
                    message: '登录成功!',
                    type: 'success',
                })
                localStorage.setItem('root_token',res.token)
                window.setTimeout(()=>{
                    router.push('/root/table')
                },500)
            }
            else {
                ElMessage({
                    message: '错误!请重试',
                    type: 'error',
                })
            }
        })
    }
}

const sendCode = () => {
    if (form.email=='') {
        ElMessage({
            message: '请输入邮箱!',
            type: 'warning',
        })
    }
    else {
        findPasswordSned(form.email).then(res=>{
            if (res.code==3000) {
               ElMessage({
                    message: '未找到该管理员，请确认后重试!',
                    type: 'warning',
                }) 
            }
            else if (res.code==200) {
                ElMessage({
                    message: '发送成功，请注意查收!',
                    type: 'success',
                }) 
            }
            else {
                ElMessage({
                    message: '错误，请重试!',
                    type: 'error',
                }) 
            }
        })
    }
}

const resetPassword = ( ) => {
    if (form.email==''||form.code==''||form.password=='') {
        ElMessage({
            message: '请输入完整信息!',
            type: 'warning',
        }) 
    }
    else {
        const data = {
            password: form.password,
            email: form.email.trim(),
            code: form.code.trim(),
        }
        findPasswrd(data).then(res=>{
            let code = res.code
            if (code == 200) {
                ElMessage({
                    message: '修改成功.',
                    type: 'success',
                })
                form.account=''
                form.password=''
                form.code=''
                form.email=''
                form.name=''
            }
            else if (code==1002){
                ElMessage({
                    message: '验证码过期.',
                    type: 'warning',
                })
            }
            else if (code==1003){
                ElMessage({
                    message: '验证码错误.',
                    type: 'warning',
                })
            }
            else {
                ElMessage({
                    message: '错误，请重试',
                    type: 'error',
                })
            }
        })
    }
}
</script>