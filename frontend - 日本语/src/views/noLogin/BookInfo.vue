<template>
    <router style="width: 100%;height: calc(10vh);"/>

    <div style="margin-left: 15%;margin-right: 15%;">
        <div v-loading="loading" style="height: calc(77vh);margin-top: calc(3vh);overflow-y: auto;">
            <el-row style="height: 60%;">
                <el-col :span="8" style="text-align: center;">
                    <el-image style="width: calc(18vw); height: calc(45vh)" :src="bookImg" :fit="fit" />
                </el-col>
                <el-col :span="16">
                    <h2>書籍名：{{ bookInfo[1] }}</h2>
                    <h3>著者：{{ bookInfo[3] }}</h3>
                    <h3>カテゴリ：{{ bookInfo[2] }}</h3>
                    <span>総冊数：{{ bookInfo[5] }}；在庫数：{{ bookInfo[6] }}</span>
                    <br>
                    <el-button style="margin-top: 10px;" type="warning" @click="back">戻る</el-button>
                    <el-tooltip content="在庫なし" placement="top">
                        <el-button style="margin-top: 10px;" type="danger" v-if="!bookInfo[4]" disabled>貸出不可</el-button>
                    </el-tooltip>
                    <el-tooltip content="先にログインしてください" placement="top">
                        <el-button style="margin-top: 10px;" type="primary" v-if="bookInfo[4]" disabled>貸出可能</el-button>
                    </el-tooltip>
                </el-col>
            </el-row>

            <div>
                <p>書籍紹介：</p>
                <p style="text-indent: 2em;" v-html="bookInfo[7]"></p>
            </div>
        </div>
    </div>


    <el-footer style="width: 100%;background-color: #DEDEDE;height: calc(10vh);">
    <div style=" display: flex;
                  align-items: center;     /* 垂直方向に中央揃え */
                  justify-content: center; /* 水平方向に中央揃え（必要に応じて） */
                  height: 100%; /* 水平方向に中央揃え */">
      <el-text>図書館管理システム</el-text>
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