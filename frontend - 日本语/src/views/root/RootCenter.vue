<template>
    <div class="common-layout" style="width: 100%;height: calc(100vh);">
        <el-container style="height: 100%;">
            <el-aside width="calc(15vw)" style="background-color: #2f4050;height: 100%;text-align: center;">
                
                <Router/>
                
            </el-aside>
            <el-container>
                <el-header style="display: flex; justify-content: center;align-items: center;">
                    <h3>マイページ</h3>
                </el-header>

                <el-main style="background-color: #E0E0E0;display: flex; justify-content: center;align-items: center;" v-loading="mainLoading" >
                    <div>
                        <el-descriptions title="Root Info" border="true" size="large" column="2">
                            <el-descriptions-item label="ニックネーム">{{ rootInfo[0] }}</el-descriptions-item>
                            <el-descriptions-item label="登録日数">{{ rootInfo[3] }}日</el-descriptions-item>
                            <el-descriptions-item label="ステータス">
                                <el-tag v-if="rootInfo[2]">正常</el-tag>
                                <el-tag type="danger" v-if="!rootInfo[2]">利用停止</el-tag>
                            </el-descriptions-item>
                            <el-descriptions-item label="メールアドレス">{{ rootInfo[1] }}</el-descriptions-item>
                        </el-descriptions>
                        <el-button type="primary" style="width: 100%;margin-top: 10px;" @click="resetPasswordDrawer=true">パスワードを変更</el-button>
                    </div>
                </el-main>
            </el-container>
        </el-container>
    </div>

    <el-drawer v-model="resetPasswordDrawer" title="I am the title" :with-header="false">
        <div>
            <h3>パスワードをリセット</h3>
            <el-form :model="form" label-width="auto" style="max-width: 600px">
                <el-form-item label="メールアドレス">
                    <el-input v-model="form.email" style="width: 100%" placeholder="メールアドレス" disabled/>
                </el-form-item>
                <el-form-item label="認証コード">
                    <el-input v-model="form.code" style="width: 100%" placeholder="認証コード" type="number"/>
                </el-form-item>
                <el-form-item label="新しいパスワード">
                    <el-input v-model="form.password" style="width: 100%" placeholder="新しいパスワード" type="password" show-password/>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="resetPassword">パスワードをリセット</el-button>
                    <el-button type="info" @click="sendCode">認証コードを送信</el-button>
                    <el-button @clike="qkButton">クリア</el-button>
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
            message: 'メールアドレスを入力してください。',
            type: 'warning',
        })
    }
    else {
        findPasswordSned(form.email).then(res=>{
            if (res.code==3000) {
               ElMessage({
                    message: '管理者が見つかりません。確認してもう一度お試しください。',
                    type: 'warning',
                }) 
            }
            else if (res.code==200) {
                ElMessage({
                    message: '送信しました。メールをご確認ください。',
                    type: 'success',
                }) 
            }
            else {
                ElMessage({
                    message: 'エラーが発生しました。もう一度お試しください。',
                    type: 'error',
                }) 
            }
        })
    }
}

const resetPassword = ( ) => {
    if (form.email==''||form.code==''||form.password=='') {
        ElMessage({
            message: 'すべての項目を入力してください。',
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
                    message: '変更しました。',
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
                    message: '認証コードの有効期限が切れています。',
                    type: 'warning',
                })
            }
            else if (code==1003){
                ElMessage({
                    message: '認証コードが正しくありません。',
                    type: 'warning',
                })
            }
            else {
                ElMessage({
                    message: 'エラーが発生しました。もう一度お試しください。',
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