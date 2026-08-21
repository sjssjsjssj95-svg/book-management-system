<template>
  <el-upload
    class="image-uploader"
    :show-file-list="false"
    :auto-upload="false"
    accept="image/*"
    :on-change="handleChange"
  >
    <div class="image-box">

      <img
        v-if="imageUrl"
        :src="imageUrl"
        class="image"
      />

      <el-icon
        v-else
        class="empty-icon"
      >
        <Plus />
      </el-icon>


      <!-- マウスをオーバーしたときのオーバーレイ -->

      <div
        v-if="imageUrl"
        class="upload-mask"
      >

        <el-icon>
          <Upload />
        </el-icon>

      </div>

    </div>
  </el-upload>
</template>


<script setup>

import {
  ref,
  computed,
  onBeforeUnmount
} from "vue"

import {
  Plus,
  Upload
} from "@element-plus/icons-vue"


// ==================== Props ====================

const props = defineProps({

  bookImg: {
    type: String,
    default: ""
  }

})


// ==================== Emits ====================

const emit = defineEmits([
  "change"
])


// ==================== 画像プレビュー ====================

const previewUrl = ref("")


const imageUrl = computed(() => {

  // 新しい画像を優先
  // 新しい画像がなければ古い画像を表示

  return (
    previewUrl.value ||
    props.bookImg
  )

})


// ==================== 画像を選択 ====================

const handleChange = (file) => {

  if (!file.raw) {
    return
  }


  // 以前のプレビューURLを解放

  if (previewUrl.value) {

    URL.revokeObjectURL(
      previewUrl.value
    )

  }


  // ローカルプレビューを作成

  previewUrl.value =
    URL.createObjectURL(
      file.raw
    )


  // 実際のFileを親コンポーネントへ渡す

  emit(
    "change",
    file.raw
  )

}


// ==================== 破棄 ====================

onBeforeUnmount(() => {

  if (previewUrl.value) {

    URL.revokeObjectURL(
      previewUrl.value
    )

  }

})

</script>


<style scoped>

.image-box {

  position: relative;

  width: 150px;

  height: 200px;

  cursor: pointer;

  overflow: hidden;

  border-radius: 6px;

}


.image {

  width: 100%;

  height: 100%;

  object-fit: cover;

  display: block;

}


.upload-mask {

  position: absolute;

  top: 0;

  left: 0;

  width: 100%;

  height: 100%;

  display: flex;

  justify-content: center;

  align-items: center;

  background: rgba(0, 0, 0, 0.5);

  color: white;

  font-size: 32px;

  opacity: 0;

  transition: opacity 0.3s;

}


.image-box:hover .upload-mask {

  opacity: 1;

}


.empty-icon {

  width: 100%;

  height: 100%;

  font-size: 30px;

  border: 1px dashed #dcdfe6;

}

</style>