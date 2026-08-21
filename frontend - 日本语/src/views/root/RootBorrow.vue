<template>
    <div class="common-layout" style="width: 100%;height: calc(100vh);">
        <el-container style="height: 100%;">
            <el-aside width="calc(15vw)" style="background-color: #2f4050;height: 100%;text-align: center;">
                
                <Router/>
                
            </el-aside>
            <el-container>
                <el-header style="display: flex; justify-content: center;align-items: center;">
                    <h3>貸出管理</h3>
                </el-header>

                <el-main style="background-color: #E0E0E0;" v-loading="mainLoading" >
                    <div style="width: 100%;text-align: right;">
                        <el-radio-group v-model="staut" style="height: calc(10vh);" @change="selectStuat">
                            <el-row>
                                <el-radio-button value="0" size="large" border>貸出中</el-radio-button>
                                <el-radio-button value="2" size="large" border>期限超過</el-radio-button>
                                <el-radio-button value="3" size="large" border>紛失</el-radio-button>
                                <el-radio-button value="1" size="large" border>返却済み</el-radio-button>
                            </el-row>
                        </el-radio-group>
                    </div>

                    <div>
                        <el-table :data="tableData" style="width: 100%;margin-top: 10px;height: 90%;" border  empty-text="データがありません">
                            <el-table-column label="表紙"  width="140">
                                <template #default="scope">
                                    <img style="width: 100%;" :src="scope.row.img">
                                </template>
                            </el-table-column>
                            <el-table-column prop="name" label="書籍名"/>
                            <el-table-column prop="author" label="著者"/>
                            <el-table-column label="借出者のプロフィール画像"  width="140">
                                <template #default="scope">
                                    <img style="width: 100%;" :src="scope.row.avatar">
                                </template>
                            </el-table-column>
                            <el-table-column prop="username" label="借出者アカウント"/>
                            <el-table-column prop="creatTime" label="貸出日時"/>
                            <el-table-column prop="dueTime" :label="ret"/>
                            <el-table-column label="操作" width="200">
                                <template #default="scope">
                                    <el-popconfirm
                                        class="box-item"
                                        :title="butText"
                                        placement="top-start"
                                        confirm-button-text="確認"
                                        cancel-button-text="キャンセル"
                                        @confirm="rootConfirmButton(scope.$index)"
                                    >
                                        <template #reference>
                                            <el-button v-if="retBut" type="success" :disabled="!scope.row.ban">返却済み</el-button>
                                            <el-button v-if="!retBut" type="warning">紛失</el-button>
                                        </template>
                                    </el-popconfirm>
                                </template>
                            </el-table-column>
                        </el-table>
                    </div>
                </el-main>
            </el-container>
        </el-container>
    </div>
</template>

<script setup>
import Router from './components/Router.vue';
import { ref , reactive } from 'vue';
import { getBorrowAll , rootConfirm } from '@/api/root.js';
import { ElMessage , ElMessageBox } from 'element-plus'

const mainLoading = ref(true)

const token = ref('')
const staut = ref('0')
const tableData = ref([])
const ret = ref('返却期限')
const retBut = ref(false)
const butText = ref('この書籍を紛失したことを確認しますか？')
token.value = localStorage.getItem('root_token')

const getAllBooksByStaut = () => {
    mainLoading.value = true
    let data = {
        status : staut.value
    }
    getBorrowAll(data,token.value).then(res=>{
        let data = []
        let time = ''
        let ban = true
        for (let i=0;i<res.length;i++){
            let resi = res[i]
            if (staut.value=='1'||staut.value=='3') {
                time = resi.return_time
                if (resi.root_confirm==0) {
                    ban = true
                }
                else {
                    ban = false
                }
            }
            else {
                time = resi.due_time
                ban = false
            }
            data.push({
                author : resi.book.author,
                name : resi.book.title,
                img : 'http://127.0.0.1:8000/storage/Book/'+resi.book.cover+'.jpg',
                creatTime : resi.created_at,
                dueTime : time,
                username : resi.user.username,
                avatar : 'http://127.0.0.1:8000/storage/'+resi.user.avatar,
                ban : ban,
                id : resi.id
            })
        }
        tableData.value = data
    })
    window.setTimeout(()=>{
        mainLoading.value = false
    },500)
}

getAllBooksByStaut()

const selectStuat = () => {
    if (staut.value=='1'||staut.value=='3') {
        if (staut.value=='1') {
            ret.value = '返却日時'
        }
        else {
            ret.value = '紛失日時'
        }
        retBut.value = true
        butText.value = 'この書籍が返却されたことを確認しますか？'
    }
    else {
        ret.value = '返却期限'
        retBut.value = false
        butText.value = 'この書籍を紛失したことを確認しますか？'
    }
    getAllBooksByStaut()
}

const rootConfirmButton = (a) =>{
    let data = {
        id : tableData.value[a].id,
        statu : staut.value
    }
    rootConfirm(data,token.value).then(res=>{
        if (res.msg==200) {
            ElMessage({
                message: '確認しました。',
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
    getAllBooksByStaut()
}
</script>