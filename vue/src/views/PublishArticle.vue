<template>
  <div class="publish-article">
    <TheHeader 
      title="文章管理" 
      :showBackButton="true"
    />
    
    <main class="main-content">
      <div class="container">
        <div class="toolbar">
          <div class="toolbar-left">
            <button class="filter-button">
              <span class="icon">🔍</span>
              状态
            </button>
            <button class="filter-button">
              <span class="icon">⬇️</span>
              导入
            </button>
          </div>
          <button class="publish-button" @click="showPublishForm = true">
            <span class="icon">✏️</span>
            发布
          </button>
        </div>

        <div class="article-list">
          <div v-for="article in articles" :key="article.id" class="article-card">
            <img :src="article.cover" :alt="article.title" class="article-cover">
            <div class="article-content">
              <h3 class="article-title">{{ article.title }}</h3>
              <p class="article-desc">{{ article.description }}</p>
              <div class="article-meta">
                <span class="status" :class="article.status">{{ article.statusText }}</span>
                <span class="date">{{ article.date }}</span>
              </div>
            </div>
            <button class="more-button" @click="confirmDelete(article.id)">...</button>
          </div>
        </div>
      </div>
    </main>

    <!-- 发布新文章表单 -->
    <div class="publish-form" v-if="showPublishForm">
      <div class="form-header">
        <h2>发布新文章</h2>
        <button class="close-button" @click="showPublishForm = false">×</button>
      </div>

      <div class="form-content">
        <div class="form-group">
          <label>文章标题</label>
          <input 
            type="text" 
            v-model="newArticle.title"
            placeholder="请输入文章标题"
          >
        </div>

        <div class="form-group">
          <label>文章分类</label>
          <select v-model="newArticle.category">
            <option value="">请选择分类</option>
            <option value="python">Python</option>
            <option value="java">Java</option>
            <option value="react">React</option>
            <option value="vue">Vue</option>
            <option value="javascript">JavaScript</option>
            <option value="typescript">TypeScript</option>
            <option value="nodejs">Node.js</option>
            <option value="go">Go</option>
          </select>
        </div>

        <div class="form-group">
          <label>封面图片网址</label>
          <input 
            type="text" 
            v-model="newArticle.cover"
            placeholder="请输入封面图片网址"
          >
        </div>

        <div class="form-group">
          <label>文章摘要</label>
          <textarea 
            v-model="newArticle.summary"
            placeholder="请输入文章摘要..."
            rows="3"
          ></textarea>
        </div>

        <div class="form-group">
          <label>文章内容</label>
          <textarea 
            v-model="newArticle.content"
            placeholder="请输入文章内容..."
            rows="10"
          ></textarea>
        </div>
      </div>

      <div class="form-footer">
        <button class="cancel-button" @click="showPublishForm = false">取消</button>
        <button class="submit-button" @click="publishArticle">发布</button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import TheHeader from '../components/TheHeader.vue';

const showPublishForm = ref(false);
const articles = ref<any[]>([]);

const newArticle = ref({
  title: '',
  category: '',
  cover: '',
  summary: '',
  content: ''
});

// 分类英文名与ID映射（请根据实际数据库调整）
const categoryMap: Record<string, number> = {
  python: 3,
  java: 4,
  react: 8,
  vue: 7,
  javascript: 1,
  typescript: 2,
  nodejs: 6,
  go: 5
};

const fetchArticles = async () => {
  const res = await fetch('https://api.moonbeaut.top/api/articles.php?my=1', {
    credentials: 'include'
  });
  const data = await res.json();
  articles.value = data.data || [];
};

onMounted(() => {
  fetchArticles();
});

const publishArticle = async () => {
  if (!newArticle.value.title || !newArticle.value.category || !newArticle.value.content || !newArticle.value.summary) {
    alert('请填写完整信息');
    return;
  }
  const payload = {
    title: newArticle.value.title,
    category_id: categoryMap[newArticle.value.category],
    cover: newArticle.value.cover,
    summary: newArticle.value.summary,
    content: newArticle.value.content
  };
  try {
    const res = await fetch('https://api.moonbeaut.top/api/publish_article.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      credentials: 'include',
      body: JSON.stringify(payload)
    });
    const data = await res.json();
    if (data.success) {
      alert('发布成功！');
      showPublishForm.value = false;
      newArticle.value = { title: '', category: '', cover: '', summary: '', content: '' };
      fetchArticles();
    } else {
      alert(data.error || '发布失败');
    }
  } catch (e) {
    alert('发布失败');
  }
};

const confirmDelete = async (id: number | string) => {
  if (confirm('确定要删除这篇文章吗？')) {
    try {
      const res = await fetch(`https://api.moonbeaut.top/api/delete_article.php?id=${id}`, { method: 'DELETE' });
      const data = await res.json();
      if (data.success) {
        alert('删除成功！');
        fetchArticles();
      } else {
        alert(data.error || '删除失败');
      }
    } catch (e) {
      alert('删除失败');
    }
  }
};
</script>

<style scoped>
.publish-article {
  min-height: 100vh;
  background-color: var(--color-background);
}

.main-content {
  padding: var(--space-4);
}

.container {
  max-width: 1200px;
  margin: 0 auto;
}

.toolbar {
  display: flex;
  justify-content: space-between;
  margin-bottom: var(--space-4);
}

.toolbar-left {
  display: flex;
  gap: var(--space-3);
}

.filter-button {
  display: flex;
  align-items: center;
  gap: var(--space-2);
  padding: var(--space-2) var(--space-3);
  background-color: var(--color-card);
  border-radius: 8px;
  font-size: 0.9rem;
}

.publish-button {
  display: flex;
  align-items: center;
  gap: var(--space-2);
  padding: var(--space-2) var(--space-3);
  background-color: var(--color-primary);
  color: white;
  border-radius: 8px;
  font-size: 0.9rem;
}

.article-list {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: var(--space-4);
}

.article-card {
  background-color: var(--color-card);
  border-radius: 12px;
  overflow: hidden;
  position: relative;
}

.article-cover {
  width: 100%;
  height: 160px;
  object-fit: cover;
}

.article-content {
  padding: var(--space-3);
}

.article-title {
  font-size: 1.1rem;
  font-weight: 600;
  margin-bottom: var(--space-2);
}

.article-desc {
  font-size: 0.9rem;
  color: var(--color-text-secondary);
  margin-bottom: var(--space-3);
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.article-meta {
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 0.8rem;
}

.status {
  padding: 2px 8px;
  border-radius: 12px;
  background-color: var(--color-secondary);
}

.status.published {
  color: var(--color-success);
  background-color: rgba(16, 185, 129, 0.1);
}

.status.draft {
  color: var(--color-warning);
  background-color: rgba(245, 158, 11, 0.1);
}

.date {
  color: var(--color-text-tertiary);
}

.more-button {
  position: absolute;
  top: var(--space-2);
  right: var(--space-2);
  width: 32px;
  height: 32px;
  border-radius: 50%;
  background-color: rgba(255, 255, 255, 0.9);
  color: var(--color-text-secondary);
}

.publish-form {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: var(--color-background);
  z-index: 1000;
  display: flex;
  flex-direction: column;
}

.form-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: var(--space-4);
  border-bottom: 1px solid var(--color-border);
}

.form-header h2 {
  font-size: 1.2rem;
  margin: 0;
}

.close-button {
  font-size: 1.5rem;
  color: var(--color-text-tertiary);
}

.form-content {
  flex: 1;
  padding: var(--space-4);
  overflow-y: auto;
}

.form-group {
  margin-bottom: var(--space-4);
}

.form-group label {
  display: block;
  margin-bottom: var(--space-2);
  font-weight: 500;
}

.form-group input,
.form-group select,
.form-group textarea {
  width: 100%;
  padding: var(--space-3);
  border: 1px solid var(--color-border);
  border-radius: 8px;
  background-color: var(--color-card);
}

.cover-upload {
  border: 2px dashed var(--color-border);
  border-radius: 8px;
  padding: var(--space-6);
}

.upload-placeholder {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: var(--space-2);
  color: var(--color-text-tertiary);
}

.upload-icon {
  font-size: 2rem;
}

.form-footer {
  padding: var(--space-4);
  border-top: 1px solid var(--color-border);
  display: flex;
  justify-content: flex-end;
  gap: var(--space-3);
}

.cancel-button {
  padding: var(--space-2) var(--space-4);
  border: 1px solid var(--color-border);
  border-radius: 8px;
  color: var(--color-text-secondary);
}

.submit-button {
  padding: var(--space-2) var(--space-4);
  background-color: var(--color-primary);
  color: white;
  border-radius: 8px;
}
</style>