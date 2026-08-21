<template>
  <div>
      <el-input v-model="title" style="width: 100%" placeholder="タイトルを入力してください" />
  </div>
  <div class="editor-box">
    <!-- ツールバー -->
    <Toolbar
      :editor="editorRef"
      :defaultConfig="toolbarConfig"
      mode="default"
      class="toolbar"
    />

    <!-- エディタ -->
    <Editor
      v-model="valueHtml"
      :defaultConfig="editorConfig"
      mode="default"
      class="editor"
      @onCreated="handleCreated"
    />
  </div>
  <el-button type="warning" style="height: 5%;margin-top: 2%;width: 48%;" @click="crale">クリア</el-button>
  <el-button type="primary" style="height: 5%;margin-top: 2%;width: 48%;margin-left: 4%;" @click="addNewInfo">追加</el-button>
</template>

<script setup>
import "@wangeditor/editor/dist/css/style.css";
import { shallowRef, ref, onBeforeUnmount, watch } from "vue";
import { Editor, Toolbar } from "@wangeditor/editor-for-vue";
import { addInfo } from "@/api/root";
import { ElMessage, ElMessageBox } from 'element-plus'

const token = ref()
token.value = localStorage.getItem('root_token')
const title = ref('')

// v-modelに対応
const props = defineProps({
  modelValue: {
    type: String,
    default: "",
  },
});

const emit = defineEmits(["update:modelValue", "success"]);

// エディタインスタンス
const editorRef = shallowRef();

// エディタ内容
const valueHtml = ref(props.modelValue);

// 親コンポーネントの変更時に同期
watch(
  () => props.modelValue,
  (val) => {
    valueHtml.value = val;
  }
);

// エディタの変更時に親コンポーネントへ通知
watch(valueHtml, (val) => {
  emit("update:modelValue", val);
});

// ツールバー設定
const toolbarConfig = {}

// エディタ設定
const editorConfig = {
  placeholder: "お知らせ内容を入力してください...",
};

// 作成完了
const handleCreated = (editor) => {
  editorRef.value = editor;
};

// 破棄
onBeforeUnmount(() => {
  editorRef.value?.destroy();
});

const crale = () => {
    valueHtml.value=''
    title.value=''
}

const addNewInfo = () => {
    if(title.value==''||valueHtml.value=='') {
        ElMessage({
            message: 'タイトルまたは内容を入力してください',
            type: 'warning',
        })
    }
    else {
        let data = {
            title: title.value,
            content: valueHtml.value
        }
        addInfo(data,token.value).then(res=>{
            console.log(res)
            if(res.code==200){
                ElMessage({
                    message: '追加しました',
                    type: 'success',
                })
                title.value=''
                valueHtml.value=''
                emit("success") 
            }
            else{
                ElMessage({
                    message: 'エラーが発生しました。もう一度お試しください。',
                    type: 'error',
                })
            }
        })
    }
}
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