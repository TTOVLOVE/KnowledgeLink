<template>
  <div class="points-details">
    <TheHeader 
      title="明细" 
      :showBackButton="true"
    />
    
    <main class="main-content">
      <div class="container">
        <div class="filter-section">
          <button class="filter-button">
            全部类型
            <span class="arrow-down">▼</span>
          </button>
          <button class="filter-button">
            所有时间
            <span class="arrow-down">▼</span>
          </button>
        </div>

        <div class="details-list">
          <div v-for="detail in details" :key="detail.id" class="detail-item">
            <div class="detail-info">
              <div class="detail-title">{{ detail.title }}</div>
              <div class="detail-time">{{ detail.time }}</div>
            </div>
            <div 
              class="detail-points"
              :class="{ 
                'positive': detail.points > 0,
                'negative': detail.points < 0
              }"
            >
              {{ detail.points > 0 ? '+' : '' }}{{ detail.points }}{{ detail.type }}
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import TheHeader from '../components/TheHeader.vue';

interface DetailItem {
  id: number;
  title: string;
  time: string;
  points: number;
  type: string;
}

const details = ref<DetailItem[]>([]);

onMounted(async () => {
  try {
    const res = await fetch('https://api.moonbeaut.top/api/points_details.php', {
  credentials: 'include'
});
    if (!res.ok) throw new Error('获取积分明细失败');
    const data = await res.json();
    details.value = data.details || [];
  } catch (e) {
    // 可选：错误处理
    console.error(e);
  }
});
</script>

<style scoped>
.points-details {
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

.filter-section {
  display: flex;
  gap: var(--space-3);
  margin-bottom: var(--space-4);
}

.filter-button {
  display: flex;
  align-items: center;
  gap: var(--space-2);
  padding: var(--space-2) var(--space-3);
  background-color: var(--color-secondary);
  border-radius: 16px;
  font-size: 0.9rem;
  color: var(--color-text-secondary);
}

.arrow-down {
  font-size: 0.8rem;
}

.details-list {
  background-color: var(--color-card);
  border-radius: 12px;
  overflow: hidden;
}

.detail-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: var(--space-4);
  border-bottom: 1px solid var(--color-border);
}

.detail-item:last-child {
  border-bottom: none;
}

.detail-title {
  font-size: 1rem;
  font-weight: 500;
  margin-bottom: var(--space-1);
  color: var(--color-text-primary);
}

.detail-time {
  font-size: 0.8rem;
  color: var(--color-text-tertiary);
}

.detail-points {
  font-weight: 500;
}

.detail-points.positive {
  color: var(--color-success);
}

.detail-points.negative {
  color: var(--color-error);
}
</style>