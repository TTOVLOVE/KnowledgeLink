<template>
  <div class="app">
    <router-view v-slot="{ Component }">
      <transition name="page" mode="out-in">
        <component :is="Component" />
      </transition>
    </router-view>
  </div>
</template>

<script setup lang="ts">
// Main app component serves as the layout wrapper
</script>

<style>
.app {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}
</style>