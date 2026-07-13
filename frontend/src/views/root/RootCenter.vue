<template>
    <div class="common-layout" style="width: 100%;height: calc(100vh);">
        <el-container style="height: 100%;">
            <el-aside width="calc(15vw)" style="background-color: #2f4050;height: 100%;text-align: center;">
                
                <Router/>
                
            </el-aside>
            <el-container>
                <el-header style="display: flex; justify-content: center;align-items: center;">
                    <h3>个人中心</h3>
                </el-header>

                <el-main style="background-color: #E0E0E0;display: flex; justify-content: center;align-items: center;" v-loading="mainLoading" >
                    <div>
                        <el-descriptions title="Root Info" border="true" size="large" column="2">
                            <el-descriptions-item label="昵称">{{ rootInfo[0] }}</el-descriptions-item>
                            <el-descriptions-item label="注册天数">{{ rootInfo[3] }}天</el-descriptions-item>
                            <el-descriptions-item label="状态">
                                <el-tag v-if="rootInfo[2]">正常</el-tag>
                                <el-tag type="danger" v-if="!rootInfo[2]">禁用</el-tag>
                            </el-descriptions-item>
                            <el-descriptions-item label="邮箱">{{ rootInfo[1] }}</el-descriptions-item>
                        </el-descriptions>
                        <el-button type="primary" style="width: 48%;margin-top: 10px;">修改密码</el-button>
                        <el-button type="primary" style="width: 48%;margin-left: 4%;margin-top: 10px;">修改邮箱</el-button>
                    </div>
                </el-main>
            </el-container>
        </el-container>
    </div>
</template>

<script setup>
import Router from './components/Router.vue';
import { ref , reactive } from 'vue';
import { getRootInfo } from '@/api/root.js';


const mainLoading = ref(true)
const rootInfo = reactive({})

const token = ref('')

token.value = localStorage.getItem('root_token')

getRootInfo(token.value).then(res=>{
    rootInfo[0] = res.nickname
    rootInfo[1] = res.email
    rootInfo[3] = res.register_days
    if (res.status==1) {
        rootInfo[2]=true
    }
    else {
        rootInfo[2]=false
    }
    mainLoading.value=false
})
</script>