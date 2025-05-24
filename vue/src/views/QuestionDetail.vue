<template>
  <div class="question-detail">
    <TheHeader 
      :showBackButton="true" 
      :breadcrumb="{ home: '问答广场', current: '问题详情' }"
    />
    
    <main class="main-content" v-if="question">
      <div class="container">
        <div class="question-card">
          <div class="question-header">
            <div class="author-info">
              <img :src="question.author.avatar" :alt="question.author.name" class="author-avatar">
              <span class="author-name">{{ question.author.name }}</span>
            </div>
            <div class="question-reward">
              悬赏 {{ question.reward }} 知识币
            </div>
          </div>
          
          <h1 class="question-title">{{ question.title }}</h1>
          <p class="question-content">{{ question.content }}</p>
          
          <div class="question-meta">
            <div class="question-tags">
              <span v-for="tag in question.tags" :key="tag" class="tag">
                {{ tag }}
              </span>
            </div>
            <span class="publish-time">{{ question.createdAt }}</span>
          </div>
        </div>

        <div class="answers-section">
          <h2 class="answers-title">{{ answers.length }}个回答</h2>
          
          <div class="answers-list">
            <div v-for="answer in answers" :key="answer.id" class="answer-card">
              <div class="answer-header">
                <div class="author-info">
                  <img :src="answer.author.avatar" :alt="answer.author.name" class="author-avatar">
                  <span class="author-name">{{ answer.author.name }}</span>
                </div>
                <span class="answer-time">{{ answer.createdAt }}</span>
              </div>
              
              <div class="answer-content">{{ answer.content }}</div>
              
              <div class="answer-footer">
                <button class="like-button" :class="{ liked: answer.isLiked }" @click="likeAnswer(answer.id)">
                  <span class="like-icon">👍</span>
                  <span class="like-count">{{ answer.likes }}</span>
                </button>
                
                <div v-if="answer.isAccepted" class="accepted-badge">
                  已采纳
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
    
    <div class="loading" v-else>
      <p>加载中...</p>
    </div>

    <div class="answer-input">
      <input 
        type="text" 
        v-model="newAnswer"
        placeholder="写下你的回答..."
        @keyup.enter="submitAnswer"
      >
      <button class="send-button" @click="submitAnswer">
        <span class="send-icon">✈️</span>
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import TheHeader from '../components/TheHeader.vue';

const route = useRoute();
const question = ref<any>(null);
const answers = ref<any[]>([]);
const loading = ref(true);
const error = ref('');
const newAnswer = ref('');

const fetchQuestionDetail = async () => {
  loading.value = true;
  error.value = '';
  try {
    const res = await fetch(`https://api.moonbeaut.top/api/questions.php?id=${route.params.id}`, {
      credentials: 'include',
    });
    const data = await res.json();
    if (data.status === 'success') {
      const q = data.data;
      question.value = {
        id: q.id,
        title: q.title,
        content: q.content,
        reward: q.reward,
        tags: Array.isArray(q.tags) ? q.tags : (typeof q.tags === 'string' && q.tags ? q.tags.split(',') : []),
        createdAt: q.created_at,
        author: {
          id: q.user_id,
          name: q.author_name,
          avatar: q.author_avatar || ''
        }
      };
      answers.value = (q.answers || []).map((a: any) => ({
        id: a.id,
        content: a.content,
        createdAt: a.created_at,
        author: {
          id: a.user_id,
          name: a.author_name,
          avatar: a.author_avatar || ''
        },
        likes: a.likes || 0,
        isLiked: false,
        isAccepted: false
      }));
    } else {
      error.value = data.error || '加载失败';
    }
  } catch (e) {
    error.value = '加载失败';
  } finally {
    loading.value = false;
  }
};

const submitAnswer = async () => {
  if (!newAnswer.value.trim()) return;
  const payload = {
    question_id: Number(route.params.id),
    content: newAnswer.value.trim()
  };
  try {
    const res = await fetch('https://api.moonbeaut.top/api/answers.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      credentials: 'include',
      body: JSON.stringify(payload)
    });
    const data = await res.json();
    if (data.status === 'success') {
      newAnswer.value = '';
      fetchQuestionDetail();
      // 自动聚焦输入框
      setTimeout(() => {
        const input = document.querySelector('.answer-input input') as HTMLInputElement;
        if (input) input.focus();
      }, 10);
    } else {
      alert(data.error || '提交失败');
    }
  } catch (e) {
    alert('提交失败');
  }
};

const likeAnswer = async (answerId: number) => {
  try {
    const res = await fetch('https://api.moonbeaut.top/api/answers.php?action=like', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ answer_id: answerId })
    });
    const data = await res.json();
    if (data.status === 'success') {
      fetchQuestionDetail();
    } else {
      alert(data.error || '点赞失败');
    }
  } catch (e) {
    alert('点赞失败');
  }
};

onMounted(() => {
  fetchQuestionDetail();
});
</script>

<style scoped>
.question-detail {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
  background-color: var(--color-background);
}

.main-content {
  flex: 1;
  padding: var(--space-3) 0 calc(48px + var(--space-4));
}

.container {
  padding: 0 var(--space-3);
}

.question-card {
  background-color: var(--color-card);
  border-radius: 12px;
  padding: var(--space-4);
  margin-bottom: var(--space-4);
}

.question-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: var(--space-3);
}

.author-info {
  display: flex;
  align-items: center;
  gap: var(--space-2);
}

.author-avatar {
  width: 32px;
  height: 32px;
  border-radius: 50%;
  object-fit: cover;
}

.author-name {
  font-size: 0.9rem;
  color: var(--color-text-secondary);
}

.question-reward {
  font-size: 0.9rem;
  color: var(--color-primary);
  font-weight: 500;
}

.question-title {
  font-size: 1.2rem;
  font-weight: 600;
  margin-bottom: var(--space-3);
  color: var(--color-text-primary);
  line-height: 1.4;
}

.question-content {
  font-size: 0.95rem;
  line-height: 1.6;
  color: var(--color-text-primary);
  margin-bottom: var(--space-3);
}

.question-meta {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.question-tags {
  display: flex;
  gap: var(--space-2);
}

.tag {
  padding: 2px 8px;
  border-radius: 4px;
  background-color: var(--color-secondary);
  color: var(--color-text-secondary);
  font-size: 0.8rem;
}

.publish-time {
  font-size: 0.8rem;
  color: var(--color-text-tertiary);
}

.answers-section {
  margin-bottom: var(--space-4);
}

.answers-title {
  font-size: 1.1rem;
  font-weight: 600;
  margin-bottom: var(--space-3);
  color: var(--color-text-primary);
}

.answers-list {
  display: flex;
  flex-direction: column;
  gap: var(--space-3);
}

.answer-card {
  background-color: var(--color-card);
  border-radius: 12px;
  padding: var(--space-3);
}

.answer-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: var(--space-2);
}

.answer-time {
  font-size: 0.8rem;
  color: var(--color-text-tertiary);
}

.answer-content {
  font-size: 0.95rem;
  line-height: 1.6;
  color: var(--color-text-primary);
  margin-bottom: var(--space-3);
}

.answer-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.like-button {
  display: flex;
  align-items: center;
  gap: var(--space-1);
  padding: var(--space-1) var(--space-2);
  border-radius: 4px;
  background-color: var(--color-secondary);
  color: var(--color-text-secondary);
  font-size: 0.9rem;
}

.like-button.liked {
  color: var(--color-primary);
}

.accepted-badge {
  padding: 2px 8px;
  border-radius: 4px;
  background-color: var(--color-success);
  color: white;
  font-size: 0.8rem;
  font-weight: 500;
}

.answer-input {
  position: fixed;
  bottom: 0;
  left: 0;
  right: 0;
  padding: var(--space-2) var(--space-3);
  background-color: var(--color-card);
  border-top: 1px solid var(--color-border);
  display: flex;
  gap: var(--space-2);
  align-items: center;
}

.answer-input input {
  flex: 1;
  padding: var(--space-2) var(--space-3);
  border: 1px solid var(--color-border);
  border-radius: 20px;
  background-color: var(--color-secondary);
  font-size: 0.9rem;
  color: var(--color-text-primary);
}

.send-button {
  width: 36px;
  height: 36px;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: var(--color-primary);
  border-radius: 50%;
  color: white;
  transition: transform 0.2s ease;
}

.send-button:hover {
  transform: translateX(2px);
}

.send-icon {
  font-size: 1.2rem;
  transform: rotate(45deg);
}

.loading {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 50vh;
  font-size: 1rem;
  color: var(--color-text-secondary);
}

@media (max-width: 640px) {
  .container {
    padding: 0 var(--space-2);
  }
  
  .question-card,
  .answer-card {
    padding: var(--space-2);
  }
  
  .question-title {
    font-size: 1.1rem;
  }
}
</style>