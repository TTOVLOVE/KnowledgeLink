<template>
  <div class="api-test">
    <h2>API 测试</h2>
    <button @click="testGet">测试 GET 请求</button>
    <button @click="testPost">测试 POST 请求</button>
    
    <div v-if="response" class="response">
      <h3>响应数据：</h3>
      <pre>{{ JSON.stringify(response, null, 2) }}</pre>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { get, post } from '../services/api'

import type { AxiosResponse } from 'axios';
const response = ref<null | AxiosResponse<any, any> | { error: string }>(null)

const testGet = async () => {
  try {
    response.value = await get('/example')
  } catch (error) {
    console.error('GET 请求失败:', error)
    response.value = { error: '请求失败' }
  }
}

const testPost = async () => {
  try {
    response.value = await post('/example', {
      name: '测试数据',
      value: 123
    })
  } catch (error) {
    console.error('POST 请求失败:', error)
    response.value = { error: '请求失败' }
  }
}
</script>

<style scoped>
.api-test {
  padding: 20px;
}

button {
  margin: 10px;
  padding: 8px 16px;
  background-color: #4CAF50;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

button:hover {
  background-color: #45a049;
}

.response {
  margin-top: 20px;
  padding: 10px;
  background-color: #f5f5f5;
  border-radius: 4px;
}

pre {
  white-space: pre-wrap;
  word-wrap: break-word;
}
</style> 