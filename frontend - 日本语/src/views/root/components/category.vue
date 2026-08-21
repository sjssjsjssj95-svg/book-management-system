<template>
    <h2>書籍カテゴリ</h2>
    <el-button plain @click="open" type="primary" style="width: 100%;">新しいカテゴリを追加</el-button>
    <el-table :data="tableData" style="width: 100%" v-loading="mainLoading">
        <el-table-column prop="name" label="カテゴリ名" />

        <el-table-column label="操作">
            <template #default="scope">
                <el-popconfirm
                    class="box-item"
                    title="このカテゴリを削除しますか"
                    placement="top-end"
                    confirm-button-text="確認"
                    cancel-button-text="キャンセル"
                    @confirm="deleteCategoryButton(scope.$index)"
                >
                    <template #reference>
                    <el-button type="danger">削除</el-button>
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
                message: '削除しました',
                type: 'success',
            })
            getAll()
       }
       else {
            ElMessage({
                message: 'エラーが発生しました。もう一度お試しください',
                type: 'error',
            })
            getAll()
       }
    })
}

const open = () => {
  ElMessageBox.prompt('新しいカテゴリ名を入力してください', '新しいカテゴリ', {
    confirmButtonText: '追加',
    cancelButtonText: 'キャンセル',
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
                    message: '追加しました'
                })
                getAll()
            }
            else {
                ElMessage({
                    type: 'error',
                    message: 'エラーが発生しました。もう一度お試しください。'
                })
                getAll()
            }
        })
    })
}
</script>
