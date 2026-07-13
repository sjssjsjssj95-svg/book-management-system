<script setup>
import { RouterLink, RouterView } from 'vue-router'
import HelloWorld from './components/HelloWorld.vue'
</script>

<template>
 <div class="mainBody">
  <RouterView />
 </div>
</template>

<style>
.mainBody{
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
}
</style>
