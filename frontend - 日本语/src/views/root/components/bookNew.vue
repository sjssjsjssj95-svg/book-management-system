<template>
  <div>
    <p style="text-align: center;font-size: larger;font-weight: bolder;">新しい書籍</p>
    <el-row :gutter="24">
      <el-col :span="18">
        <el-input
          v-model="bookInfo.title"
          style="max-width: 100%"
          placeholder="書籍名を入力してください"
        >
          <template #prepend>書籍名</template>
        </el-input>

        <el-input
          v-model="bookInfo.author"
          style="max-width: 100%; margin-top: 5px"
          placeholder="著者を入力してください"
        >
          <template #prepend>著者</template>
        </el-input>

        <el-input
          v-model="bookInfo.stock"
          style="max-width: 100%; margin-top: 5px"
          placeholder="総冊数を入力してください"
          type="number"
        >
          <template #prepend>総冊数</template>
        </el-input>

        <el-select
          v-model="bookInfo.category_id"
          placeholder="カテゴリを選択してください"
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
    クリア
  </el-button>

  <el-button
    type="primary"
    style="height: 5%; margin-top: 2%; width: 48%; margin-left: 4%"
    @click="addBookInfo"
  >
    追加
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

import { getAllCategoty } from "@/api/categoty.js"

import bookReWritePhoto from "./bookReWritePhoto.vue"
import { addaBook } from "@/api/root.js"


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


// ==================== 基冊データ ====================

const token = localStorage.getItem("root_token")

const bookImg = ref("")

// ユーザーが新しく選択した画像ファイル
const coverFile = ref(null)

const options = ref([])

const valueHtml = ref("")

const bookInfo = reactive({
  title: "",
  author: "",
  stock: "",
  category_id: ""
})


// ==================== カテゴリを検索 ====================

const loadCategories = async () => {
  try {
    const res = await getAllCategoty()
    const data = res.slice(1)
    options.value = data

  } catch (error) {

    ElMessage.error("カテゴリの取得に失敗しました")
  }
}


// ==================== bookIdを監視 ====================

// bookId 変更時に再検索
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


// ==================== カテゴリを初期化 ====================

loadCategories()


// ==================== 子コンポーネントから画像を受信 ====================

const handleCoverChange = (file) => {
  coverFile.value = file
}


// ==================== リッチテキストエディタ ====================

const editorRef = shallowRef()

const toolbarConfig = {}

const editorConfig = {
  placeholder: "書籍紹介を入力してください..."
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


// ==================== クリア ====================

const clear = () => {
  bookInfo.title = ""
  bookInfo.author = ""
  bookInfo.stock = ""
  bookInfo.category_id = ""

  valueHtml.value = ""

  bookImg.value = ""

  coverFile.value = null
}


// ==================== 書籍を更新 ====================

const addBookInfo = async () => {

  // 未入力項目をチェック
  if (
    !bookInfo.title ||
    !bookInfo.author ||
    bookInfo.stock === "" ||
    !bookInfo.category_id ||
    !valueHtml.value
  ) {
    ElMessage.warning("すべての項目を入力してください。")
    return
  }


  // FormDataを作成
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


  // 新しい画像が選択された場合のみアップロード
  if (coverFile.value) {
    data.append(
      "cover",
      coverFile.value
    )
  }


  try {

    
    const res = await addaBook(data,token)
    if (res.msg=="追加しました"){
      ElMessage.success("更新しました")
    }
    else {
      ElMessage.warning("エラーが発生しました。もう一度お試しください。")
    }
    emit("success")

  } catch (error) {

    console.log(error)
    ElMessae.error("更新に失敗しました")
  }
}


// ==================== エディタを破棄 ====================

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