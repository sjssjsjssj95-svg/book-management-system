<template>
    <div class="common-layout" style="width: 100%;height: calc(100vh);">
        <el-container style="height: 100%;">
            <el-aside width="calc(15vw)" style="background-color: #2f4050;height: 100%;text-align: center;">
                
                <Router/>
                
            </el-aside>
            <el-container>
                <el-header style="display: flex; justify-content: center;align-items: center;">
                    <h3>データ照会</h3>
                </el-header>

                <el-main style="background-color: #E0E0E0;" v-loading="mainLoading" >

                    <h3>ユーザー総数：{{ userNumber }}人</h3>
                    <el-divider style="border-color: #2f4050;"/>
                    <h3>書籍総数：{{ bookAllNumber }}冊</h3>

                    <div ref="pieRef" style="width:100%;height:100%;text-align: center;"></div>
                    
                    <el-divider style="border-color: #2f4050;"/>
                    <h3>貸出総数：{{ borrowAllNumber }}回</h3>
                    <div id="bar" style="width:100%;height:100%;text-align: center;"></div>

                </el-main>
            </el-container>
        </el-container>
    </div>
</template>

<script setup>
import Router from './components/Router.vue';
import { ref , onMounted  } from 'vue';
import { login } from '@/api/root'
import { ElMessage } from 'element-plus'
import { useRoute , useRouter } from 'vue-router'
import { loginIO , tableBooks , getUserNumber , getBorrowNumber } from '@/api/root'

import * as echarts from 'echarts';


const route = useRoute()
const router = useRouter()

const token = ref('')

token.value = localStorage.getItem('root_token')

const pieRef = ref()
let chart = null

let type = []
let number = []
let borrowData = []

const bookAllNumber = ref(0)
const borrowAllNumber = ref(0)
const mainLoading = ref(true)

const userNumber = ref('')


// グラフデータを読み込む
const loadChartData = async () => {
    getUserNumber(token.value).then(Res=>{
        userNumber.value=Res
    })

    getBorrowNumber(token.value).then(res => {
        res.forEach(item => {
            type.push(item.type_name)
            number.push(item.number)
            borrowAllNumber.value = borrowAllNumber.value + item.number
        })

        
        initChart()
    })

    
    // カテゴリ統計データを取得
    const res = await tableBooks(token.value)

    // ECharts用のデータ形式に変換
    const chartData = res.map(item => ({
        name: item.type_name,
        value: item.number
    }))

    res.map(item=>{
        bookAllNumber.value = bookAllNumber.value+item.number
    })
    // グラフを更新
    chart.setOption({
        title: {
            text: '書籍カテゴリ統計（合計 ' + bookAllNumber.value + ' 冊）'
        },
        series: [
            {
                type: 'pie',
                radius: '60%',
                data: chartData,
                label: {
                    show: true,
                    formatter: '{b}\n{c}冊'
                }
            }
        ]
    })
    mainLoading.value=false
}

onMounted(() => {
    // グラフを初期化
    chart = echarts.init(pieRef.value)

    chart.setOption({
        title: {
            text: '書籍カテゴリ統計'+bookAllNumber.value,
            left: 'center'
        },
        tooltip: {
            trigger: 'item',
        },
        legend: {
            bottom: 0
        },
        series: [
            {
                name: '書籍数',
                type: 'pie',
                radius: '60%',
                data: []   // 空欄のままにする
            }
        ]
    })

    // その後データを読み込む
    loadChartData()
})

const initChart = () => {
    const chart = echarts.init(document.getElementById("bar"))

    const option = {
        title: {
            text: "貸出統計",
            left: "center"
        },
        tooltip: {},
        xAxis: {
            type: "category",
            data: type
        },
        yAxis: {
            type: "value"
        },
        series: [
            {
                name: "貸出数",
                type: "bar",
                data: number,
                barWidth: "40%"
            }
        ]
    }

    chart.setOption(option)
}
</script>