<template>
    <mainRouter style="width: calc(99vw);height: calc(10vh);"/>

    <div class="parent" >
         <el-form :model="form" label-width="auto" :class="animass">
            <h2 style="text-align: center;width: 100%;" >找回密码</h2>

            <el-form-item label="邮箱">
                <el-input v-model="form.email"  placeholder="需要邮箱格式">
                        <template #append>
                            <el-button type="primary" @click="sendCode">发送验证码</el-button>
                        </template>
                </el-input>
            </el-form-item>
            

            <el-form-item label="验证码">
                <el-input v-model="form.code" type="number">
                </el-input>
            </el-form-item>

            <el-form-item label="新密码">
                <el-input v-model="form.password" type="password" show-password placeholder="最少需要6个字符"/>
            </el-form-item>

            <el-form-item>
                <div style="text-align: center;width: 100%;">
                    <el-button type="primary"  @click="registerSend">立马重置</el-button>
                    <el-button @click="toLogin">返回登录</el-button>
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
import { findPasswordSned , findPasswrd } from '@/api/user';
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
            message: '请先获取验证码',
            type: 'warning',
        })
    }
    else if (form.code==''||form.password=='') {
        ElMessage({
            message: '仍有未填项目',
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

const sendCode = () => {
   if (form.email==''){
    ElMessage({
        message: '请填写email',
        type: 'warning',
    })
   }
   else {
     findPasswordSned(form.email).then(res=>{
        if ( res.code == 200) {
            ElMessage({
                message: '发送成功，请注意查看邮箱.',
                type: 'success',
            })
            confirEmail.value = form.email
        }
        else if ( res.code == 1000) {
             ElMessage({
                message: '请一分钟后重试',
                type: 'warning',
            })
        }
        else if ( res.code == 1001) {
             ElMessage({
                message: '邮箱已被注册',
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
  justify-content: center; /* 水平居中 */
  align-items: center;    /* 垂直居中 */
}
</style>