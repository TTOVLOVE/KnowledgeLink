<template>
  <div class="home-page">
    <TheHeader title="知遇星球" :showSearch="true" @search="handleSearch" />
    
    <main class="main-content">
      <div class="container">
        <!-- 分类区域 -->
        <div class="category-section">
          <CategoryGrid :categories="categories" />
        </div>
        <!-- Tab导航 -->
        <div class="tab-section">
          <TabNavigation 
            :tabs="tabs" 
            :activeTab="activeTab" 
            @change="handleTabChange" 
          />
        </div>
        
        <div v-if="loading" class="loading">
          加载中...
        </div>
        
        <div v-else-if="error" class="error">
          {{ error }}
        </div>
        
        <div v-else class="articles-list">
          <ArticleCard 
            v-for="article in articles" 
            :key="article.id" 
            :article="article" 
          />
        </div>
      </div>
    </main>
    
    <BottomNavigation active="home" />
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, watch } from 'vue';
// 已删除未使用的 get 导入
import TheHeader from '../components/TheHeader.vue';
import TabNavigation from '../components/TabNavigation.vue';
import ArticleCard from '../components/ArticleCard.vue';
import BottomNavigation from '../components/BottomNavigation.vue';
import CategoryGrid from '../components/CategoryGrid.vue';

// 文章类型定义
interface Author {
  id: number | string;
  name: string;
  avatar: string;
}

interface Article {
  id: number | string;
  title: string;
  author: Author;
  category: string;
  rating: number | string;
  cover: string;
  summary: string;
  created_at: string;
}

// 标签页配置
const tabs = [
  { id: 'recommended', label: '推荐' },
  { id: 'trending', label: '热榜' }
];

// 状态
const activeTab = ref('recommended');
const articles = ref<Article[]>([]);
const categories = ref([]);
const loading = ref(false);
const error = ref<string | null>(null);
const searchKeyword = ref('');

// 获取文章列表
const fetchArticles = async () => {
  try {
    loading.value = true;
    error.value = null;
    console.log('开始获取文章列表...');
    let url = 'https://api.moonbeaut.top/api/articles.php';
    const params = [];
    params.push(`type=${activeTab.value}`); // 新增：区分推荐和热榜
    if (searchKeyword.value) {
      params.push(`search=${encodeURIComponent(searchKeyword.value)}`);
    }
    if (params.length > 0) {
      url += '?' + params.join('&');
    }
    const response = await fetch(url, {
      credentials: 'include'
    });
    if (!response.ok) {
      throw new Error('获取文章列表失败');
    }
    const data = await response.json();
    console.log('文章列表响应:', data);
    if (data.status === 'success') {
      articles.value = (data.data || []).map((item: any) => ({
        id: item.id,
        title: item.title,
        author: {
          id: item.user_id,
          name: item.author_name,
          avatar: item.author_avatar || ''
        },
        category: item.category,
        rating: item.rating,
        cover: item.cover,
        summary: item.summary,
        created_at: item.created_at
      }));
    } else {
      throw new Error(data.error || '获取文章列表失败');
    }
  } catch (err) {
    console.error('获取文章列表失败:', err);
    error.value = err instanceof Error ? err.message : '获取文章列表失败，请稍后重试';
  } finally {
    loading.value = false;
  }
};

// 获取分类列表
const fetchCategories = async () => {
  try {
    console.log('开始获取分类列表...');
    const response = await fetch('https://api.moonbeaut.top/api/categories.php');
    if (!response.ok) {
      throw new Error('获取分类列表失败');
    }
    const data = await response.json();
    console.log('分类列表响应:', data);
    if (data.status === 'success') {
      categories.value = data.data;
    } else {
      throw new Error(data.error || '获取分类列表失败');
    }
  } catch (err) {
    console.error('获取分类列表失败:', err);
  }
};

// 监听标签页变化
watch(activeTab, () => {
  fetchArticles();
});

// 处理标签页切换
const handleTabChange = (tabId: string) => {
  activeTab.value = tabId;
};

function handleSearch(keyword: string) {
  searchKeyword.value = keyword;
  fetchArticles();
}
// 组件挂载时获取数据
onMounted(() => {
  fetchArticles();
  fetchCategories();
});
</script>

<style scoped>
.home-page {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}

.main-content {
  flex: 1;
  padding: var(--space-4) 0 calc(64px + var(--space-4));
}

.container {
  width: 100%;
  max-width: 1100px;
  margin: 0 auto;
  padding: 32px 24px 0 24px;
  box-sizing: border-box;
}

.category-section {
  background: #fff;
  border-radius: 18px;
  box-shadow: 0 2px 16px 0 rgba(0,0,0,0.06);
  padding: 8px 0 8px 0;
  margin-bottom: 16px;
  margin-top: 0;
  width: 100%;
  max-width: 400px;
  margin-left: auto;
  margin-right: auto;
  display: flex;
  justify-content: center;
}

@media (max-width: 600px) {
  .category-section {
    max-width: 320px;
    padding: 6px 0 6px 0;
  }
}

@media (max-width: 600px) {
  .category-section {
    max-width: 320px;
  }
}

.tab-section {
  display: flex;
  justify-content: center;
  margin-bottom: 24px;
}

.articles-list {
  padding-bottom: var(--space-4);
}

@media (min-width: 768px) {
  .articles-list {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
    gap: var(--space-4);
  }
}

.loading, .error {
  text-align: center;
  padding: 40px 0;
  color: var(--color-text-secondary);
}

.error {
  color: var(--color-error);
}

@media (max-width: 900px) {
  .container {
    max-width: 100%;
    padding: 16px 0 0 0;
  }
  .articles-list {
    grid-template-columns: 1fr;
    gap: 24px;
  }
  .container {
    padding-left: 8px;
    padding-right: 8px;
  }
  .category-section {
    max-width: 100%;
  }
}
</style>