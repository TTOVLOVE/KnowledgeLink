<template>
  <div class="user-actions">
    <div class="user-info">
      <img :src="author.avatar || 'https://via.placeholder.com/40'" :alt="author.name" class="user-avatar" />
      <span class="user-name">{{ author.name }}</span>
    </div>
    <div class="action-buttons">
      <button class="action-button" @click="showCoinSheet = true">
        <span class="action-icon">💰</span>
        <span class="action-text">投币</span>
      </button>
      <button class="action-button" @click="showCommentSheet = true">
        <span class="action-icon">💬</span>
        <span class="action-text">评论</span>
      </button>
      <button class="action-button" @click="toggleFavorite">
        <span class="action-icon star" :class="{ 'is-favorite': isFavorite }">★</span>
        <span class="action-text">收藏</span>
      </button>
    </div>

    <CommentSheet 
      :is-open="showCommentSheet"
      :article-id="props.articleId"
      @close="showCommentSheet = false"
    />
    
    <CoinSheet
      :is-open="showCoinSheet"
      :article-id="props.articleId"
      @close="showCoinSheet = false"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, watch } from 'vue';
// 已删除未使用的 Author 导入
import CommentSheet from './CommentSheet.vue';
import CoinSheet from './CoinSheet.vue';

const props = defineProps<{
  author: {
    id: string | number;
    name: string;
    avatar: string;
  };
  articleId: string | number;
  articleTitle: string;
  articleLink: string;
}>();

const showCommentSheet = ref(false);
const showCoinSheet = ref(false);
const isFavorite = ref(false);

// 检查是否已收藏
const checkFavorite = async () => {
  try {
    const res = await fetch('https://api.moonbeaut.top/api/collections.php?check=1&link=' + encodeURIComponent(props.articleLink), {
      credentials: 'include',
    });
    const data = await res.json();
    isFavorite.value = !!data.isFavorite;
  } catch (e) {
    isFavorite.value = false;
  }
};

onMounted(() => {
  checkFavorite();
});

watch(() => props.articleLink, () => {
  checkFavorite();
});

const toggleFavorite = async () => {
  try {
    const res = await fetch('https://api.moonbeaut.top/api/toggle_favorite.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      credentials: 'include',
      body: JSON.stringify({
        article_id: props.articleId,
        title: props.articleTitle,
        link: props.articleLink
      })
    });
    const data = await res.json();
    isFavorite.value = !!data.isFavorite;
  } catch (e) {
    // 可选：错误处理
  }
};
</script>

<style scoped>
.user-actions {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background-color: var(--color-card);
  border-radius: 12px;
  padding: var(--space-4);
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
  margin-top: var(--space-5);
}

.user-info {
  display: flex;
  align-items: center;
  gap: var(--space-2);
}

.user-avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  object-fit: cover;
}

.user-name {
  font-weight: 500;
  font-size: 1rem;
  color: var(--color-text-primary);
}

.action-buttons {
  display: flex;
  gap: var(--space-3);
  align-items: flex-start;
}

.action-button {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: var(--space-2);
  background: none;
  border: none;
  cursor: pointer;
  color: var(--color-text-secondary);
  transition: color 0.2s ease, transform 0.2s ease;
}

.action-button:hover {
  color: var(--color-primary);
  transform: translateY(-2px);
}

.action-icon {
  font-size: 1.2rem;
  margin-bottom: var(--space-1);
  line-height: 1;
}

.action-icon.star {
  color: var(--color-text-tertiary);
  transition: color 0.2s ease;
}

.action-icon.star.is-favorite {
  color: #ffd700;
}

.action-text {
  font-size: 0.8rem;
  font-weight: 500;
}

@media (max-width: 640px) {
  .user-actions {
    flex-direction: column;
    gap: var(--space-4);
  }
  
  .action-buttons {
    width: 100%;
    justify-content: space-around;
  }
}
</style>