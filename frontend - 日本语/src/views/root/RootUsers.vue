<template>
    <div class="common-layout" style="width: 100%;height: calc(100vh);">
        <el-container style="height: 100%;">
            <el-aside width="calc(15vw)" style="background-color: #2f4050;height: 100%;text-align: center;">
                
                <Router/>
                
            </el-aside>
            <el-container>
                <el-header style="display: flex; justify-content: center;align-items: center;">
                    <h3>ユーザー管理</h3>
                </el-header>

                <el-main style="background-color: #E0E0E0;" v-loading="mainLoading" >
                    <el-table :data="tableData" style="width: 100%;margin-top: 10px;height: 90%;" border  empty-text="データがありません">
                        <el-table-column label="プロフィール画像"  width="140">
                            <template #default="scope">
                                <img style="width: 100%;" :src="scope.row.avatar">
                            </template>
                        </el-table-column>
                        <el-table-column width="100" prop="account" label="アカウント"/>
                        <el-table-column width="100" prop="name" label="ニックネーム"/>
                        <el-table-column prop="email" label="メールアドレス"/>
                        <el-table-column width="100" prop="status" label="ステータス"/>
                        <el-table-column width="100" prop="created_at" label="作成日時"/>
                        <el-table-column width="100" prop="updated_at" label="更新日時"/>
                        <el-table-column width="100" prop="banded_at" label="停止日時"/>
                        <el-table-column width="100" prop="ban_why" label="停止理由"/>
                        <el-table-column label="操作" width="200">
                            <template #default="scope">
                                <el-popconfirm
                                    class="box-item"
                                    title="このユーザーを停止しますか？"
                                    placement="top-start"
                                    confirm-button-text="確認"
                                    cancel-button-text="キャンセル"
                                    @confirm="bandUser(scope.$index)"
                                    v-if="!scope.row.ban"
                                >
                                    <template #reference>
                                    <el-button type="warning">利用停止</el-button>
                                    </template>
                                </el-popconfirm>

                                <el-popconfirm
                                    class="box-item"
                                    title="このユーザーを停止しますか？"
                                    placement="top-start"
                                    confirm-button-text="確認"
                                    cancel-button-text="キャンセル"
                                    @confirm="banOffUserButton(scope.$index)"
                                    v-if="scope.row.ban"
                                >
                                    <template #reference>
                                    <el-button type="success">利用停止を解除</el-button>
                                    </template>
                                </el-popconfirm>

                                <el-popconfirm
                                    class="box-item"
                                    title="このユーザーを削除しますか？"
                                    placement="top-end"
                                    confirm-button-text="確認"
                                    cancel-button-text="キャンセル"
                                    @confirm="deleteUserButton(scope.$index)"
                                >
                                    <template #reference>
                                    <el-button type="danger">削除</el-button>
                                    </template>
                                </el-popconfirm>
                            </template>
                        </el-table-column>
                    </el-table>

                </el-main>
            </el-container>
        </el-container>
    </div>
</template>

<script setup>
import Router from './components/Router.vue';
import { ref , reactive } from 'vue';
import { getAllUser , banUser , banOffUser , deleteUser } from '@/api/root.js';
import { ElMessage , ElMessageBox } from 'element-plus'
const mainLoading = ref(true)

const token = ref('')
const tableData = ref([])

token.value = localStorage.getItem('root_token')

const getAllUserAction = () => {
    mainLoading.value=true
    getAllUser(token.value).then(res=>{
        let data = []
        for (let i=0;i<res.length;i++){
            let status = ''
            let ban = ''
            let ban_why = ''
            if (res[i].status=='正常') {
                status = '停止されていません'
                ban = false
                ban_why = status
            }
            else {
                status = res[i].baned_at
                ban = true
                ban_why = res[i].ban_why
            }
            data.push({
                avatar : 'http://127.0.0.1:8000/storage/'+res[i].avatar,
                account : res[i].username,
                name : res[i].nickname,
                email : res[i].email,
                status : res[i].status,
                created_at : res[i].created_at,
                updated_at : res[i].updated_at,
                banded_at : status,
                id : res[i].id,
                ban : ban,
                ban_why : ban_why
            })
        }
        tableData.value = data
        mainLoading.value=false
    })
}

getAllUserAction()

const bandUser = (a) =>{
    ElMessageBox.prompt('停止理由を入力してください', '理由', {
        confirmButtonText: '確認',
        cancelButtonText: 'キャンセル',
        inputErrorMessage: 'Invalid Email',
    })
    .then(({ value }) => {
        if (value==''||value==null) {
            ElMessage({
                message: '停止理由を入力してください。',
                type: 'waning',
            })
        }
        else {
            mainLoading.value=true
            let data={
                id : tableData.value[a].id,
                code : value
            }
            banUser(data,token.value).then(res=>{
                if (res==200){
                    ElMessage({
                        message: '利用を停止しました。',
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
            getAllUserAction()
        }
    })
    .catch(() => {
        ElMessage({
            type: 'info',
            message: 'キャンセルしました',
        })
    })
}

const deleteUserButton = (a) =>{
    ElMessageBox.prompt('削除理由を入力してください', 'ご注意：削除したデータは復元できません。', {
        confirmButtonText: '確認',
        cancelButtonText: 'キャンセル',
        inputErrorMessage: 'Invalid Email',
    })
    .then(({ value }) => {
        if (value==''||value==null) {
            ElMessage({
                message: '停止理由を入力してください。',
                type: 'waning',
            })
        }
        else {
            mainLoading.value=true
            let data={
                id : tableData.value[a].id,
                code : value
            }
            deleteUser(data,token.value).then(res=>{
                if (res==200){
                    ElMessage({
                        message: '利用を停止しました。',
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
            getAllUserAction()
        }
    })
    .catch(() => {
        ElMessage({
            type: 'info',
            message: 'キャンセルしました',
        })
    })
}

const banOffUserButton = (a) =>{
    mainLoading.value=true
    let data={
        id : tableData.value[a].id,
    }
    banOffUser(data,token.value).then(res=>{
        if (res==200){
            ElMessage({
                message: '利用停止を解除しました。',
                type: 'success',
            })
        }
    })
    getAllUserAction()
}
</script>