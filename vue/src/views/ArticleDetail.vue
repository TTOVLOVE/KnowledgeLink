<template>
  <div class="article-detail">
    <TheHeader 
      :showBackButton="true" 
      :breadcrumb="{ home: '共享星球', current: article?.title || '' }"
    />
    
    <main class="main-content" v-if="article">
      <div class="container">
        <article class="article">
          <header class="article-header">
            <h1 class="article-title">{{ article.title }}</h1>
            <div class="article-meta">
              <span class="publish-date">发布日期: {{ article.publishDate }}</span>
              <span class="author">
                <img v-if="article.author.avatar" :src="article.author.avatar" class="author-avatar" />
                作者: {{ article.author.name }}
              </span>
            </div>
          </header>
          
          <div class="article-body" v-html="article.content"></div>
          
          <UserActions 
            :author="article.author" 
            :article-id="article.id"
            :article-title="article.title"
            :article-link="`/article/${article.id}`"
          />
        </article>
      </div>
    </main>
    
    <div class="loading" v-else-if="loading">
      <p>加载中...</p>
    </div>
    
    <div class="error" v-else-if="error">
      <p>{{ error }}</p>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import TheHeader from '../components/TheHeader.vue';
import UserActions from '../components/UserActions.vue';

// 文章类型定义
interface Author {
  id: string;
  name: string;
  avatar: string;
}
interface Article {
  id: string;
  title: string;
  content: string;
  publishDate: string;
  author: Author;
}

const route = useRoute();
const article = ref<Article | null>(null);
const loading = ref(true);
const error = ref<string | null>(null);

onMounted(async () => {
  const id = route.params.id;
  if (!id) {
    error.value = '无效的文章ID';
    loading.value = false;
    return;
  }
  
  loading.value = true;
  try {
    const res = await fetch(`https://api.moonbeaut.top/api/articles.php?id=${id}`, {
      credentials: 'include'
    });
    if (!res.ok) {
      const data = await res.json();
      throw new Error(data.error || '请求失败');
    }
    const data = await res.json();
    article.value = data;
  } catch (err) {
    error.value = err instanceof Error ? err.message : '加载文章失败';
    article.value = null;
  } finally {
    loading.value = false;
  }
});
</script>

<style scoped>
.article-detail {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
  background-color: var(--color-background);
}

.main-content {
  flex: 1;
  padding: var(--space-3) 0 var(--space-4);
}

.container {
  width: 100%;
  max-width: 800px;
  padding: 0 var(--space-3);
  margin: 0 auto;
  box-sizing: border-box;
}

.article {
  background-color: var(--color-card);
  border-radius: 12px;
  padding: var(--space-4);
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
  overflow-wrap: break-word;
  word-wrap: break-word;
  hyphens: auto;
}

.article-header {
  margin-bottom: var(--space-4);
}

.article-title {
  font-size: 1.4rem;
  font-weight: 700;
  margin-bottom: var(--space-3);
  line-height: 1.4;
  color: var(--color-text-primary);
}

.article-meta {
  display: flex;
  flex-direction: column;
  gap: var(--space-2);
  font-size: 0.85rem;
  color: var(--color-text-tertiary);
}

.author-avatar {
  width: 24px;
  height: 24px;
  border-radius: 50%;
  margin-right: 6px;
  vertical-align: middle;
}

.article-body {
  font-size: 0.95rem;
  line-height: 1.8;
  color: var(--color-text-primary);
}

.article-body :deep(img) {
  max-width: 100%;
  height: auto;
  border-radius: 8px;
  margin: var(--space-3) 0;
}

.article-body :deep(h2) {
  font-size: 1.2rem;
  margin: var(--space-5) 0 var(--space-3);
  font-weight: 600;
  color: var(--color-text-primary);
}

.article-body :deep(h3) {
  font-size: 1.1rem;
  margin: var(--space-4) 0 var(--space-2);
  font-weight: 600;
  color: var(--color-text-primary);
}

.article-body :deep(h4) {
  font-size: 1rem;
  margin: var(--space-3) 0 var(--space-2);
  font-weight: 500;
  color: var(--color-text-primary);
}

.article-body :deep(p) {
  margin-bottom: var(--space-3);
}

.article-body :deep(pre) {
  background-color: var(--color-secondary);
  padding: var(--space-3);
  border-radius: 8px;
  overflow-x: auto;
  margin: var(--space-3) 0;
  font-family: monospace;
  font-size: 0.85rem;
  line-height: 1.6;
  white-space: pre-wrap;
  word-break: break-word;
}

.article-body :deep(code) {
  background-color: var(--color-secondary);
  padding: 0.2em 0.4em;
  border-radius: 4px;
  font-size: 0.85em;
  font-family: monospace;
}

.article-body :deep(ul), 
.article-body :deep(ol) {
  margin: var(--space-2) 0 var(--space-3);
  padding-left: var(--space-4);
}

.article-body :deep(li) {
  margin-bottom: var(--space-2);
}

.article-body :deep(a) {
  color: var(--color-accent);
  text-decoration: none;
  word-break: break-word;
}

.article-body :deep(a:hover) {
  text-decoration: underline;
}

.loading {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 50vh;
  font-size: 1rem;
  color: var(--color-text-secondary);
}

.error {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 50vh;
  font-size: 1rem;
  color: var(--color-error);
  text-align: center;
  padding: var(--space-3);
}

@media (max-width: 640px) {
  .container {
    padding: 0 var(--space-2);
  }
  
  .article {
    padding: var(--space-3);
    border-radius: 8px;
  }
  
  .article-title {
    font-size: 1.2rem;
  }
  
  .article-body {
    font-size: 0.9rem;
  }
  
  .article-meta {
    font-size: 0.8rem;
  }
  
  .article-body :deep(pre) {
    font-size: 0.8rem;
    padding: var(--space-2);
    margin: var(--space-2) calc(var(--space-2) * -1);
    border-radius: 0;
  }
  
  .article-body :deep(img) {
    margin: var(--space-2) calc(var(--space-2) * -1);
    border-radius: 0;
    width: calc(100% + var(--space-2) * 2);
    max-width: none;
  }
  
  .article-body :deep(ul),
  .article-body :deep(ol) {
    padding-left: var(--space-3);
  }
}
</style>