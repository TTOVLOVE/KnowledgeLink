<template>
  <div class="coin-dialog" :class="{ 'is-open': isOpen }">
    <div class="overlay" @click="close"></div>
    <div class="dialog-content">
      <button class="close-button" @click="close">×</button>
      
      <div class="coin-content">
        <div 
          class="coin-animation" 
          :class="{ 'animate': isAnimating }"
          @click="giveCoin"
        >
          <div class="coin">
            <div class="coin-front">知识币</div>
            <div class="coin-back">+1</div>
          </div>
        </div>
        
        <div class="coin-info">
          <p class="coin-count">已投 {{ coinCount }} 个币</p>
          <p class="coin-balance">我还有 {{ balance }} 个知识币</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue';

const props = defineProps<{
  isOpen: boolean;
  articleId: number | string;
}>();

const emit = defineEmits<{
  (e: 'close'): void;
}>();

const coinCount = ref(0);
const balance = ref(0);
const isAnimating = ref(false);

import { storeToRefs } from 'pinia';
import { useUserStore } from '../store/user';
const userStore = useUserStore();
const { user } = storeToRefs(userStore);

const fetchCoinInfo = async () => {
  if (!props.articleId) return;
  try {
    const res = await fetch(`https://api.moonbeaut.top/api/coins.php?article_id=${props.articleId}`, {
      credentials: 'include',
    });
    const data = await res.json();
    coinCount.value = data.coinCount || 0;
    balance.value = data.balance || 0;
  } catch (e) {
    coinCount.value = 0;
    balance.value = 0;
  }
};

watch(() => props.isOpen, (val) => {
  if (val) {
    // 打开投币弹窗时自动刷新用户信息，确保 session、余额等是最新的
    if (userStore.fetchUser) {
      userStore.fetchUser();
    }
    fetchCoinInfo();
  }
});

watch(() => props.articleId, () => {
  if (props.isOpen) fetchCoinInfo();
});

const close = () => {
  emit('close');
};

const giveCoin = async () => {
  if (isAnimating.value || balance.value <= 0) return;
  if (!user.value) {
    alert('请先登录');
    return;
  }
  isAnimating.value = true;
  try {
    const res = await fetch('https://api.moonbeaut.top/api/coins.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        article_id: props.articleId,
        user_id: user.value.id
      }),
      credentials: 'include'
    });
    const data = await res.json();
    if (data.success) {
      coinCount.value++;
      balance.value--;
    } else {
      alert(data.error || '投币失败');
    }
  } catch (e) {
    alert('投币失败');
  } finally {
    setTimeout(() => {
      isAnimating.value = false;
    }, 1000);
  }
};
</script>

<style scoped>
.coin-dialog {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  z-index: 1000;
  display: flex;
  align-items: center;
  justify-content: center;
  opacity: 0;
  pointer-events: none;
  transition: opacity 0.3s ease;
}

.coin-dialog.is-open {
  opacity: 1;
  pointer-events: auto;
}

.overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.5);
}

.dialog-content {
  position: relative;
  background-color: var(--color-card);
  border-radius: 20px;
  padding: var(--space-6);
  width: 90%;
  max-width: 320px;
  z-index: 1;
}

.close-button {
  position: absolute;
  top: var(--space-2);
  right: var(--space-2);
  font-size: 1.5rem;
  color: var(--color-text-tertiary);
  padding: var(--space-2);
}

.coin-content {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.coin-animation {
  width: 120px;
  height: 120px;
  perspective: 1000px;
  margin-bottom: var(--space-4);
  cursor: pointer;
  transition: transform 0.2s ease;
}

.coin-animation:hover {
  transform: scale(1.05);
}

.coin {
  width: 100%;
  height: 100%;
  position: relative;
  transform-style: preserve-3d;
  transition: transform 0.6s;
}

.coin-animation.animate .coin {
  transform: rotateX(720deg);
}

.coin-front,
.coin-back {
  position: absolute;
  width: 100%;
  height: 100%;
  backface-visibility: hidden;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  font-size: 1.4rem;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.coin-front {
  background: linear-gradient(45deg, #ffd700, #ffed4a);
  color: #b8860b;
}

.coin-back {
  background: linear-gradient(45deg, #ffed4a, #ffd700);
  color: #b8860b;
  transform: rotateX(180deg);
}

.coin-info {
  text-align: center;
}

.coin-count {
  font-size: 1.2rem;
  font-weight: 600;
  color: var(--color-primary);
  margin-bottom: var(--space-2);
}

.coin-balance {
  font-size: 0.9rem;
  color: var(--color-text-secondary);
}
</style>