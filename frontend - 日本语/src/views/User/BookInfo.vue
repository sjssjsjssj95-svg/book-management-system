<template>
    <router style="width: calc(100vw);height: calc(10vh);"/>

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
                    <el-button style="margin-top: 10px;" type="primary" v-if="bookInfo[4]" @click="borrowBookBut">貸出可能</el-button>
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
import { ElMessage, ElMessageBox } from 'element-plus'
import { borrowBook } from '@/api/borrow';

const routerJump = useRouter()
const loading = ref(true)
const bookId = ref(0)
const route = useRoute()
const bookImg = ref('')
const bookInfo = reactive({})

const token = ref('')
token.value = localStorage.getItem('token')

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
        path: '/user/books',
    })
}

//時刻をフォーマット
const formatDate = (date) => {
  const year = date.getFullYear()
  const month = String(date.getMonth() + 1).padStart(2, '0')
  const day = String(date.getDate()).padStart(2, '0')

  return `${year}-${month}-${day}`
}

const borrowBookBut = () => {
    const nowDay = new Date()
    const backDay = new Date()
    backDay.setDate(backDay.getDate()+10)
    ElMessageBox.confirm(
    '現在時刻：'+formatDate(nowDay)+'、返却期限：'+formatDate(backDay)+'。この書籍を借りますか？',
    '貸出書籍：'+bookInfo[1],
    {
      confirmButtonText: '確認',
      cancelButtonText: 'キャンセル',
      type: 'warning',
    }
  )
    .then(() => {
        const data = {
            book_id: bookId.value,
        }
        borrowBook(data,token.value).then(res=>{
            if(res.code==200) {
                ElMessage({
                    message: '貸出しました。確認メールを送信しました。',
                    type: 'success',
                })
                bookInfo[6]=bookInfo[6]-1
            }
            else if (res.code==4000) {
                ElMessage({
                    message: 'この書籍は在庫切れです。',
                    type: 'warning',
                })
            }
            else if (res.code==4002) {
                ElMessage({
                    message: 'この書籍はすでに貸出中です。',
                    type: 'warning',
                })
            }
            else {
                ElMessage({
                    message: 'エラー',
                    type: 'warning',
                })
            }
        })
    })
}
</script>