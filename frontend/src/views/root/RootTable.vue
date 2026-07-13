<template>
    <div class="common-layout" style="width: 100%;height: calc(100vh);">
        <el-container style="height: 100%;">
            <el-aside width="calc(15vw)" style="background-color: #2f4050;height: 100%;text-align: center;">
                
                <Router/>
                
            </el-aside>
            <el-container>
                <el-header style="display: flex; justify-content: center;align-items: center;">
                    <h3>数据查询</h3>
                </el-header>

                <el-main style="background-color: #E0E0E0;" v-loading="mainLoading" >

                    <h3>用户总数：{{ userNumber }}个</h3>
                    <el-divider style="border-color: #2f4050;"/>
                    <h3>图书总数：{{ bookAllNumber }}本</h3>

                    <div ref="pieRef" style="width:100%;height:100%;text-align: center;"></div>
                    
                    <el-divider style="border-color: #2f4050;"/>
                    <h3>借阅总数:{{ borrowAllNumber }}次</h3>
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


// 加载图表数据
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

    
    // 获取分类统计数据
    const res = await tableBooks(token.value)

    // 转换成 ECharts 需要的数据格式
    const chartData = res.map(item => ({
        name: item.type_name,
        value: item.number
    }))

    res.map(item=>{
        bookAllNumber.value = bookAllNumber.value+item.number
    })
    // 更新图表
    chart.setOption({
        title: {
            text: '图书分类统计（共 ' + bookAllNumber.value + ' 本）'
        },
        series: [
            {
                type: 'pie',
                radius: '60%',
                data: chartData,
                label: {
                    show: true,
                    formatter: '{b}\n{c}本'
                }
            }
        ]
    })
    mainLoading.value=false
}

onMounted(() => {
    // 初始化图表
    chart = echarts.init(pieRef.value)

    chart.setOption({
        title: {
            text: '图书分类统计'+bookAllNumber.value,
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
                name: '图书数量',
                type: 'pie',
                radius: '60%',
                data: []   // 先空着
            }
        ]
    })

    // 再去加载数据
    loadChartData()
})

const initChart = () => {
    const chart = echarts.init(document.getElementById("bar"))

    const option = {
        title: {
            text: "借阅统计",
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
                name: "借阅数量",
                type: "bar",
                data: number,
                barWidth: "40%"
            }
        ]
    }

    chart.setOption(option)
}
</script>