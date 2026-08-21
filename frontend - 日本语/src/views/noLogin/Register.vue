<template>
    <mainRouter style="width: calc(99vw);height: calc(10vh);"/>

    <div class="parent" >
         <el-form :model="form" label-width="auto" :class="animass">
            <h2 style="text-align: center;width: 100%;" >新規登録</h2>
            <el-form-item label="アカウント" style="width: calc(25vw);">
                <el-input v-model="form.account" placeholder="3文字以上で入力してください"/>
            </el-form-item>

            <el-form-item label="パスワード" style="width: calc(25vw);">
                <el-input v-model="form.password" type="password" show-password placeholder="6文字以上で入力してください"/>
            </el-form-item>

            <el-form-item label="ニックネーム" style="width: calc(25vw);">
                <el-input v-model="form.name"  placeholder="2文字以上で入力してください"/>
            </el-form-item>

            <el-form-item label="メールアドレス">
                <el-input v-model="form.email"  placeholder="有効なメールアドレスを入力してください">
                        <template #append>
                            <el-button type="primary" @click="sendCode">認証コードを送信</el-button>
                        </template>
                </el-input>
            </el-form-item>

            <el-form-item label="認証コード">
                <el-input v-model="form.code" type="number">
                    
                </el-input>
            </el-form-item>

            <el-form-item>
                <div style="text-align: center;width: 100%;">
                    <el-button type="primary"  @click="registerSend">すぐに新規登録</el-button>
                    <el-button @click="toLogin">すでにアカウントをお持ちですか？ログイン</el-button>
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
import { sendRegisterCode , register } from '@/api/user';
import { ElMessage } from 'element-plus'

import { useRoute , useRouter } from 'vue-router'
const route = useRoute()
const router = useRouter()

const animass = ref('animate__animated animate__flipInY')

const form = reactive({
  account: '',
  password:'',
  name:'',
  email:'',
  code:''
})

const confirEmail = ref('')

const registerSend = () => {
    if (confirEmail.value=='') {
        ElMessage({
            message: '先に認証コードを取得してください',
            type: 'warning',
        })
    }
    else if (form.account==''||form.code==''||form.name==''||form.password=='') {
        ElMessage({
            message: '未入力の項目があります',
            type: 'warning',
        })
    }
    else {
        const data = {
            username: form.account.trim(),
            password: form.password,
            nickname: form.name.trim(),
            email: form.email.trim(),
            code: form.code.trim(),
        }
        register(data).then(res=>{
            let code = res.code
            if (code == 200) {
                ElMessage({
                    message: '登録しました。',
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
            else if (code==1004){
                ElMessage({
                    message: 'このユーザー名はすでに登録されています。',
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

const sendCode = () => {
    sendRegisterCode(form.email).then(res=>{
        if ( res.code == 200) {
            ElMessage({
                message: '送信しました。メールをご確認ください。',
                type: 'success',
            })
            confirEmail.value = form.email
        }
        else if ( res.code == 1000) {
             ElMessage({
                message: '1分後にもう一度お試しください',
                type: 'warning',
            })
        }
        else if ( res.code == 1001) {
             ElMessage({
                message: 'このメールアドレスはすでに登録されています',
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

const toLogin = () => {
    animass.value = 'animate__animated animate__flipOutY'
    window.setTimeout(()=>{
        router.push('/login')
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