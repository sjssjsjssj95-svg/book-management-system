<template>
    <div class="common-layout" style="width: 100%;height: calc(100vh);">
        <el-container style="height: 100%;">
            <el-aside width="calc(15vw)" style="background-color: #2f4050;height: 100%;text-align: center;">
                
                <Router/>
                
            </el-aside>
            <el-container>
                <el-header style="display: flex; justify-content: center;align-items: center;">
                    <h3>个人中心</h3>
                </el-header>

                <el-main style="background-color: #E0E0E0;display: flex; justify-content: center;align-items: center;" v-loading="mainLoading" >
                    <div>
                        <el-descriptions title="Root Info" border="true" size="large" column="2">
                            <el-descriptions-item label="昵称">{{ rootInfo[0] }}</el-descriptions-item>
                            <el-descriptions-item label="注册天数">{{ rootInfo[3] }}天</el-descriptions-item>
                            <el-descriptions-item label="状态">
                                <el-tag v-if="rootInfo[2]">正常</el-tag>
                                <el-tag type="danger" v-if="!rootInfo[2]">禁用</el-tag>
                            </el-descriptions-item>
                            <el-descriptions-item label="邮箱">{{ rootInfo[1] }}</el-descriptions-item>
                        </el-descriptions>
                        <el-button type="primary" style="width: 100%;margin-top: 10px;" @click="resetPasswordDrawer=true">修改密码</el-button>
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
                    <el-input v-model="form.email" style="width: 100%" placeholder="邮箱" disabled/>
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
                    <el-button @clike="qkButton">清空</el-button>
                </el-form-item>
            </el-form>
        </div>
    </el-drawer>
</template>

<script setup>
import Router from './components/Router.vue';
import { ref , reactive } from 'vue';
import { getRootInfo } from '@/api/root.js';
import { findPasswordSned , findPasswrd } from '@/api/root'
import { ElMessage } from 'element-plus'


const mainLoading = ref(true)
const rootInfo = reactive({})

const token = ref('')


const resetPasswordDrawer = ref(false)
const form = reactive({
    email:'',
    code:'',
    password:''
})

token.value = localStorage.getItem('root_token')

getRootInfo(token.value).then(res=>{
    rootInfo[0] = res.nickname
    rootInfo[1] = res.email
    form.email = res.email
    rootInfo[3] = res.register_days
    if (res.status==1) {
        rootInfo[2]=true
    }
    else {
        rootInfo[2]=false
    }
    mainLoading.value=false
})

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

const qkButton = () => {
    console.log('asd')
    form.code = ''
    form.password = ''
}
</script>