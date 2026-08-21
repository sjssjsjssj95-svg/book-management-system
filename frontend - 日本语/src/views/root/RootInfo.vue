<template>
    <div class="common-layout" style="width: 100%;height: calc(100vh);">
        <el-container style="height: 100%;">
            <el-aside width="calc(15vw)" style="background-color: #2f4050;height: 100%;text-align: center;">
                
                <Router/>
                
            </el-aside>
            <el-container>
                <el-header style="display: flex; justify-content: center;align-items: center;">
                    <h3>お知らせ管理</h3>
                </el-header>

                <el-main style="background-color: #E0E0E0;" v-loading="mainLoading" >
                    <div style="border-radius: 5%;margin-left: 5%;margin-right: 5%;background-color: white;min-height: 100%;">
                        <div style="height: 1px;"></div>
                        <div>
                            <el-row :gutter="20">
                                <el-col :span="8">
                                
                                </el-col>
                                <el-col :span="8" style="text-align: center;">
                                    <h4>すべてのお知らせ</h4>
                                </el-col>
                                <el-col :span="8" style="display: flex; justify-content: center;align-items: center;">
                                    <el-button type="primary" @click="drawer=true">お知らせを追加</el-button>   
                                </el-col>
                            </el-row>
                        </div>
                        <el-collapse v-model="activeName" accordion style="margin-right: 5%;margin-left: 5%;">
                            <el-collapse-item v-for="value in infoLength" :title="infoTitle[value]" :name="value">
                                <div v-html="infoContent[value]"></div>
                                <p style="text-align: right;">{{ infoTime[value] }}</p>
                                <div style="text-align: right;">
                                    <el-popconfirm
                                        class="box-item"
                                        title="削除してもよろしいですか？"
                                        placement="top-end"
                                        confirm-button-text="確認"
                                        cancel-button-text="キャンセル"
                                        @confirm="deleteInfoAction(value)"
                                    >
                                        <template #reference>
                                            <el-button type="danger" size="small">削除</el-button>
                                        </template>
                                    </el-popconfirm>
                                </div>
                            </el-collapse-item>
                        </el-collapse>
                    </div>
                </el-main>
            </el-container>
        </el-container>
    </div>


    <el-drawer v-model="drawer" title="I am the title" :with-header="false" size="60%">
        <InfoInput @success="handleSuccess" style="height: 90%;"/>
    </el-drawer>
</template>

<script setup>
import Router from './components/Router.vue';
import { ref , reactive } from 'vue';
import { deleteInfo } from '@/api/root.js';
import { getAllInfo } from '@/api/info.js';
import InfoInput from './components/InfoInput.vue';
import { ElMessage, ElMessageBox } from 'element-plus'
import { fa } from 'element-plus/es/locale/index.mjs';


const mainLoading = ref(true)
const activeName = ref('')
const drawer = ref(false)
const token = ref('')

token.value = localStorage.getItem('root_token')

const infoTitle = reactive({})
const infoTime = reactive({})
const infoContent = reactive({})
const infoLength = ref(0)
const infoId = reactive({})


const getAllInfoAction = () => {
    mainLoading.value = true
    activeName.value=''
    Object.keys(infoContent).forEach(key => delete infoContent[key])
    Object.keys(infoTitle).forEach(key => delete infoTitle[key])
    Object.keys(infoTime).forEach(key => delete infoTime[key])
    Object.keys(infoId).forEach(key => delete infoId[key])

    getAllInfo().then(res => {

        infoLength.value = res.length

        for (let i = 1; i <= res.length; i++) {
            infoContent[i] = res[i - 1].content
            infoTime[i] = res[i - 1].created_at
            infoTitle[i] = res[i - 1].title
            infoId[i] = res[i - 1].id
        }

        mainLoading.value = false
    })
}

getAllInfoAction()

const deleteInfoAction = (a) => {
    let data ={
        id :infoId[a]
    }
    deleteInfo(data,token.value).then(res=>{
        if (res.code==200){
            ElMessage({
                message: '削除しました',
                type: 'success',
            })
            getAllInfoAction()
        }
        else {
            ElMessage({
                message: 'エラーが発生しました。もう一度お試しください。',
                type: 'error',
            })
        }
    })
}


const handleSuccess = () => {
    drawer.value = false      // ドロワーを閉じる（任意）
    getAllInfoAction()         // お知らせを再取得
}
</script>