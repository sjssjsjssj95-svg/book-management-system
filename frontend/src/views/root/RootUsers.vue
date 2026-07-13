<template>
    <div class="common-layout" style="width: 100%;height: calc(100vh);">
        <el-container style="height: 100%;">
            <el-aside width="calc(15vw)" style="background-color: #2f4050;height: 100%;text-align: center;">
                
                <Router/>
                
            </el-aside>
            <el-container>
                <el-header style="display: flex; justify-content: center;align-items: center;">
                    <h3>用户管理</h3>
                </el-header>

                <el-main style="background-color: #E0E0E0;" v-loading="mainLoading" >
                    <el-table :data="tableData" style="width: 100%;margin-top: 10px;height: 90%;" border  empty-text="暂无数据">
                        <el-table-column label="头像"  width="140">
                            <template #default="scope">
                                <img style="width: 100%;" :src="scope.row.avatar">
                            </template>
                        </el-table-column>
                        <el-table-column width="100" prop="account" label="账户"/>
                        <el-table-column width="100" prop="name" label="昵称"/>
                        <el-table-column prop="email" label="邮箱"/>
                        <el-table-column width="100" prop="status" label="状态"/>
                        <el-table-column width="100" prop="created_at" label="创建时间"/>
                        <el-table-column width="100" prop="updated_at" label="更新时间"/>
                        <el-table-column width="100" prop="banded_at" label="禁用时间"/>
                        <el-table-column width="100" prop="ban_why" label="禁用理由"/>
                        <el-table-column label="操作" width="200">
                            <template #default="scope">
                                <el-popconfirm
                                    class="box-item"
                                    title="确定要禁用该用户吗"
                                    placement="top-start"
                                    confirm-button-text="确认"
                                    cancel-button-text="取消"
                                    @confirm="bandUser(scope.$index)"
                                    v-if="!scope.row.ban"
                                >
                                    <template #reference>
                                    <el-button type="warning">禁用</el-button>
                                    </template>
                                </el-popconfirm>

                                <el-popconfirm
                                    class="box-item"
                                    title="确定要禁用该用户吗"
                                    placement="top-start"
                                    confirm-button-text="确认"
                                    cancel-button-text="取消"
                                    @confirm="banOffUserButton(scope.$index)"
                                    v-if="scope.row.ban"
                                >
                                    <template #reference>
                                    <el-button type="success">解封</el-button>
                                    </template>
                                </el-popconfirm>

                                <el-popconfirm
                                    class="box-item"
                                    title="确定要删除该用户吗"
                                    placement="top-end"
                                    confirm-button-text="确认"
                                    cancel-button-text="取消"
                                    @confirm="deleteUserButton(scope.$index)"
                                >
                                    <template #reference>
                                    <el-button type="danger">删除</el-button>
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
                status = '暂未禁用'
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
    ElMessageBox.prompt('请输入封禁理由', '理由', {
        confirmButtonText: '确认',
        cancelButtonText: '取消',
        inputErrorMessage: 'Invalid Email',
    })
    .then(({ value }) => {
        if (value==''||value==null) {
            ElMessage({
                message: '请输入封禁理由!',
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
                        message: '封禁成功!',
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
            getAllUserAction()
        }
    })
    .catch(() => {
        ElMessage({
            type: 'info',
            message: '已取消',
        })
    })
}

const deleteUserButton = (a) =>{
    ElMessageBox.prompt('请输入删除理由', '请注意：删除不可逆，请慎重', {
        confirmButtonText: '确认',
        cancelButtonText: '取消',
        inputErrorMessage: 'Invalid Email',
    })
    .then(({ value }) => {
        if (value==''||value==null) {
            ElMessage({
                message: '请输入封禁理由!',
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
                        message: '封禁成功!',
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
            getAllUserAction()
        }
    })
    .catch(() => {
        ElMessage({
            type: 'info',
            message: '已取消',
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
                message: '解封成功!',
                type: 'success',
            })
        }
    })
    getAllUserAction()
}
</script>