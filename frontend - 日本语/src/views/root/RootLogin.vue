<template>
    <div class="common-layout" style="width: 100%;height: calc(100vh);">
        <el-container style="height: 100%;">
            <el-aside width="calc(15vw)" style="background-color: #2f4050;height: 100%;text-align: center;">
                
                <Router/>
                
            </el-aside>
            <el-container>
                <el-header style="display: flex; justify-content: center;align-items: center;">
                    <h4>先にログインしてください</h4>
                </el-header>

                <el-main style="background-color: #E0E0E0;display: flex; justify-content: center;align-items: center;">
                    <div style="background-color: white;width: calc(30vw);border-radius: 10px;text-align: center;">
                        <p>メールアドレス</p>
                        <el-input v-model="email" style="width: calc(20vw)" placeholder="メールアドレスを入力してください" />
                        <p>パスワード</p>
                        <el-input v-model="password" style="width: calc(20vw)" placeholder="パスワードを入力してください" type="password" show-password/>
                        <br>
                        <el-button style="margin-top: 15px;" type="primary" @click="loginButton">ログイン</el-button>
                        <el-button style="margin-top: 15px;" type="warning" @click="resetPasswordDrawer=true">パスワードを忘れた方</el-button>
                        <br>
                        <div style="height: 15px;"></div>
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
                    <el-input v-model="form.email" style="width: 100%" placeholder="メールアドレス" />
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
                    <el-button @clike="qk">クリア</el-button>
                </el-form-item>
            </el-form>
        </div>
    </el-drawer>
</template>

<script setup>
import Router from './components/Router.vue';
import { ref , reactive } from 'vue';
import { login , findNoLoginPasswordSned , findNoLoginPasswrd } from '@/api/root'
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
            message: 'メールアドレスを入力してください和パスワード！',
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
                    message: '管理者アカウントが見つかりません。',
                    type: 'error',
                })
            }
            else if (res.code == 1) {
                ElMessage({
                    message: 'パスワードが正しくありません。',
                    type: 'error',
                })
            }
            else if (res.code == 200){
                ElMessage({
                    message: 'ログインしました。',
                    type: 'success',
                })
                localStorage.setItem('root_token',res.token)
                window.setTimeout(()=>{
                    router.push('/root/table')
                },500)
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

const sendCode = () => {
    if (form.email=='') {
        ElMessage({
            message: 'メールアドレスを入力してください。',
            type: 'warning',
        })
    }
    else {
        findNoLoginPasswordSned(form.email).then(res=>{
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
        findNoLoginPasswrd(data).then(res=>{
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

const qk = () => {
    form.email = ''
    form.code = ''
    form.password = ''
}
</script>