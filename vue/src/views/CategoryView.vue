<template>
  <div class="category-view">
    <TheHeader 
      :title="Array.isArray(categoryName) ? categoryName[0] : categoryName" 
      :showBackButton="true"
      :showSearch="true"
    />
    
    <main class="main-content">
      <div class="container">
        <div class="articles-list">
          <ArticleCard 
            v-for="article in articles" 
            :key="(article as any).id" 
            :article="article" 
          />
        </div>
      </div>
    </main>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import TheHeader from '../components/TheHeader.vue';
import ArticleCard from '../components/ArticleCard.vue';

const route = useRoute();
const articles = ref([]);

// 分类英文名与ID映射（请根据实际数据库调整）
const categoryMap: Record<string, number> = {
  python: 3,
  java: 4,
  react: 8,
  'vue.js': 7,
  javascript: 1,
  typescript: 2,
  'node.js': 6,
  go: 5
};

const categoryId = computed(() => categoryMap[route.params.id as string] || 0);

const categoryName = computed(() => {
  const categories: Record<string, string> = {
    python: 'Python',
    java: 'Java',
    react: 'React',
    vue: 'Vue.js',
    javascript: 'JavaScript',
    typescript: 'TypeScript',
    nodejs: 'Node.js',
    go: 'Go'
  };
  return categories[route.params.id as string] || route.params.id;
});

onMounted(async () => {
  if (!categoryId.value) return;
  const res = await fetch(`https://api.moonbeaut.top/api/articles.php?category_id=${categoryId.value}`, {
    credentials: 'include'
  });
  const data = await res.json();
  articles.value = data.data || [];
});
</script>

<style scoped>
.category-view {
  min-height: 100vh;
  background-color: var(--color-background);
}

.main-content {
  padding: var(--space-4) 0;
}

.container {
  padding: 0 var(--space-3);
}

.articles-list {
  display: grid;
  gap: var(--space-4);
}

@media (min-width: 768px) {
  .articles-list {
    grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
  }
}
</style>