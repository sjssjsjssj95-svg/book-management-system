<template>
  <router style="width: calc(99vw);height: calc(10vh);"/>
  
  <div style="overflow-x: hidden;">
    <div class="img-wrapper">
    <img style="width: 100%;height: calc(65vh);" src="../../assets/img/mainPageImg.png" alt="">
    <div class="mask"></div>
  </div>

  <div style="height: calc(70vh);">
    <div style="position: absolute;top: calc(25vh);left: 15%;" class="imgText">
    <h2>知識は知恵を育む</h2>
    <h2>読書<span style="color: rgb(102, 177, 255);">人生を照らす</span></h2>
    <el-text class="mx-1" type="info">豊富な書籍を、便利に管理して、読書を楽しもう</el-text>
    <br>
    <el-button :icon="User" style="margin-top: 20px;" type="primary" @click="toLogin">ログイン / 新規登録</el-button>
    <el-button :icon="Reading" type="info" style="margin-top: 20px;" @click="toBooks">書籍一覧</el-button>
  </div>

  <div style="position: absolute;box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);left: 10%;right: 10%;height: calc(16vh);top: calc(67vh);background-color: white;">
    <div style="width: 100%;">
       <el-row>
          <el-col :span="8">
            <div style="text-align: center;">
              <el-row>
                <el-col :span="6" style="display: flex;
                  align-items: center;   /* 垂直方向に中央揃え */
                  justify-content: center; /* 水平方向に中央揃え（任意） */
                  height: calc(16vh);">
                  <el-icon style="background-color: rgb(102, 177, 255);border-radius: 50%;width: 60px;height: 60px;" size="40"><Reading /></el-icon>
                </el-col>
                <el-col :span="18" style="display: flex;
                  align-items: center;   /* 垂直方向に中央揃え */
                  justify-content: left; /* 水平方向に中央揃え（任意） */
                  height: calc(16vh);">
                  <div style="text-align: left;">
                    <span style="font-weight: bolder;">豊富なリソース</span>
                    <br>
                    <el-text class="mx-1" type="info">豊富な書籍を、いつでも自由に読めます</el-text>
                  </div>
                </el-col>
              </el-row>
            </div>
          </el-col>

          <el-col :span="8">
            <div style="text-align: center;">
              <el-row>
                <el-col :span="6" style="display: flex;
                  align-items: center;   /* 垂直方向に中央揃え */
                  justify-content: center; /* 水平方向に中央揃え（任意） */
                  height: calc(16vh);">
                  <el-icon style="background-color: rgb(133, 206, 97);border-radius: 50%;width: 60px;height: 60px;" size="40"><Menu /></el-icon>
                </el-col>
                <el-col :span="18" style="display: flex;
                  align-items: center;   /* 垂直方向に中央揃え */
                  justify-content: left; /* 水平方向に中央揃え（任意） */
                  height: calc(16vh);">
                  <div style="text-align: left;">
                    <span style="font-weight: bolder;">豊富なカテゴリ</span>
                    <br>
                    <el-text class="mx-1" type="info">多彩なカテゴリから素早く検索</el-text>
                  </div>
                </el-col>
              </el-row>
            </div>
          </el-col>

          <el-col :span="8">
            <div style="text-align: center;">
              <el-row>
                <el-col :span="6" style="display: flex;
                  align-items: center;   /* 垂直方向に中央揃え */
                  justify-content: center; /* 水平方向に中央揃え（任意） */
                  height: calc(16vh);">
                  <el-icon style="background-color: rgb(235, 181, 99);border-radius: 50%;width: 60px;height: 60px;" size="40"><Document /></el-icon>
                  <el-icon><Operation /></el-icon>
                </el-col>
                <el-col :span="18" style="display: flex;
                  align-items: center;   /* 垂直方向に中央揃え */
                  justify-content: left; /* 水平方向に中央揃え（任意） */
                  height: calc(16vh);">
                  <div style="text-align: left;">
                    <span style="font-weight: bolder;">貸出履歴</span>
                    <br>
                    <el-text class="mx-1" type="info">読書履歴を確認して、前回の続きから</el-text>
                  </div>
                </el-col>
              </el-row>
            </div>
          </el-col>
       </el-row>
    </div>
  </div>

  <div style="margin-top: calc(10vh);margin-right: 10%;margin-left: 10%;">
    <h3><span style="color: #0064FF;margin-right: 10px;">I</span>おすすめ書籍</h3>
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
                    <el-tag type="success" style="margin-left: 5px;" v-if="bookAvailable[value]">貸出可能</el-tag>
                    <el-tag type="danger" style="margin-left: 5px;" v-if="!bookAvailable[value]">在庫なし</el-tag>
                </template>
                <img
                    :src="bookImg[value]"
                    style="width: calc(12vw);height: calc(30vh);"
                />
                <el-button style="width: 100%;" :id="bookId[value]" @click="getBookInfo">
                    <span :id="bookId[value]">詳細を見る</span>
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
                  align-items: center;     /* 垂直方向に中央揃え */
                  justify-content: center; /* 水平方向に中央揃え（必要に応じて） */
                  height: 100%; /* 水平方向に中央揃え */">
      <el-text>図書館管理システム</el-text>
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
    path: '/book',
  })
}

const getBookInfo = (a) => {
    routerJump.push({
        path: '/book/info',
        query: { id: a.target.id }
    })
}

const toLogin = () => {
  routerJump.push({
    path: '/login',
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