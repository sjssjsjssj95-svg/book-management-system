<template>
    <h2>书本类型</h2>
    <el-button plain @click="open" type="primary" style="width: 100%;">添加新类别</el-button>
    <el-table :data="tableData" style="width: 100%" v-loading="mainLoading">
        <el-table-column prop="name" label="类别名称" />

        <el-table-column label="操作">
            <template #default="scope">
                <el-popconfirm
                    class="box-item"
                    title="确定要删除该类别吗"
                    placement="top-end"
                    confirm-button-text="确认"
                    cancel-button-text="取消"
                    @confirm="deleteCategoryButton(scope.$index)"
                >
                    <template #reference>
                    <el-button type="danger">删除</el-button>
                    </template>
                </el-popconfirm>
            </template>
        </el-table-column>
    </el-table>
</template>

<script setup>
'use strict'
import { getAllCategory , deleteCategory , addCategory } from '@/api/root';
import { ref } from 'vue';
import { ElMessage, ElMessageBox } from 'element-plus'

const token = ref('')
const tableData = ref([])
const mainLoading = ref(true)

token.value = localStorage.getItem('root_token')

const getAll = () => {
    getAllCategory(token.value).then(res=>{
        mainLoading.value=true
        let data = []
        for (let i=0;i<res.length;i++){
            data.push({
                id : res[i].id,
                name : res[i].name,
            })
        }
        tableData.value = data
        mainLoading.value=false
    })
}

getAll()

const deleteCategoryButton = (a) =>{
    mainLoading.value=true
    const data = {
        id: tableData.value[a].id,
    }
    deleteCategory(data,token.value).then(res=>{
       if (res.msg==200) {
            ElMessage({
                message: '删除成功',
                type: 'success',
            })
            getAll()
       }
       else {
            ElMessage({
                message: '错误请重试',
                type: 'error',
            })
            getAll()
       }
    })
}

const open = () => {
  ElMessageBox.prompt('请输入新类别名称', '新类别', {
    confirmButtonText: '添加',
    cancelButtonText: '取消',
  })
    .then(({ value }) => {
        mainLoading.value=true
        const data = {
            name : value,
        }
        addCategory(data,token.value).then(res=>{
            if (res.msg==200) {
                ElMessage({
                    type: 'success',
                    message: '添加成功'
                })
                getAll()
            }
            else {
                ElMessage({
                    type: 'error',
                    message: '错误，请重试'
                })
                getAll()
            }
        })
    })
}
</script>
