<template>
    <router style="width: calc(100vw);height: calc(10vh);"/>

    <div style="margin-left: 15%;margin-right: 15%;min-height: calc(75vh);" 
            v-loading="loading">
        <el-descriptions
            title="ユーザー情報"
            direction="vertical"
            border
            style="margin-top: calc(5vh)"
        >
            <el-descriptions-item
            :rowspan="2"
            :width="140"
            label="プロフィール画像"
            align="center"
            >
            <el-image
                style="width: 100px; height: 100px"
                :src="userinfo[4]"
                :preview-src-list="[userinfo[4]]"
                :initial-index="0"
            />
            </el-descriptions-item>
            <el-descriptions-item label="ユーザー名">{{ userinfo[0] }}</el-descriptions-item>
            <el-descriptions-item label="ニックネーム">{{ userinfo[1] }}</el-descriptions-item>
            <el-descriptions-item label="登録日数">{{ userinfo[2] }}</el-descriptions-item>
            <el-descriptions-item label="アカウント状態">
            <el-tag v-if="userinfo[5]">正常</el-tag>
            <el-tag type="danger" v-if="!userinfo[5]">利用停止</el-tag>
            </el-descriptions-item>
            <el-descriptions-item label="メールアドレス">
            {{ userinfo[3] }}
            </el-descriptions-item>
        </el-descriptions>
        
        <div style="text-align: right;margin-top: 10px;">
            <el-button type="primary" @click="openUpdateUserNameBut">ニックネームを変更</el-button>
            <el-button type="info" @click="dialogVisible=true">プロフィール画像を変更</el-button>
            <el-button type="warning" @click="update_email=true">メールアドレスを変更</el-button>
            <el-button type="danger" @click="update_password=true">パスワードを変更</el-button>
        </div>
    </div>


    <el-footer style="width: 100%;background-color: #DEDEDE;height: calc(10vh);">
    <div style=" display: flex;
                  align-items: center;     /* 垂直方向に中央揃え */
                  justify-content: center; /* 水平方向に中央揃え（必要に応じて） */
                  height: 100%; /* 水平方向に中央揃え */">
      <el-text>図書館管理システム</el-text>
    </div>
  </el-footer>


  <!--ファイルをアップロード-->
  <el-dialog
    v-model="dialogVisible"
    title="プロフィール画像をアップロード"
    width="500"
    :before-close="handleClose"
  >
    <el-upload
        ref="upload"
        name="avatar"
        :headers="{
            Authorization: 'Bearer ' + token
        }"
        action="http://127.0.0.1:8000/api/user/update/img"
        :limit="1"
        :on-exceed="handleExceed"
        :auto-upload="false"
        :before-upload="beforeAvatarUpload"
        :on-success="handleSuccess"
    >
        <template #trigger>
        <el-button type="primary">プロフィール画像を選択</el-button>
        </template>
        <el-button class="ml-3" type="success" @click="submitUpload" style="margin-left: 10px;">
        アップロード
        </el-button>
        <template #tip>
        <div class="el-upload__tip text-red">
            1枚のみ選択できます。複数選択した場合は最後の画像で上書きされます
        </div>
        </template>
    </el-upload>
  </el-dialog>

  <!--パスワードを変更-->
  <el-dialog
    v-model="update_password"
    title="パスワードを変更"
    width="500"
    :before-close="handleClose"
  >
    <p>現在のパスワード</p>
    <el-input v-model="updatePasswordOldPassword" style="width: 100%" placeholder="現在のパスワードを入力してください" type="password" show-password/>
    <p>新しいパスワード</p>
    <el-input v-model="updatePasswordNewPassword" style="width: 100%" placeholder="新しいパスワードを入力してください" type="password" show-password/>
    <el-row :gutter="24" style="margin-top: 10px;">
        <el-col :span="12"><el-button type="primary" style="width: 100%;" @click="change_password">パスワードを変更</el-button></el-col>
        <el-col :span="12"><el-button type="info" style="width: 100%;" @click="clearNewPassword">データをクリア</el-button></el-col>
    </el-row>
  </el-dialog>

   <!--メールアドレスを変更-->
  <el-dialog
    v-model="update_email"
    title="メールアドレスを変更"
    width="500"
    :before-close="handleClose"
  >
    <p>新しいメールアドレス</p>
    <el-input v-model="new_email" style="width: 100%" placeholder="メールアドレスを入力してください"/>
    <p>認証コード</p>
    <el-input v-model="new_code" style="width: 100%" placeholder="認証コードを入力してください"/>
    <el-row :gutter="24" style="margin-top: 10px;">
        <el-col :span="12"><el-button type="primary" style="width: 100%;" @click="sendCode">認証コードを送信</el-button></el-col>
        <el-col :span="12"><el-button type="info" style="width: 100%;" @click="changeEmail">メールアドレスを変更</el-button></el-col>
    </el-row>
  </el-dialog>
</template>

<script setup>
import router from './components/router.vue';
import { ref , reactive } from 'vue';
import { ElMessage, ElMessageBox } from 'element-plus'
import { getUserInfo , updateUserName , updateUserPassword , resetEmailSendEmail , resetEmail } from '@/api/user';
import { genFileId } from 'element-plus'

const update_password = ref(false)
const update_email = ref(false)

const token = ref('')
const updatePasswordOldPassword = ref('')
const updatePasswordNewPassword = ref('')
token.value = localStorage.getItem('token')
const dialogVisible = ref(false)

const new_email = ref('')
const new_code = ref('')

const loading = ref(true)

const userinfo = reactive([])
getUserInfo(token.value).then(res=>{
    
    userinfo[0] = res.username
    userinfo[1] = res.nickname
    userinfo[2] = res.register_days+'日'
    userinfo[3] = res.email
    if (res.status==1){
        userinfo[5] = true
    }
    else {
        userinfo[5] = false
    }
    if (res.avatar==''){
        userinfo[4] = './img/user/mr.png'
    }
    else {
        userinfo[4] = 'http://127.0.0.1:8000/storage/'+res.avatar
    }
    console.log(userinfo[4])
    loading.value=false
})

const openUpdateUserNameBut = () => {
  ElMessageBox.prompt('新しいニックネームを入力してください', 'ニックネームを変更', {
    confirmButtonText: '確認',
    cancelButtonText: 'キャンセル',
  })
    .then(({ value }) => {
      if(value==''){
        ElMessage({
            type: 'info',
            message: 'ニックネームを入力してください',
        })
      }
      else {
        const date = {
            username : value
        }
        updateUserName( date , token.value ).then(res=>{
            if (res.code == 200) {
                ElMessage({
                    type: 'success',
                    message: '変更しました',
                })
                userinfo[1] = value
            }
            else {
                ElMessage({
                    type: 'error',
                    message: 'エラー',
                })
            }
        })
      }
    })
}

const upload = ref()

const handleExceed = (files) => {
  upload.value.clearFiles()
  const file = files[0]
  file.uid = genFileId()
  upload.value.handleStart(file)
}

const submitUpload = () => {
  upload.value.submit()
}

const beforeAvatarUpload = (rawFile) => {
  if (rawFile.type !== 'image/jpeg' && rawFile.type !== 'image/jpg' && rawFile.type !== 'image/png') {
    ElMessage.error('選択したファイルは画像ではありません。')
    return false
  } else if (rawFile.size / 1024 / 1024 > 2) {
    ElMessage.error('画像サイズは2MB以下にしてください。')
    return false
  }
  return true
}

const handleSuccess = (response, file, fileList) => {
  if (response.code==200) {
    dialogVisible.value=false
    loading.value=true
    ElMessage({
        type: 'success',
        message: '変更しました',
    })
    getUserInfo(token.value).then(res=>{
        if (res.avatar==''){
            userinfo[4] = './img/user/mr.png'
        }
        else {
            userinfo[4] = 'http://127.0.0.1:8000/storage/'+res.avatar
        }
        loading.value=false
    })
  }
  else {
    ElMessage({
        type: 'error',
        message: 'エラー',
    })
  }
}

const clearNewPassword = () =>{
    updatePasswordNewPassword.value=''
    updatePasswordOldPassword.value=''
}

const change_password = () =>{
    if (updatePasswordNewPassword.value==''||updatePasswordOldPassword.value==''){
        ElMessage({
            message: 'パスワードを入力してください。',
            type: 'warning',
        })
    }
    else {
        const date = {
            password : updatePasswordNewPassword.value,
            old_password : updatePasswordOldPassword.value,
        }
        updateUserPassword( date , token.value ).then(res=>{
            if (res.code==4001) {
                ElMessage({
                    message: '現在のパスワードが正しくありません。',
                    type: 'warning',
                })
            }
            else if(res.code==1) {
                ElMessage({
                    message: '変更しました。',
                    type: 'success',
                }) 
                clearNewPassword()
                update_password.value=false
            }
        }).catch(error=>{
            ElMessage({
                message: 'エラー：パスワードは6文字以上で入力してください。',
                type: 'error',
            })
        })
    }
}

const new_email_value = ref('')
const sendCode = () =>{
    if (new_email.value==''){
        ElMessage({
            message: 'メールアドレスを入力してください。',
            type: 'warning',
        })
    }
    else {
        const data = {
            email : new_email.value
        }
        resetEmailSendEmail(data,token.value).then(res=>{
            if(res.msg==1){
                ElMessage({
                    message: '送信しました。メールをご確認ください。',
                    type: 'success',
                })
                new_email_value.value = new_email.value
            }
            else {
                ElMessage({
                    message: 'エラー：メールアドレスの形式が正しくありません。',
                    type: 'warning',
                })
            }
        }).catch(error=>{
            ElMessage({
                message: 'エラー：メールアドレスの形式が正しくありません。',
                type: 'error',
            })
        })
    }
}

const changeEmail = () =>{
    if (new_code==''||new_email_value==''){
        ElMessage({
            message: '認証コードを取得して入力してください。',
            type: 'warning',
        })
    }
    else {
        const data = {
            email : new_email_value.value,
            code : new_code.value
        }
        resetEmail(data,token.value).then(res=>{
            if (res.msg==1){
                ElMessage({
                    message: '変更しました。',
                    type: 'success',
                })
                update_email.value=false
                new_code.value=''
                new_email_value.value=''
                new_email.value=''
            }
            else {
                ElMessage({
                    message: '認証コードが正しくありません。',
                    type: 'warning',
                })
            }
        }).catch(error=>{
            ElMessage({
                message: 'エラーが発生しました。',
                type: 'error',
            })
        })
    }
}
</script>