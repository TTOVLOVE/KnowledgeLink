<template>
  <div class="publish-question">
    <TheHeader 
      title="问题发布" 
      :showBackButton="true"
    />
    
    <main class="main-content">
      <div class="container">
        <div class="form-group">
          <input 
            type="text" 
            class="title-input"
            v-model="title"
            placeholder="请输入问题标题"
          >
          <div class="similar-questions" v-if="similarQuestions.length > 0">
            <p class="hint">找到相似问题</p>
            <div class="similar-list">
              <div v-for="q in similarQuestions" :key="q.id" class="similar-item">
                {{ q.title }}
              </div>
            </div>
          </div>
        </div>

        <div class="form-group">
          <textarea 
            v-model="content"
            class="content-input"
            placeholder="详细描述你的问题..."
            rows="8"
          ></textarea>
        </div>

        <div class="toolbar">
          <button class="tool-button">
            <span class="tool-icon">📷</span>
          </button>
          <button class="tool-button">
            <span class="tool-icon">&lt;/&gt;</span>
          </button>
          <button class="tool-button">
            <span class="tool-icon">📅</span>
          </button>
        </div>

        <div class="tags-section">
          <h3 class="section-title">添加标签</h3>
          <div class="tags-container">
            <div 
              v-for="tag in selectedTags" 
              :key="tag"
              class="tag"
              @click="removeTag(tag)"
            >
              {{ tag }}
              <span class="remove-tag">×</span>
            </div>
            <input 
              v-model="newTag"
              class="tag-input"
              placeholder="添加标签..."
              @keyup.enter="addTag"
            >
          </div>
        </div>

        <div class="reward-section">
          <h3 class="section-title">设置悬赏</h3>
          <div class="reward-slider">
            <input 
              type="range" 
              v-model="reward" 
              min="1" 
              max="100" 
              class="slider"
            >
            <div class="reward-value">{{ reward }} 知识币</div>
          </div>
          <p class="balance-info">当前余额: {{ coin }} 知识币</p>
        </div>

        <div v-if="coin === 0" class="coin-warning" style="color:red; margin: 10px 0;">当前知识币数量为0，无法进行悬赏问题</div>
<button class="publish-button" @click="publishQuestion" :disabled="coin === 0">
  发布问题
</button>
      </div>
    </main>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import TheHeader from '../components/TheHeader.vue';

const router = useRouter();
const title = ref('');
const content = ref('');
const newTag = ref('');
const selectedTags = ref<string[]>([]);
const reward = ref(10);

const coin = ref(0);

onMounted(async () => {
  try {
    const res = await fetch('https://api.moonbeaut.top/api/user_info.php', {
      credentials: 'include'
    });
    const data = await res.json();
    if (data.status === 'success' && data.data && typeof data.data.coins !== 'undefined') {
      coin.value = data.data.coins;
    }
  } catch (e) {
    coin.value = 0;
  }
});

import { watch } from 'vue';
interface QuestionItem { id: number; title: string }
const similarQuestions = ref<QuestionItem[]>([]);
let searchTimeout: number | undefined;

watch(title, (newTitle) => {
  if (searchTimeout) clearTimeout(searchTimeout);
  if (!newTitle.trim()) {
    similarQuestions.value = [];
    return;
  }
  // 防抖，300ms后请求
  searchTimeout = window.setTimeout(async () => {
    try {
      const res = await fetch(`https://api.moonbeaut.top/api/questions.php?search=${encodeURIComponent(newTitle.trim())}`);
      const data = await res.json();
      if (data.status === 'success' && Array.isArray(data.data)) {
        // 只取前3个且排除与当前输入完全相同的标题
        similarQuestions.value = (data.data as QuestionItem[]).filter(q => q.title !== newTitle.trim()).slice(0, 3);
      } else {
        similarQuestions.value = [];
      }
    } catch {
      similarQuestions.value = [];
    }
  }, 300);
});

const addTag = () => {
  if (newTag.value && !selectedTags.value.includes(newTag.value)) {
    selectedTags.value.push(newTag.value);
  }
  newTag.value = '';
};

const removeTag = (tag: string) => {
  selectedTags.value = selectedTags.value.filter(t => t !== tag);
};

const publishQuestion = async () => {
  if (!title.value.trim() || !content.value.trim()) {
    alert('请填写完整标题和内容');
    return;
  }
  const payload = {
    title: title.value.trim(),
    content: content.value.trim(),
    reward: reward.value,
    tags: selectedTags.value
  };
  try {
    const res = await fetch('https://api.moonbeaut.top/api/questions.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      credentials: 'include',
      body: JSON.stringify(payload)
    });
    const data = await res.json();
    if (data.status === 'success') {
      alert('发布成功！');
      router.replace('/questions');
    } else {
      alert(data.error || '发布失败');
    }
  } catch (e) {
    alert('发布失败');
  }
};
</script>

<style scoped>
.publish-question {
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

.form-group {
  margin-bottom: var(--space-4);
}

.title-input {
  width: 100%;
  padding: var(--space-3);
  border: 1px solid var(--color-border);
  border-radius: 8px;
  font-size: 1.1rem;
  background-color: var(--color-card);
}

.similar-questions {
  margin-top: var(--space-2);
  padding: var(--space-3);
  background-color: var(--color-secondary);
  border-radius: 8px;
}

.hint {
  font-size: 0.9rem;
  color: var(--color-text-secondary);
  margin-bottom: var(--space-2);
}

.similar-list {
  display: flex;
  flex-direction: column;
  gap: var(--space-2);
}

.similar-item {
  padding: var(--space-2);
  background-color: var(--color-card);
  border-radius: 4px;
  font-size: 0.9rem;
  color: var(--color-text-secondary);
}

.content-input {
  width: 100%;
  padding: var(--space-3);
  border: 1px solid var(--color-border);
  border-radius: 8px;
  font-size: 1rem;
  background-color: var(--color-card);
  resize: vertical;
}

.toolbar {
  display: flex;
  gap: var(--space-2);
  margin-bottom: var(--space-4);
}

.tool-button {
  padding: var(--space-2);
  background-color: var(--color-secondary);
  border-radius: 4px;
}

.tool-icon {
  font-size: 1.2rem;
}

.section-title {
  font-size: 1rem;
  font-weight: 600;
  margin-bottom: var(--space-3);
}

.tags-container {
  display: flex;
  flex-wrap: wrap;
  gap: var(--space-2);
  padding: var(--space-3);
  background-color: var(--color-card);
  border-radius: 8px;
}

.tag {
  display: flex;
  align-items: center;
  gap: var(--space-1);
  padding: var(--space-1) var(--space-2);
  background-color: var(--color-secondary);
  border-radius: 16px;
  font-size: 0.9rem;
}

.remove-tag {
  cursor: pointer;
  color: var(--color-text-tertiary);
}

.tag-input {
  border: none;
  background: none;
  font-size: 0.9rem;
  color: var(--color-text-primary);
  min-width: 100px;
}

.reward-section {
  margin-bottom: var(--space-6);
}

.reward-slider {
  display: flex;
  align-items: center;
  gap: var(--space-3);
  margin-bottom: var(--space-2);
}

.slider {
  flex: 1;
  height: 4px;
  background-color: var(--color-secondary);
  border-radius: 2px;
  -webkit-appearance: none;
}

.slider::-webkit-slider-thumb {
  -webkit-appearance: none;
  width: 20px;
  height: 20px;
  background-color: var(--color-primary);
  border-radius: 50%;
  cursor: pointer;
}

.reward-value {
  font-size: 0.9rem;
  color: var(--color-primary);
  font-weight: 500;
  min-width: 80px;
}

.balance-info {
  font-size: 0.8rem;
  color: var(--color-text-tertiary);
}

.publish-button {
  width: 100%;
  padding: var(--space-3);
  background-color: var(--color-primary);
  color: white;
  border-radius: 8px;
  font-size: 1rem;
  font-weight: 500;
}
</style>