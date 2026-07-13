<template>
  <div>
    <el-row :gutter="24">
      <el-col :span="18">
        <el-input
          v-model="bookInfo.title"
          style="max-width: 100%"
          placeholder="请输入书名"
        >
          <template #prepend>书名</template>
        </el-input>

        <el-input
          v-model="bookInfo.author"
          style="max-width: 100%; margin-top: 5px"
          placeholder="请输入作者"
        >
          <template #prepend>作者</template>
        </el-input>

        <el-input
          v-model="bookInfo.stock"
          style="max-width: 100%; margin-top: 5px"
          placeholder="请输入总数"
          type="number"
        >
          <template #prepend>总数</template>
        </el-input>

        <el-select
          v-model="bookInfo.category_id"
          placeholder="请选择分类"
          style="width: 100%; margin-top: 5px"
        >
          <el-option
            v-for="item in options"
            :key="item.value"
            :label="item.label"
            :value="item.value"
          />
        </el-select>
      </el-col>

      <el-col :span="6">
        <bookReWritePhoto
          :key="props.bookId"
          :book-img="bookImg"
          @change="handleCoverChange"
        />
      </el-col>
    </el-row>
  </div>

  <div class="editor-box">
    <Toolbar
      :editor="editorRef"
      :default-config="toolbarConfig"
      mode="default"
      class="toolbar"
    />

    <Editor
      v-model="valueHtml"
      :default-config="editorConfig"
      mode="default"
      class="editor"
      @on-created="handleCreated"
    />
  </div>

  <el-button
    type="warning"
    style="height: 5%; margin-top: 2%; width: 48%"
    @click="clear"
  >
    清空
  </el-button>

  <el-button
    type="primary"
    style="height: 5%; margin-top: 2%; width: 48%; margin-left: 4%"
    @click="updateBookInfo"
  >
    更新
  </el-button>
</template>

<script setup>
import "@wangeditor/editor/dist/css/style.css"

import {
  shallowRef,
  ref,
  reactive,
  onBeforeUnmount,
  watch
} from "vue"

import { Editor, Toolbar } from "@wangeditor/editor-for-vue"
import { ElMessage } from "element-plus"

import { updataBook } from "@/api/root"
import { getBookInfo } from "@/api/book"
import { getAllCategoty } from "@/api/categoty.js"

import bookReWritePhoto from "./bookReWritePhoto.vue"


// ==================== Props ====================

const props = defineProps({
  modelValue: {
    type: String,
    default: ""
  },

  bookId: {
    type: [String, Number],
    required: true
  }
})


// ==================== Emits ====================

const emit = defineEmits([
  "update:modelValue",
  "success"
])


// ==================== 基础数据 ====================

const token = localStorage.getItem("root_token")

const bookImg = ref("")

// 用户新选择的图片文件
const coverFile = ref(null)

const options = ref([])

const valueHtml = ref("")

const bookInfo = reactive({
  title: "",
  author: "",
  stock: "",
  category_id: ""
})


// ==================== 查询书籍 ====================

const loadBookInfo = async () => {
  try {
    const res = await getBookInfo(props.bookId)


    bookInfo.title = res.title
    bookInfo.author = res.author
    bookInfo.stock = res.stock
    bookInfo.category_id = res.category_id

    valueHtml.value = res.description

    bookImg.value =
      "http://127.0.0.1:8000/storage/Book/" +
      res.cover +
      ".jpg"

    // 换书时清空之前选择的新图片
    coverFile.value = null

  } catch (error) {

    ElMessage.error("获取书籍信息失败")
  }
}


// ==================== 查询分类 ====================

const loadCategories = async () => {
  try {
    const res = await getAllCategoty()
    const data = res.slice(1)
    options.value = data

  } catch (error) {

    ElMessage.error("获取分类失败")
  }
}


// ==================== 监听 bookId ====================

// bookId 改变时重新查询
watch(
  () => props.bookId,

  (newId) => {
    if (newId) {
      loadBookInfo()
    }
  },

  {
    immediate: true
  }
)


// ==================== 初始化分类 ====================

loadCategories()


// ==================== 接收子组件图片 ====================

const handleCoverChange = (file) => {
  coverFile.value = file
}


// ==================== 富文本编辑器 ====================

const editorRef = shallowRef()

const toolbarConfig = {}

const editorConfig = {
  placeholder: "请输入书籍介绍..."
}

const handleCreated = (editor) => {
  editorRef.value = editor
}


// ==================== v-model ====================

watch(
  () => props.modelValue,

  (val) => {
    valueHtml.value = val
  }
)

watch(valueHtml, (val) => {
  emit("update:modelValue", val)
})


// ==================== 清空 ====================

const clear = () => {
  bookInfo.title = ""
  bookInfo.author = ""
  bookInfo.stock = ""
  bookInfo.category_id = ""

  valueHtml.value = ""

  bookImg.value = ""

  coverFile.value = null
}


// ==================== 更新书籍 ====================

const updateBookInfo = async () => {

  // 检查空值
  if (
    !bookInfo.title ||
    !bookInfo.author ||
    bookInfo.stock === "" ||
    !bookInfo.category_id ||
    !valueHtml.value ||
    !props.bookId
  ) {
    ElMessage.warning("所有值都不能为空！")
    return
  }


  // 创建 FormData
  const data = new FormData()

  data.append("title", bookInfo.title)

  data.append("author", bookInfo.author)

  data.append("stock", bookInfo.stock)

  data.append(
    "category_id",
    bookInfo.category_id
  )

  data.append(
    "description",
    valueHtml.value
  )

  data.append(
    "book_id",
    props.bookId
  )


  // 用户选择新图片才上传
  if (coverFile.value) {
    data.append(
      "cover",
      coverFile.value
    )
  }


  try {

    const res = await updataBook(
      data,
      token
    )

    ElMessage.success("更新成功")

    emit("success")

  } catch (error) {


    ElMessage.error("更新失败")
  }
}


// ==================== 销毁编辑器 ====================

onBeforeUnmount(() => {
  editorRef.value?.destroy()
})
</script>

<style scoped>
.editor-box {
  border: 1px solid #dcdfe6;
  border-radius: 6px;
  overflow: hidden;
  height: 85%;
}

.toolbar {
  border-bottom: 1px solid #ebeef5;
}

.editor {
  height: 450px;
  overflow-y: auto;
}
</style>