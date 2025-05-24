<template>
  <div class="questions-page">
    <TheHeader title="问答广场" :showSearch="true" @search="handleSearch" />
    
    <main class="main-content">
      <div class="container">
        <!-- Latest Questions Section -->
        <div class="latest-questions">
          <div class="section-header">
            <div class="header-left">
              <span class="icon">⚡</span>
              <h2 class="section-title">最新提问</h2>
            </div>
          </div>
          
          <router-link 
            :to="`/question/${latestQuestion.id}`" 
            class="latest-question-card" 
            v-if="latestQuestion"
          >
            <div class="author-info">
              <img :src="latestQuestion.author.avatar" :alt="latestQuestion.author.name" class="author-avatar">
              <span class="author-name">{{ latestQuestion.author.name }}</span>
              <span class="question-reward">悬赏 {{ latestQuestion.reward }} 知识币</span>
            </div>
            
            <h3 class="question-title">{{ latestQuestion.title }}</h3>
            
            <div class="question-tags">
              <span v-for="tag in latestQuestion.tags" :key="tag" class="tag">
                {{ tag }}
              </span>
            </div>
            
            <div class="question-stats">
              <span class="comment-count">{{ latestQuestion.answerCount }} 回答</span>
              <span class="dot">·</span>
              <span class="view-count">0 浏览</span>
            </div>
          </router-link>
        </div>

        <div class="filter-tabs">
          <button 
            v-for="tab in filterTabs" 
            :key="tab.id"
            class="filter-tab"
            :class="{ active: activeFilter === tab.id }"
            @click="activeFilter = tab.id"
          >
            {{ tab.label }}
          </button>
        </div>

        <div class="questions-list">
          <router-link
            v-for="question in questions"
            :key="question.id"
            :to="`/question/${question.id}`"
            class="question-card"
          >
            <div class="question-header">
              <img :src="question.author.avatar" :alt="question.author.name" class="author-avatar">
              <span class="author-name">{{ question.author.name }}</span>
              <span class="question-reward">悬赏 {{ question.reward }} 知识币</span>
            </div>
            
            <h3 class="question-title">{{ question.title }}</h3>
            
            <div class="question-footer">
              <div class="question-tags">
                <span 
                  v-for="tag in question.tags" 
                  :key="tag"
                  class="tag"
                >
                  {{ tag }}
                </span>
              </div>
              <div class="question-meta">
                <span class="answer-count">{{ question.answerCount }} 回答</span>
                <span class="publish-time">{{ question.createdAt }}</span>
              </div>
            </div>
          </router-link>
        </div>
      </div>
    </main>

    <button class="fab-button" @click="navigateToPublishQuestion">
      <span class="fab-icon">+</span>
      <span class="fab-text">提问</span>
    </button>
    
    <BottomNavigation active="questions" />
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import TheHeader from '../components/TheHeader.vue';
import BottomNavigation from '../components/BottomNavigation.vue';

const router = useRouter();
const activeFilter = ref('all');
const filterTabs = [
  { id: 'all', label: '全部' },
  { id: 'tech', label: '技术' },
  { id: 'product', label: '产品' }
];

const questions = ref<any[]>([]);
const latestQuestion = ref<any | null>(null);
const loading = ref(false);
const error = ref('');
const searchKeyword = ref('');

const fetchQuestions = async (search = '') => {
  loading.value = true;
  error.value = '';
  let url = 'https://api.moonbeaut.top/api/questions.php';
  if (search) {
    url += `?search=${encodeURIComponent(search)}`;
  }
  try {
    const res = await fetch(url);
    const data = await res.json();
    if (data.status === 'success') {
      questions.value = (data.data || []).map((q: any) => ({
        id: q.id,
        title: q.title,
        content: q.content,
        reward: q.reward,
        tags: q.tags || [],
        answerCount: q.answerCount || 0,
        createdAt: q.created_at,
        author: {
          id: q.user_id,
          name: q.author_name,
          avatar: q.author_avatar || ''
        }
      }));
      latestQuestion.value = questions.value[0] || null;
    } else {
      error.value = data.error || '获取问题失败';
    }
  } catch (e) {
    error.value = '获取问题失败';
  } finally {
    loading.value = false;
  }
};

const handleSearch = (keyword: string) => {
  searchKeyword.value = keyword;
  fetchQuestions(keyword);
};

onMounted(() => {
  fetchQuestions();
});

const navigateToPublishQuestion = () => {
  router.push('/publish-question');
};
</script>

<style scoped>
.questions-page {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
  background-color: var(--color-background);
}

.main-content {
  flex: 1;
  padding: var(--space-3) 0 calc(64px + var(--space-4));
}

.container {
  padding: 0 var(--space-3);
}

/* Latest Questions Section */
.latest-questions {
  background-color: var(--color-card);
  border-radius: 12px;
  padding: var(--space-3);
  margin-bottom: var(--space-4);
}

.section-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: var(--space-3);
}

.header-left {
  display: flex;
  align-items: center;
  gap: var(--space-2);
}

.icon {
  font-size: 1.2rem;
}

.section-title {
  font-size: 1.1rem;
  font-weight: 600;
  margin: 0;
}

.question-button {
  padding: var(--space-1) var(--space-3);
  background-color: var(--color-primary);
  color: white;
  border-radius: 16px;
  font-size: 0.9rem;
  font-weight: 500;
}

.latest-question-card {
  background-color: var(--color-secondary);
  border-radius: 8px;
  padding: var(--space-3);
  display: block;
  text-decoration: none;
  color: inherit;
  transition: transform 0.2s ease;
}

.latest-question-card:hover {
  transform: translateY(-2px);
}

.author-info {
  display: flex;
  align-items: center;
  gap: var(--space-2);
  margin-bottom: var(--space-2);
}

.author-avatar {
  width: 24px;
  height: 24px;
  border-radius: 50%;
  object-fit: cover;
}

.author-name {
  font-size: 0.9rem;
  color: var(--color-text-secondary);
}

.question-reward {
  margin-left: auto;
  font-size: 0.8rem;
  color: var(--color-primary);
  font-weight: 500;
}

.question-title {
  font-size: 1rem;
  font-weight: 600;
  margin-bottom: var(--space-2);
  color: var(--color-text-primary);
  line-height: 1.4;
}

.question-tags {
  display: flex;
  gap: var(--space-2);
  margin-bottom: var(--space-2);
}

.tag {
  padding: 2px 8px;
  border-radius: 4px;
  background-color: white;
  color: var(--color-text-secondary);
  font-size: 0.8rem;
}

.question-stats {
  display: flex;
  align-items: center;
  gap: var(--space-2);
  font-size: 0.8rem;
  color: var(--color-text-tertiary);
}

.dot {
  color: var(--color-text-tertiary);
}

/* Filter Tabs */
.filter-tabs {
  display: flex;
  gap: var(--space-3);
  margin-bottom: var(--space-4);
  padding: var(--space-2) 0;
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
}

.filter-tab {
  padding: var(--space-2) var(--space-3);
  border-radius: 16px;
  background-color: var(--color-secondary);
  color: var(--color-text-secondary);
  font-size: 0.9rem;
  white-space: nowrap;
  transition: all 0.2s ease;
}

.filter-tab.active {
  background-color: var(--color-primary);
  color: white;
}

.questions-list {
  display: flex;
  flex-direction: column;
  gap: var(--space-3);
}

.question-card {
  background-color: var(--color-card);
  border-radius: 12px;
  padding: var(--space-3);
  text-decoration: none;
  color: inherit;
  transition: transform 0.2s ease;
}

.question-card:hover {
  transform: translateY(-2px);
}

.question-header {
  display: flex;
  align-items: center;
  gap: var(--space-2);
  margin-bottom: var(--space-2);
}

.question-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.question-meta {
  display: flex;
  gap: var(--space-3);
  font-size: 0.8rem;
  color: var(--color-text-tertiary);
}

.fab-button {
  position: fixed;
  right: var(--space-4);
  bottom: calc(64px + var(--space-4));
  display: flex;
  align-items: center;
  gap: var(--space-2);
  padding: var(--space-2) var(--space-3);
  background-color: var(--color-primary);
  color: white;
  border-radius: 20px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.fab-icon {
  font-size: 1.2rem;
  font-weight: bold;
}

.fab-text {
  font-size: 0.9rem;
  font-weight: 500;
}

@media (max-width: 640px) {
  .container {
    padding: 0 var(--space-2);
  }
  
  .question-card {
    padding: var(--space-2);
  }
  
  .latest-questions {
    padding: var(--space-2);
  }
  
  .latest-question-card {
    padding: var(--space-2);
  }
}
</style>