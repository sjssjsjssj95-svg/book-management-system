<template>
    <div class="common-layout" style="width: 100%;height: calc(100vh);">
        <el-container style="height: 100%;">
            <el-aside width="calc(15vw)" style="background-color: #2f4050;height: 100%;text-align: center;">
                
                <Router/>
                
            </el-aside>
            <el-container>
                <el-header style="display: flex; justify-content: center;justify-content: center;align-items: center;">
                    <h3>书籍管理</h3>
                </el-header>

                <el-main style="background-color: #E0E0E0;" v-loading="mainLoading" >
                    <div style="text-align: right;">
                        <el-select v-model="value" placeholder="Select" style="width: 10%;margin-right: 10px;" @change="selectTypeSelect">
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
                            placeholder="请输入搜索内容"
                            class="input-with-select"
                        >
                            <template #prepend>
                                <el-select v-model="selectType" placeholder="Select" style="width: 115px">
                                <el-option label="书名" value="title" />
                                <el-option label="作者" value="author" />
                                </el-select>
                            </template>
                            <template #append>
                                <el-button>搜索</el-button>
                            </template>
                        </el-input>
                        <el-button type="primary" style="margin-left: 10px;" @click="newBookDrawer=true">添加新图书</el-button>
                        <el-button type="primary" style="margin-left: 10px;" @click="categoryDrawer=true">书本类型管理</el-button>
                    </div>
                    <el-row :gutter="20">
                            <el-col :span="3" style="text-align: center;">
                                <h3>封面</h3>
                            </el-col>
                            <el-col :span="3" style="text-align: center;">
                                <h3>书名</h3>
                            </el-col>
                            <el-col :span="3" style="text-align: center;">
                                <h3>作者</h3>
                            </el-col>
                            <el-col :span="3" style="text-align: center;">
                                <h3>类型</h3>
                            </el-col>
                            <el-col :span="3" style="text-align: center;">
                                <h3>总数</h3>
                            </el-col>
                            <el-col :span="3" style="text-align: center;">
                                <h3>余数</h3>
                            </el-col>
                            <el-col :span="3" style="text-align: center;">
                                <h3>创建时间</h3>
                            </el-col>
                            <el-col :span="3" style="text-align: center;">
                                <h3>操作</h3>
                            </el-col>
                        </el-row>
                    <div style="width: 100%;" v-for="value in booksLength">
                        <el-divider style="border-color: #2f4050;"/>
                         <el-row :gutter="20">
                            <el-col :span="3">
                                <el-image style="width: 100%;" :src="bookCover[value-1]" :fit="fit" />
                            </el-col>
                            <el-col :span="3" style="display: flex; justify-content: center;justify-content: center;align-items: center;">
                                <p>{{ bookName[value-1] }}</p>
                            </el-col>
                            <el-col :span="3" style="display: flex; justify-content: center;align-items: center;">
                                <p>{{ bookAuthor[value-1] }}</p>
                            </el-col>
                            <el-col :span="3" style="display: flex; justify-content: center;align-items: center;">
                                {{bookCategory[value-1]}}
                            </el-col>
                            <el-col :span="3" style="display: flex; justify-content: center;align-items: center;">
                               {{bookStock[value-1]}}
                            </el-col>
                            <el-col :span="3" style="display: flex; justify-content: center;align-items: center;">
                                 {{bookAvailable[value-1]}}
                            </el-col>
                            <el-col :span="3" style="display: flex; justify-content: center;align-items: center;">
                                {{bookTime[value-1]}}
                            </el-col>
                            <el-col :span="3" style="display: flex; justify-content: center;align-items: center;">
                                <el-button type="warning" @click="openChangeDrawer(value)">修改</el-button>
                                <el-popconfirm
                                    class="box-item"
                                    title="确定要删除吗？"
                                    placement="top-end"
                                    confirm-button-text="确定"
                                    cancel-button-text="取消"
                                    @confirm="deleteBookButton(value)"
                                >
                                    <template #reference>
                                        <el-button type="danger">删除</el-button>
                                    </template>
                                </el-popconfirm>
                            </el-col>
                        </el-row>
                    </div>
                </el-main>
            </el-container>
        </el-container>
    </div>


    <el-drawer v-model="drawer" title="I am the title" :with-header="false" size="60%">
        <bookReWrite  :key="reWriteBookId" :bookId="reWriteBookId" @success="updataBookSuccess"/>
    </el-drawer>

    <el-drawer v-model="categoryDrawer" title="I am the title" :with-header="false" size="20%">
        <category/>
    </el-drawer>

    <el-drawer v-model="newBookDrawer" title="I am the title" :with-header="false" size="60%">
        <bookNew @success="addBookSuccess"/>
    </el-drawer>
</template>

<script setup>
import Router from './components/Router.vue';
import "@wangeditor/editor/dist/css/style.css";
import { shallowRef, ref, onBeforeUnmount, watch , reactive } from "vue";
import { getAllBooks , deleteBook } from '@/api/root.js';
import { ElMessage, ElMessageBox } from 'element-plus'
import { getAllCategoty } from '@/api/categoty';
import { getBooksByCategoty } from '@/api/book';
import bookReWrite from './components/bookReWrite.vue';
import category from './components/category.vue';
import bookNew from './components/bookNew.vue';


const mainLoading = ref(true)

const token = ref('')
const booksLength = ref(0)
const bookName = reactive({})
const bookAuthor = reactive({})
const bookAvailable = reactive({})
const bookCategory = reactive({})
const bookCover = reactive({})
const bookId = reactive({})
const bookStock = reactive({})
const bookTime = reactive({})

const reWriteBookId = ref(0)

const selectInput = ref('')
const selectType = ref('title')

const drawer = ref(false)
const categoryDrawer = ref(false)
const newBookDrawer = ref(false)


const value = ref(0)

const options = ref({})

getAllCategoty().then(res=>{
    for (let i=0;i<res.length;i++){
        options.value = res
    }
})

token.value = localStorage.getItem('root_token')

const getAllBooksAction = () => {
    getAllBooks(token.value).then(res=>{
        console.log(res)
        mainLoading.value = true
        booksLength.value = res.length
        for (let i=0;i<res.length;i++) {
            bookName[i]        = res[i].title
            bookAuthor[i]      = res[i].author
            bookAvailable[i]   = res[i].available
            bookCategory[i]    = res[i].category
            bookCover[i]       = 'http://127.0.0.1:8000/storage/Book/'+res[i].cover+'.jpg'
            bookId[i]          = res[i].id
            bookStock[i]       = res[i].stock
            bookTime[i]        = res[i].created_at
        }
        window.setTimeout(()=>{
            mainLoading.value = false
        },500)
    })
}

getAllBooksAction()

const selectTypeSelect = (value) => {
    mainLoading.value = true
    getBooksByCategoty(value).then(res=>{
        booksLength.value = res.length
        for (let i=0;i<res.length;i++) {
            bookName[i]        = res[i].title
            bookAuthor[i]      = res[i].author
            bookAvailable[i]   = res[i].available
            bookCategory[i]    = res[i].category
            bookCover[i]       = 'http://127.0.0.1:8000/storage/Book/'+res[i].cover+'.jpg'
            bookId[i]          = res[i].id
            bookStock[i]       = res[i].stock
            bookTime[i]        = res[i].created_at
        }
    })
    window.setTimeout(()=>{
            mainLoading.value = false
        },500)
}

const deleteBookButton = (a) => {
    const data = {
        book_id: bookId[a-1],
    }
    deleteBook(data,token.value).then(res=>{
        if (res.code==200) {
            mainLoading.value=true
            ElMessage({
                message: '删除成功',
                type: 'success',
            })
            getAllBooksAction()
        }
        else {
            ElMessage({
                message: '错误，请重试',
                type: 'error',
            })
        }
    })
}

const openChangeDrawer = (a) => {
    reWriteBookId.value = bookId[a-1]
    drawer.value=true
}

const updataBookSuccess = () => {
    drawer.value = false
    getAllBooksAction()
}

const addBookSuccess = () => {
    newBookDrawer.value = false 
    getAllBooksAction()
}
</script>