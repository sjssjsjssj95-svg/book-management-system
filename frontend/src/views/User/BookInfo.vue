<template>
    <router style="width: calc(100vw);height: calc(10vh);"/>

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
                    <el-button style="margin-top: 10px;" type="primary" v-if="bookInfo[4]" @click="borrowBookBut">可以借阅</el-button>
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

//格式化时间
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
    '当前时间：'+formatDate(nowDay)+'，最迟还书时间：'+formatDate(backDay)+'。确认借阅？',
    '借阅书籍：'+bookInfo[1],
    {
      confirmButtonText: '确认',
      cancelButtonText: '取消',
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
                    message: '借阅成功，已发送邮件确认',
                    type: 'success',
                })
                bookInfo[6]=bookInfo[6]-1
            }
            else if (res.code==4000) {
                ElMessage({
                    message: '此书没有库存',
                    type: 'warning',
                })
            }
            else if (res.code==4002) {
                ElMessage({
                    message: '此书已借阅且未归还',
                    type: 'warning',
                })
            }
            else {
                ElMessage({
                    message: '错误',
                    type: 'warning',
                })
            }
        })
    })
}
</script>