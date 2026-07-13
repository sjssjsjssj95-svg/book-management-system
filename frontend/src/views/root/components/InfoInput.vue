<template>
  <div>
      <el-input v-model="title" style="width: 100%" placeholder="请输入标题" />
  </div>
  <div class="editor-box">
    <!-- 工具栏 -->
    <Toolbar
      :editor="editorRef"
      :defaultConfig="toolbarConfig"
      mode="default"
      class="toolbar"
    />

    <!-- 编辑器 -->
    <Editor
      v-model="valueHtml"
      :defaultConfig="editorConfig"
      mode="default"
      class="editor"
      @onCreated="handleCreated"
    />
  </div>
  <el-button type="warning" style="height: 5%;margin-top: 2%;width: 48%;" @click="crale">清空</el-button>
  <el-button type="primary" style="height: 5%;margin-top: 2%;width: 48%;margin-left: 4%;" @click="addNewInfo">添加</el-button>
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

// 支持 v-model
const props = defineProps({
  modelValue: {
    type: String,
    default: "",
  },
});

const emit = defineEmits(["update:modelValue", "success"]);

// 编辑器实例
const editorRef = shallowRef();

// 编辑器内容
const valueHtml = ref(props.modelValue);

// 父组件修改时同步
watch(
  () => props.modelValue,
  (val) => {
    valueHtml.value = val;
  }
);

// 编辑器修改时通知父组件
watch(valueHtml, (val) => {
  emit("update:modelValue", val);
});

// 工具栏配置
const toolbarConfig = {}

// 编辑器配置
const editorConfig = {
  placeholder: "请输入公告内容...",
};

// 创建完成
const handleCreated = (editor) => {
  editorRef.value = editor;
};

// 销毁
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
            message: '请输入标题或内容',
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
                    message: '添加成功',
                    type: 'success',
                })
                title.value=''
                valueHtml.value=''
                emit("success") 
            }
            else{
                ElMessage({
                    message: '错误，请重试',
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