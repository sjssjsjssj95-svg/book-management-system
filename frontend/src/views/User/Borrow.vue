<template>
    <router style="width: calc(100vw);height: calc(10vh);"/>

    <div style="margin-left: 15%;margin-right: 15%;height: calc(80vh)" v-loading="loading">
        <el-radio-group v-model="radioGourp" style="width: 100%;height: calc(10vh);">
            <el-row>
                 <el-col :span="6">
                    <el-radio style="width: calc(15vw);" value="1" size="large" border @click="Normol">借阅中</el-radio>
                 </el-col>
                 <el-col :span="6">
                    <el-radio style="width: calc(15vw);margin-left: calc(2vw);" value="2" size="large" border @click="outTime">已逾期 <el-tag v-if="tagShow[0]" effect="dark" type="danger">请注意！</el-tag></el-radio>
                 </el-col>
                 <el-col :span="6">
                    <el-radio style="width: calc(15vw);margin-left: calc(2vw);" value="3" size="large" border @click="lost">书本丢失 <el-tag v-if="tagShow[1]" effect="dark" type="danger">请注意！</el-tag></el-radio>
                 </el-col>
                 <el-col :span="6">
                    <el-radio style="width: calc(15vw);margin-left: calc(2vw);" value="4" size="large" border @click="getBackLogBut">已归还 <el-tag v-if="tagShow[1]" effect="dark" type="danger">请注意！</el-tag></el-radio>
                 </el-col>
            </el-row>
        </el-radio-group>

        <el-table :data="tableData" style="width: 100%" height="calc(70vh)" empty-text="暂无数据" v-if="trueTable">
            <el-table-column fixed prop="name" label="书名"/>
            <el-table-column label="封面">
                <template #default="scope">
                    <img style="width: 50%;" :src="scope.row.img" alt="">
                </template>
            </el-table-column>
            <el-table-column prop="borrow_time" label="借阅时间"/>
            <el-table-column prop="last_time" label="最迟归还时间"/>
            <el-table-column label="操作">
                <template #default="scope">
                    <el-button type="primary" @click="handleEdit(scope.$index)">
                    归还
                    </el-button>
                </template>
            </el-table-column>
        </el-table>


        <el-table :data="tableData" style="width: 100%" height="calc(70vh)" empty-text="暂无数据" v-if="!trueTable">
            <el-table-column fixed prop="name" label="书名"/>
            <el-table-column label="封面">
                <template #default="scope">
                    <img style="width: 50%;" :src="scope.row.img" alt="">
                </template>
            </el-table-column>
            <el-table-column prop="borrow_time" label="借阅时间"/>
            <el-table-column prop="last_time" label="归还时间"/>
            <el-table-column label="操作">
                <template #default="scope">
                    <el-button type="primary" @click="handleEdit(scope.$index)" disabled>
                    归还
                    </el-button>
                </template>
            </el-table-column>
        </el-table>
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
import { ElMessage, ElMessageBox } from 'element-plus'
import { getBorrowNorLog , getBorrowOutLog , getBorrowLostLog , backBook , getBackLog } from '@/api/borrow';

const bookId = ref(0)
const route = useRoute()

const token = ref('')
token.value = localStorage.getItem('token')

bookId.value = route.query.id

const tableData = ref([])
const trueTable = ref(true)
const radioGourp = ref("1")
const loading = ref(true)
const tagShow = reactive([])
tagShow[0] = false
tagShow[1] = false

const choose = ref(0)
const chooseForBack = ref(0)

const Normol = () => {
     if (trueTable.value==false){
        trueTable.value = !trueTable.value
    }
    if (choose.value!=1||choose.value==0) {
        loading.value = true
        getBorrowNorLog().then(res=>{
            let data = []
            if (res[0].user_id==0){
                tagShow[0] = true
            }
            if (res[0].book_id==0) {
                tagShow[1] = true
            }
            for (let i=1;i<res.length;i++){
                data.push({
                    name : res[i].book.title,
                    borrow_time : res[i].borrow_time,
                    last_time : res[i].due_time,
                    img : 'http://127.0.0.1:8000/storage/Book/'+res[i].book.cover+'.jpg',
                    id : res[i].id
                })
            }
            tableData.value = data
            loading.value = false
            choose.value=1
            chooseForBack.value=1
        })
    }
}

Normol()

const outTime = () =>{
    if (trueTable.value==false){
        trueTable.value = !trueTable.value
    }
    if (choose.value!=2) {
        loading.value = true
        getBorrowOutLog().then(res=>{
            if (res.length==0) {
                tagShow[0]=false
                loading.value = false
                choose.value=2
                chooseForBack.value=2
                tableData.value = []
            }
            else {
                let data = []
                for (let i=0;i<res.length;i++){
                    data.push({
                        name : res[i].book.title,
                        borrow_time : res[i].borrow_time,
                        last_time : res[i].due_time,
                        img : '/Img/Book/'+res[i].book.cover+'.jpg',
                        id : res[i].id
                    })
                }
                tableData.value = data
                loading.value = false
                choose.value=2
                chooseForBack.value=2
            }
        })
    }
}

const lost = () => {
    if (trueTable.value==false){
        trueTable.value = !trueTable.value
    }
   if(choose.value!=3){
    loading.value = true
    getBorrowLostLog().then(res=>{
    if (res.length==0) {
        tagShow[1]=false
        tableData.value = []
        loading.value = false
        choose.value=3
        chooseForBack.value=3
    }
        else {
            let data = []
            for (let i=0;i<res.length;i++){
                data.push({
                    name : res[i].book.title,
                    borrow_time : res[i].borrow_time,
                    last_time : res[i].due_time,
                    img : 'http://127.0.0.1:8000/storage/Book/'+res[i].book.cover+'.jpg',
                    id : res[i].id
                })
            }
            tableData.value = data
            loading.value = false
            choose.value=3
            chooseForBack.value=3
            loading.value = true
        }
     })
   }
}

const getBackLogBut = () => {
    if (choose.value!=4) {
        trueTable.value = !trueTable.value
        loading.value = true
        getBackLog().then(res=>{
            if (res.length==0) {
                tagShow[2]=false
                tableData.value = []
                loading.value = false
                choose.value=4
                chooseForBack.value=4
            }
            else {
                let data = []
                for (let i=0;i<res.length;i++){
                    data.push({
                        name : res[i].book.title,
                        borrow_time : res[i].borrow_time,
                        last_time : res[i].return_time,
                        img : 'http://127.0.0.1:8000/storage/Book/'+res[i].book.cover+'.jpg',
                        id : res[i].id
                    })
                }
                tableData.value = data
                loading.value = false
                choose.value=4
                chooseForBack.value=4
            }
        })
    }
}

const handleEdit = (index) => {
  const data = {
        id: tableData.value[index].id,
    }
  backBook(data,token.value).then(res=>{
    if(res==1){
        ElMessage({
            message: '归还成功',
            type: 'success',
        })
        if (chooseForBack.value==1||chooseForBack.value==0) {
            choose.value=5
            Normol()
        }
        if (chooseForBack.value==2){
            choose.value=5
            outTime()
        }
    }
    else {
        ElMessage({
            message: '错误',
            type: 'error',
        })
    }
  })
}
</script>