<template>
    <el-menu
        :default-active="choose"
        class="el-menu-vertical-demo"
        style="background-color: #2f4050;"
        @select="handleSelect"
        text-color="#BFCBD9"
        active-text-color=" #409EFF"
    >
        <h3 style="color: white;">図書館管理画面</h3>
        <el-menu-item index="/root/table" :disabled="loginOrNot">
            <el-icon><Histogram /></el-icon>
            <span>ダッシュボード</span>
        </el-menu-item>
        <el-menu-item index="/root/books" :disabled="loginOrNot">
           <el-icon><Collection /></el-icon>
            <span>書籍管理</span>
        </el-menu-item>
        <el-menu-item index="/root/users" :disabled="loginOrNot">
            <el-icon><UserFilled /></el-icon>
            <span>ユーザー管理</span>
        </el-menu-item>
        <el-menu-item index="/root/borrow" :disabled="loginOrNot">
            <el-icon><List /></el-icon>
            <span>貸出管理</span>
        </el-menu-item>
        <el-menu-item index="/root/info" :disabled="loginOrNot">
            <el-icon><Notification /></el-icon>
            <span>お知らせ管理</span>
        </el-menu-item>
        <el-menu-item index="/root/center" :disabled="loginOrNot">
            <el-icon><Files /></el-icon>
            <span>マイページ</span>
        </el-menu-item>
    </el-menu>
    <el-popconfirm
        class="box-item"
        :title="buttonName"
        placement="top-start"
        confirm-button-text="確認"
        cancel-button-text="キャンセル"
        @confirm="buttonAcction"
    >
        <template #reference>
        <el-button type="danger" style="width: calc(15vw);height: calc(5vh);border-radius: 0;position: absolute;bottom: 0;left: 0;">{{ buttonName }}</el-button>
        </template>
    </el-popconfirm>
</template>

<script setup>
import { ref } from 'vue'
import { useRoute , useRouter } from 'vue-router'
import { ElMessage } from 'element-plus'
import { Histogram , Collection , UserFilled , List , Notification ,  Files } from '@element-plus/icons-vue'
import { logout , loginIO } from '@/api/root'

const route = useRoute()
const router = useRouter()
const choose = ref('/root/table')

const loginOrNot = ref(true)
const buttonName = ref('')

const token = ref('')
token.value = localStorage.getItem('token')

choose.value=route.path

token.value = localStorage.getItem('root_token')

if (choose.value=='/root/login') {
    buttonName.value='ホームに戻る'
}
else {
    loginOrNot.value=false
    buttonName.value='ログアウト'
}

const handleSelect = (key) => {
    router.push(key)
}

const buttonAcction = () => {
    if (choose.value=='/root/login') {
        router.push('/')
    }
    else {
        logout(token.value).then(res=>{
            if (res.code==200){
                ElMessage({
                    message: 'ログアウトしました。まもなく移動します。',
                    type: 'success',
                })
                window.setTimeout(()=>{
                    router.push('/')
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
</script>

<style>
.aside{
    height:100vh;
    display:flex;
    flex-direction:column;
}

.menu{
    flex:1;
}

.logout{
    height:55px;
}
</style>