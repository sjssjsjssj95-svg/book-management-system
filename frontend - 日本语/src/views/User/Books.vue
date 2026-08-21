<template>
   <router style="width: calc(99vw);height: calc(10vh);"/>
   <div style="margin-top: 10px;margin-right: 15%;margin-left: 15%;text-align: center;overflow-y: auto;height: calc(85vh);margin-bottom: 20px;" v-loading="loading">
    <!--検索ボックス-->
    <div>
        <el-select v-model="value" placeholder="カテゴリ" style="margin-right: 5px;width: calc(10vw);" @change="selectType">
            <el-option
                v-for="item in options"
                :key="item.value"
                :label="item.label"
                :value="item.value"
            />
        </el-select>

        <el-input
            v-model="selectInput"
            style="width: 30%"
            placeholder="検索内容を入力してください"
            class="input-with-select"
        >
            <template #prepend>
                <el-select v-model="selectTypeBut" placeholder="Select" style="width: 115px">
                <el-option label="書籍名" value="title" />
                <el-option label="著者" value="author" />
                </el-select>
            </template>
        </el-input>

        <el-button type="primary" style="margin-left: 5px;width: calc(5vw);" @click="selectByTitle">検索</el-button>
        <el-button type="success" style="margin-left: 5px;width: calc(10vw);" @click="getAllBooksFunction">検索条件をクリア</el-button>
    </div>
    
    <el-row>
        <el-col :span="6" v-for="value in booksLength" style="margin-top: 10px;">
            <el-card style="max-width: calc(15vw)" >
                <template #header>
                    <span style="font-weight: bolder;font-size: larger;">{{ bookName[value] }}</span>
                    <br>
                    <span>{{ bookAu[value] }}</span>
                    <br>
                    <el-tag type="primary">{{ bookType[value] }}</el-tag>
                    <el-tag type="success" style="margin-left: 5px;" v-if="bookAvailable[value]">貸出可能</el-tag>
                    <el-tag type="danger" style="margin-left: 5px;" v-if="!bookAvailable[value]">在庫なし</el-tag>
                </template>
                <img
                    :src="bookImg[value]"
                    style="width: calc(12vw);height: calc(30vh);"
                    :id="bookId[value]" @click="getBookInfo"
                />
                <el-button style="width: 100%;margin-top: 10px;" :id="bookId[value]" @click="getBookInfo">
                    <span :id="bookId[value]">詳細を見る</span>
                </el-button>
            </el-card>
        </el-col>
    </el-row>
   </div>


   <el-footer style="width: 100%;background-color: #DEDEDE;">
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
import { getAllBooks , getBooksByCategoty , getBooksByTitle } from '@/api/book';
import { getAllCategoty } from '@/api/categoty';
import { ref , reactive } from 'vue';
import { ElMessage } from 'element-plus'

import { useRouter } from 'vue-router'
const routerJump = useRouter()

const loading = ref(true)
const booksLength = ref(0)
const bookName = reactive({})
const bookImg = reactive({})
const bookType = reactive({})
const bookAu = reactive({})
const bookAvailable = reactive({})
const bookId = reactive({})
const value = ref(0)

const options = ref({})

const selectTypeBut = ref('title')
const selectInput = ref('')

getAllCategoty().then(res=>{
    for (let i=0;i<res.length;i++){
        options.value = res
    }
})

const getAllBooksFunction = () => {
    selectInput.value=''
    loading.value=true
    getAllBooks().then(res=>{
        booksLength.value = res.length
        for (let i=1;i<=res.length;i++) {
            bookId[i] = res[i-1].id
            bookName[i] = res[i-1].title
            bookImg[i] = 'http://127.0.0.1:8000/storage/Book/'+res[i-1].cover+'.jpg'
            bookType[i] = res[i-1].category
            bookAu[i] = res[i-1].author
            bookAvailable[i] = res[i-1].is_available
        }
        loading.value=false
    })
}

getAllBooksFunction()

const getBookInfo = (a) => {
    routerJump.push({
        path: '/user/book/info',
        query: { id: a.target.id }
    })
}

const selectType = (value) => {
    loading.value=true
    getBooksByCategoty(value).then(res=>{
        booksLength.value = res.length
        for (let i=1;i<=res.length;i++) {
            bookId[i] = res[i-1].id
            bookName[i] = res[i-1].title
            bookImg[i] = 'http://127.0.0.1:8000/storage/Book/'+res[i-1].cover+'.jpg'
            bookType[i] = res[i-1].category
            bookAu[i] = res[i-1].author
            bookAvailable[i] = res[i-1].is_available
        }
        loading.value=false
    })
}

const selectByTitle = () => {
    loading.value=true
    value.value = 0
    if (selectInput.value==''||selectInput.value==null) {
        ElMessage({
            message: 'を入力してください。',
            type: 'warning',
        })
    }
    else {
        getBooksByTitle(selectTypeBut.value,selectInput.value).then(res=>{
            if (res==0) {
                ElMessage({
                    message: '見つかりませんでした。',
                    type: 'warning',
                })
            }
            else {
                booksLength.value = res.length
                for (let i=1;i<=res.length;i++) {
                    bookId[i] = res[i-1].id
                    bookName[i] = res[i-1].title
                    bookImg[i] = 'http://127.0.0.1:8000/storage/Book/'+res[i-1].cover+'.jpg'
                    bookType[i] = res[i-1].category
                    bookAu[i] = res[i-1].author
                    bookAvailable[i] = res[i-1].is_available
                }
            }
        })
    }
    loading.value=false
}
</script>