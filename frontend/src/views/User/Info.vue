<template>
    <router style="width: calc(100vw);height: calc(10vh);"/>

    <div style="margin-left: 15%;margin-right: 15%;">
        <div v-loading="loading" style="height: calc(77vh);margin-top: calc(3vh);overflow-y: auto;">
            <el-collapse v-model="activeName" accordion>
                <el-collapse-item v-for="value in infoLength" :title="infoTitle[value]" :name="value">
                    <div v-html="infoContent[value]"></div>
                    <p style="text-align: right;">{{ infoTime[value] }}</p>
                </el-collapse-item>
            </el-collapse>
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
</template>

<script setup>
import router from './components/router.vue';
import { useRoute , useRouter } from 'vue-router'
import { ref , reactive } from 'vue';
import { getAllInfo } from '@/api/info';

const infoTitle = reactive({})
const infoTime = reactive({})
const infoContent = reactive({})
const infoLength = ref(0)


getAllInfo().then(res=>{
    infoLength.value=res.length
    for (let i=1;i<=res.length;i++){
        infoContent[i] = res[i-1].content
        infoTime[i] = res[i-1].created_at
        infoTitle[i] = res[i-1].title
    }
})
</script>