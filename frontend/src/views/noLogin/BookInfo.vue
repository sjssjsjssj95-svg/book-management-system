<template>
    <router style="width: 100%;height: calc(10vh);"/>

    <div style="margin-left: 15%;margin-right: 15%;">
        <div v-loading="loading" style="height: calc(77vh);margin-top: calc(3vh);overflow-y: auto;">
            <el-row style="height: 60%;">
                <el-col :span="8" style="text-align: center;">
                    <el-image style="width: calc(18vw); height: calc(45vh)" :src="bookImg" :fit="fit" />
                </el-col>
                <el-col :span="16">
                    <h2>书名：{{ bookInfo[1] }}</h2>
                    <h3>作者: {{ bookInfo[3] }}</h3>
                    <h3>类型: {{ bookInfo[2] }}</h3>
                    <span>总数：{{ bookInfo[5] }}；剩余数量: {{ bookInfo[6] }}</span>
                    <br>
                    <el-button style="margin-top: 10px;" type="warning" @click="back">返回</el-button>
                    <el-tooltip content="暂无库存" placement="top">
                        <el-button style="margin-top: 10px;" type="danger" v-if="!bookInfo[4]" disabled>不可借阅</el-button>
                    </el-tooltip>
                    <el-tooltip content="请先登录" placement="top">
                        <el-button style="margin-top: 10px;" type="primary" v-if="bookInfo[4]" disabled>可以借阅</el-button>
                    </el-tooltip>
                </el-col>
            </el-row>

            <div>
                <p>图书介绍：</p>
                <p style="text-indent: 2em;" v-html="bookInfo[7]"></p>
            </div>
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
import { getBookInfo } from '@/api/book';

const routerJump = useRouter()
const loading = ref(true)
const bookId = ref(0)
const route = useRoute()
const bookImg = ref('')
const bookInfo = reactive({})

bookId.value = route.query.id

getBookInfo(bookId.value).then(res=>{
    bookImg.value = 'http://127.0.0.1:8000/storage/Book/'+res.cover+'.jpg'
    bookInfo[1] = res.title
    bookInfo[2] = res.category
    bookInfo[3] = res.author
    bookInfo[4] = res.is_available
    bookInfo[5] = res.stock
    bookInfo[6] = res.available
    bookInfo[7] = res.description
    loading.value=false
})

const back = () => {
    routerJump.push({
        path: '/book',
    })
}
</script>