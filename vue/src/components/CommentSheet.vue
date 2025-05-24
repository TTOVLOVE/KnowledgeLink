<template>
  <div class="comment-sheet" :class="{ 'is-open': isOpen }">
    <div class="overlay" @click="close"></div>
    <div class="sheet-content">
      <div class="sheet-header">
        <h3 class="sheet-title">共{{ comments.length }}条评论</h3>
        <button class="close-button" @click="close">×</button>
      </div>
      
      <div class="comments-list">
        <div v-for="comment in comments" :key="comment.id" class="comment-item">
          <img :src="getAvatarUrl(comment.avatar)" :alt="comment.username" class="comment-avatar">
          <div class="comment-content">
            <div class="comment-header">
              <span class="comment-username">{{ comment.username }}</span>
              <span class="comment-time">{{ comment.time }}</span>
            </div>
            <p class="comment-text">{{ comment.text }}</p>
          </div>
        </div>
      </div>
      
      <div class="comment-input">
        <input 
          type="text" 
          v-model="newComment" 
          placeholder="说两句吧..."
          @keyup.enter="submitComment"
        >
        <button class="submit-button" @click="submitComment">发送</button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
function getAvatarUrl(avatar: string) {
  if (!avatar) return '/images/默认打卡.png';
  if (/^https?:\/\//.test(avatar)) return avatar;
  // 兼容 uploads/xxx.png 或 xxx.png
  return `https://api.moonbeaut.top/uploads/${avatar.replace(/^uploads[\\/]/, '')}`;
}

import { ref, watch } from 'vue';

interface Comment {
  id: number;
  username: string;
  avatar: string;
  text: string;
  time: string;
}

const props = defineProps<{
  isOpen: boolean;
  articleId: number | string;
}>();

const emit = defineEmits<{
  (e: 'close'): void;
}>();

const newComment = ref('');
const comments = ref<Comment[]>([]);

import { storeToRefs } from 'pinia';
import { useUserStore } from '../store/user';
const userStore = useUserStore();
const { user } = storeToRefs(userStore);

const fetchComments = async () => {
  if (!props.articleId) return;
  try {
    const res = await fetch(`https://api.moonbeaut.top/api/comments.php?article_id=${props.articleId}`);
    const data = await res.json();
    comments.value = data.comments || [];
  } catch (e) {
    comments.value = [];
  }
};

watch(() => props.isOpen, (val) => {
  if (val) fetchComments();
});

watch(() => props.articleId, () => {
  if (props.isOpen) fetchComments();
});

const close = () => {
  emit('close');
};

const submitComment = async () => {
  if (!newComment.value.trim()) return;
  if (!user.value || !user.value.id) {
    alert('请先登录');
    return;
  }
  try {
    const res = await fetch('https://api.moonbeaut.top/api/comments.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      credentials: 'include',
      body: JSON.stringify({
        article_id: props.articleId,
        user_id: user.value.id,
        username: user.value.username || user.value.nickname || user.value.name,
        avatar: user.value.avatar,
        text: newComment.value
      })
    });
    const data = await res.json();
    if (data.success) {
      newComment.value = '';
      await fetchComments();
    }
  } catch (e) {}
};
</script>

<style scoped>
.comment-sheet {
  position: fixed;
  bottom: 0;
  left: 0;
  right: 0;
  z-index: 1000;
  transform: translateY(100%);
  transition: transform 0.3s ease-in-out;
  display: flex;
  flex-direction: column;
  /* background: transparent; */
}

.comment-sheet.is-open {
  transform: translateY(0);
}

.overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.01);
  opacity: 0;
  transition: opacity 0.3s ease;
}

.is-open .overlay {
  opacity: 1;
}

.sheet-content {
  position: relative;
  background-color: var(--color-card);
  border-radius: 20px 20px 0 0;
  padding: var(--space-4);
  max-height: 80vh;
  display: flex;
  flex-direction: column;
  margin-top: auto;
  overflow: hidden;
  box-shadow: 0 -4px 16px rgba(0, 0, 0, 0.06);
}

.sheet-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: var(--space-4);
}

.sheet-title {
  margin: 0;
  font-size: 1.1rem;
  font-weight: 600;
}

.close-button {
  font-size: 1.5rem;
  color: var(--color-text-tertiary);
  padding: var(--space-2);
}

.comments-list {
  flex: 1;
  overflow-y: auto;
  margin-bottom: var(--space-4);
  -webkit-overflow-scrolling: touch;
}

.comment-item {
  display: flex;
  gap: var(--space-3);
  margin-bottom: var(--space-4);
}

.comment-avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  object-fit: cover;
}

.comment-content {
  flex: 1;
}

.comment-header {
  display: flex;
  justify-content: space-between;
  margin-bottom: var(--space-1);
}

.comment-username {
  font-weight: 500;
  color: var(--color-text-primary);
}

.comment-time {
  font-size: 0.8rem;
  color: var(--color-text-tertiary);
}

.comment-text {
  color: var(--color-text-primary);
  margin: 0;
}

.comment-input {
  display: flex;
  gap: var(--space-2);
  padding: var(--space-3);
  background-color: var(--color-secondary);
  border-radius: 12px;
  margin-top: auto;
}

.comment-input input {
  flex: 1;
  border: none;
  background: none;
  font-size: 0.9rem;
  color: var(--color-text-primary);
}

.comment-input input:focus {
  outline: none;
}

.submit-button {
  padding: var(--space-2) var(--space-3);
  background-color: var(--color-primary);
  color: white;
  border-radius: 6px;
  font-size: 0.9rem;
  font-weight: 500;
}

.submit-button:hover {
  background-color: var(--color-primary-dark);
}
</style>