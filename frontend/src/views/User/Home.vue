<template>
  <router style="width: calc(99vw);height: calc(10vh);"/>
  
  <div style="overflow-x: hidden;">
    <div class="img-wrapper">
    <img style="width: 100%;height: calc(65vh);" src="../../assets/img/mainPageImg.png" alt="">
    <div class="mask"></div>
  </div>

  <div style="height: calc(70vh);">
    <div style="position: absolute;top: calc(25vh);left: 15%;" class="imgText">
    <h2>知识启迪智慧</h2>
    <h2>阅读<span style="color: rgb(102, 177, 255);">点亮人生</span></h2>
    <el-text class="mx-1" type="info">海量图书资源-便捷管理 -畅享阅读</el-text>
    <br>
    <el-button :icon="User" style="margin-top: 20px;" type="primary" @click="toCenter">个人中心</el-button>
    <el-button :icon="Reading" type="info" style="margin-top: 20px;" @click="toBooks">图书浏览</el-button>
  </div>

  <div style="position: absolute;box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);left: 10%;right: 10%;height: calc(16vh);top: calc(67vh);background-color: white;">
    <div style="width: 100%;">
       <el-row>
          <el-col :span="8">
            <div style="text-align: center;">
              <el-row>
                <el-col :span="6" style="display: flex;
                  align-items: center;   /* 垂直居中 */
                  justify-content: center; /* 水平居中（可选） */
                  height: calc(16vh);">
                  <el-icon style="background-color: rgb(102, 177, 255);border-radius: 50%;width: 60px;height: 60px;" size="40"><Reading /></el-icon>
                </el-col>
                <el-col :span="18" style="display: flex;
                  align-items: center;   /* 垂直居中 */
                  justify-content: left; /* 水平居中（可选） */
                  height: calc(16vh);">
                  <div style="text-align: left;">
                    <span style="font-weight: bolder;">海量资源</span>
                    <br>
                    <el-text class="mx-1" type="info">百万图书资源，随心阅读</el-text>
                  </div>
                </el-col>
              </el-row>
            </div>
          </el-col>

          <el-col :span="8">
            <div style="text-align: center;">
              <el-row>
                <el-col :span="6" style="display: flex;
                  align-items: center;   /* 垂直居中 */
                  justify-content: center; /* 水平居中（可选） */
                  height: calc(16vh);">
                  <el-icon style="background-color: rgb(133, 206, 97);border-radius: 50%;width: 60px;height: 60px;" size="40"><Menu /></el-icon>
                </el-col>
                <el-col :span="18" style="display: flex;
                  align-items: center;   /* 垂直居中 */
                  justify-content: left; /* 水平居中（可选） */
                  height: calc(16vh);">
                  <div style="text-align: left;">
                    <span style="font-weight: bolder;">分类齐全</span>
                    <br>
                    <el-text class="mx-1" type="info">多种分类，快速查找</el-text>
                  </div>
                </el-col>
              </el-row>
            </div>
          </el-col>

          <el-col :span="8">
            <div style="text-align: center;">
              <el-row>
                <el-col :span="6" style="display: flex;
                  align-items: center;   /* 垂直居中 */
                  justify-content: center; /* 水平居中（可选） */
                  height: calc(16vh);">
                  <el-icon style="background-color: rgb(235, 181, 99);border-radius: 50%;width: 60px;height: 60px;" size="40"><Document /></el-icon>
                  <el-icon><Operation /></el-icon>
                </el-col>
                <el-col :span="18" style="display: flex;
                  align-items: center;   /* 垂直居中 */
                  justify-content: left; /* 水平居中（可选） */
                  height: calc(16vh);">
                  <div style="text-align: left;">
                    <span style="font-weight: bolder;">借阅记录</span>
                    <br>
                    <el-text class="mx-1" type="info">记录阅读记录，继续上次</el-text>
                  </div>
                </el-col>
              </el-row>
            </div>
          </el-col>
       </el-row>
    </div>
  </div>

  <div style="margin-top: calc(10vh);margin-right: 10%;margin-left: 10%;">
    <h3><span style="color: #0064FF;margin-right: 10px;">I</span>热门推荐</h3>
    <div style="margin-top: 10px;text-align: center;" v-loading="loading">
    <el-row>
        <el-col :span="6" v-for="value in booksLength" style="margin-top: 10px;">
            <el-card style="max-width: calc(15vw)">
                <template #header>
                    <span style="font-weight: bolder;font-size: larger;">{{ bookName[value] }}</span>
                    <br>
                    <span>{{ bookAu[value] }}</span>
                    <br>
                    <el-tag type="primary">{{ bookType[value] }}</el-tag>
                    <el-tag type="success" style="margin-left: 5px;" v-if="bookAvailable[value]">可以借阅</el-tag>
                    <el-tag type="danger" style="margin-left: 5px;" v-if="!bookAvailable[value]">暂无库存</el-tag>
                </template>
                <img
                    :src="bookImg[value]"
                    style="width: calc(12vw);height: calc(30vh);"
                />
                <el-button style="width: 100%;" :id="bookId[value]" @click="getBookInfo">
                    <span :id="bookId[value]">查看详情</span>
                </el-button>
            </el-card>
        </el-col>
    </el-row>
   </div>
  </div>
  </div>
  </div>

  <el-footer style="width: 100%;background-color: #DEDEDE;">
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
import { User , Reading , Document , Menu } from '@element-plus/icons-vue'
import { getFiveBooks } from '@/api/book';
import { ref , reactive } from 'vue';

import { useRoute , useRouter } from 'vue-router'
const routerJump = useRouter()

const loading = ref(true)
const booksLength = ref(0)
const bookName = reactive({})
const bookImg = reactive({})
const bookType = reactive({})
const bookAu = reactive({})
const bookAvailable = reactive({})
const bookId = reactive({})


getFiveBooks().then(res=>{
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

const toBooks = () => {
  routerJump.push({
    path: '/user/books',
  })
}

const getBookInfo = (a) => {
    routerJump.push({
        path: '/user/book/info',
        query: { id: a.target.id }
    })
}

const toCenter = () => {
  routerJump.push({
    path: '/user/center',
  })
}
</script>

<style>
.img-wrapper {
  position: relative;
}

.img-wrapper img {
  width: 100%;
}

.mask {
  position: absolute;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  background: linear-gradient(to right, white, rgba(255,255,255,0));
}
</style>