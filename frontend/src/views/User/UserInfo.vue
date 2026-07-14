<template>
    <router style="width: calc(100vw);height: calc(10vh);"/>

    <div style="margin-left: 15%;margin-right: 15%;min-height: calc(75vh);" 
            v-loading="loading">
        <el-descriptions
            title="用户信息"
            direction="vertical"
            border
            style="margin-top: calc(5vh)"
        >
            <el-descriptions-item
            :rowspan="2"
            :width="140"
            label="头像"
            align="center"
            >
            <el-image
                style="width: 100px; height: 100px"
                :src="userinfo[4]"
                :preview-src-list="[userinfo[4]]"
                :initial-index="0"
            />
            </el-descriptions-item>
            <el-descriptions-item label="用户名">{{ userinfo[0] }}</el-descriptions-item>
            <el-descriptions-item label="昵称">{{ userinfo[1] }}</el-descriptions-item>
            <el-descriptions-item label="注册天数">{{ userinfo[2] }}</el-descriptions-item>
            <el-descriptions-item label="账户状态">
            <el-tag v-if="userinfo[5]">正常</el-tag>
            <el-tag type="danger" v-if="!userinfo[5]">禁用</el-tag>
            </el-descriptions-item>
            <el-descriptions-item label="邮件地址">
            {{ userinfo[3] }}
            </el-descriptions-item>
        </el-descriptions>
        
        <div style="text-align: right;margin-top: 10px;">
            <el-button type="primary" @click="openUpdateUserNameBut">修改昵称</el-button>
            <el-button type="info" @click="dialogVisible=true">修改头像</el-button>
            <el-button type="warning" @click="update_email=true">修改邮箱</el-button>
            <el-button type="danger" @click="update_password=true">修改密码</el-button>
        </div>
    </div>


    <el-footer style="width: 100%;background-color: #DEDEDE;height: calc(10vh);">
    <div style=" display: flex;
                  align-items: center;     /* 垂直居中 */
                  justify-content: center; /* 水平居中（如果需要） */
                  height: 100%; /* 水平居中 */">
      <el-text>图书馆管理系统</el-text>
    </div>
  </el-footer>


  <!--上传文件-->
  <el-dialog
    v-model="dialogVisible"
    title="上传头像"
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
        <el-button type="primary">选择头像</el-button>
        </template>
        <el-button class="ml-3" type="success" @click="submitUpload" style="margin-left: 10px;">
        上传
        </el-button>
        <template #tip>
        <div class="el-upload__tip text-red">
            只能选择一张，多选的图片会覆盖上一张
        </div>
        </template>
    </el-upload>
  </el-dialog>

  <!--修改密码-->
  <el-dialog
    v-model="update_password"
    title="修改密码"
    width="500"
    :before-close="handleClose"
  >
    <p>旧密码</p>
    <el-input v-model="updatePasswordOldPassword" style="width: 100%" placeholder="请输入旧密码" type="password" show-password/>
    <p>新密码</p>
    <el-input v-model="updatePasswordNewPassword" style="width: 100%" placeholder="请输入新密码" type="password" show-password/>
    <el-row :gutter="24" style="margin-top: 10px;">
        <el-col :span="12"><el-button type="primary" style="width: 100%;" @click="change_password">修改密码</el-button></el-col>
        <el-col :span="12"><el-button type="info" style="width: 100%;" @click="clearNewPassword">清空数据</el-button></el-col>
    </el-row>
  </el-dialog>

   <!--修改邮箱-->
  <el-dialog
    v-model="update_email"
    title="修改邮箱"
    width="500"
    :before-close="handleClose"
  >
    <p>新邮箱</p>
    <el-input v-model="new_email" style="width: 100%" placeholder="请输入邮箱"/>
    <p>验证码</p>
    <el-input v-model="new_code" style="width: 100%" placeholder="请输入验证码"/>
    <el-row :gutter="24" style="margin-top: 10px;">
        <el-col :span="12"><el-button type="primary" style="width: 100%;" @click="sendCode">发送验证码</el-button></el-col>
        <el-col :span="12"><el-button type="info" style="width: 100%;" @click="changeEmail">修改邮箱</el-button></el-col>
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
    userinfo[2] = res.register_days+'天'
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
  ElMessageBox.prompt('请输入新的昵称', '修改昵称', {
    confirmButtonText: '确认',
    cancelButtonText: '取消',
  })
    .then(({ value }) => {
      if(value==''){
        ElMessage({
            type: 'info',
            message: '昵称不能为空',
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
                    message: '修改成功',
                })
                userinfo[1] = value
            }
            else {
                ElMessage({
                    type: 'error',
                    message: '错误',
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
  if (rawFile.type !== 'image/jpeg') {
    ElMessage.error('目标文件不是图片!')
    return false
  } else if (rawFile.size / 1024 / 1024 > 2) {
    ElMessage.error('图片最大值为2mb!')
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
        message: '修改成功',
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
        message: '错误',
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
            message: '请输入密码.',
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
                    message: '旧密码错误.',
                    type: 'warning',
                })
            }
            else if(res.code==1) {
                ElMessage({
                    message: '修改成功.',
                    type: 'success',
                }) 
                clearNewPassword()
                update_password.value=false
            }
        }).catch(error=>{
            ElMessage({
                message: '错误，请注意密码最少需要6位.',
                type: 'error',
            })
        })
    }
}

const new_email_value = ref('')
const sendCode = () =>{
    if (new_email.value==''){
        ElMessage({
            message: '请输入邮箱.',
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
                    message: '发送成功，请注意查收.',
                    type: 'success',
                })
                new_email_value.value = new_email.value
            }
            else {
                ElMessage({
                    message: '错误，请注意邮箱形式.',
                    type: 'warning',
                })
            }
        }).catch(error=>{
            ElMessage({
                message: '错误，请注意邮箱形式.',
                type: 'error',
            })
        })
    }
}

const changeEmail = () =>{
    if (new_code==''||new_email_value==''){
        ElMessage({
            message: '请先获取验证码/请输入验证码.',
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
                    message: '修改成功.',
                    type: 'success',
                })
                update_email.value=false
                new_code.value=''
                new_email_value.value=''
                new_email.value=''
            }
            else {
                ElMessage({
                    message: '验证码错误.',
                    type: 'warning',
                })
            }
        }).catch(error=>{
            ElMessage({
                message: '错误.',
                type: 'error',
            })
        })
    }
}
</script>