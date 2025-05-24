<template>
  <div class="collections-page">
    <TheHeader 
      title="收藏夹" 
      :showBackButton="true"
    />
    
    <main class="main-content">
      <div class="container">
        <div class="collection-count">总共{{ collections.length }}个内容</div>
        
        <div class="collections-list">
          <router-link 
            v-for="item in collections" 
            :key="item.id"
            :to="item.link"
            class="collection-item"
          >
            <div class="item-content">
              <h3 class="item-title">{{ item.title }}</h3>
            </div>
            <span class="arrow">→</span>
          </router-link>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import TheHeader from '../components/TheHeader.vue';

interface CollectionItem {
  id: number;
  title: string;
  link: string;
}

const collections = ref<CollectionItem[]>([]);

onMounted(async () => {
  try {
    const res = await fetch('https://api.moonbeaut.top/api/collections.php', {
  credentials: 'include'
});
    if (!res.ok) throw new Error('获取收藏夹失败');
    const data = await res.json();
    collections.value = data.collections || [];
  } catch (e) {
    // 可选：错误处理
    console.error(e);
  }
});
</script>

<style scoped>
.collections-page {
  min-height: 100vh;
  background-color: var(--color-background);
}

.main-content {
  padding: var(--space-4);
}

.container {
  max-width: 800px;
  margin: 0 auto;
}

.collection-count {
  font-size: 0.9rem;
  color: var(--color-text-secondary);
  margin-bottom: var(--space-4);
}

.collections-list {
  background-color: var(--color-card);
  border-radius: 12px;
  overflow: hidden;
}

.collection-item {
  display: flex;
  align-items: center;
  padding: var(--space-4);
  border-bottom: 1px solid var(--color-border);
  text-decoration: none;
  color: var(--color-text-primary);
}

.collection-item:last-child {
  border-bottom: none;
}

.item-content {
  flex: 1;
}

.item-title {
  font-size: 1rem;
  font-weight: 500;
  margin: 0;
}

.arrow {
  color: var(--color-text-tertiary);
  font-size: 1.2rem;
}
</style>