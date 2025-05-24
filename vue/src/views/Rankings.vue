<template>
  <div class="rankings-page">
    <TheHeader title="排行榜" :showBackButton="false" class="rankings-header" />
    
    <main class="main-content">
      <div class="container">
        <div class="toggle-container">
          <button 
            class="toggle-button" 
            :class="{ active: activeTab === 'experts' }"
            @click="activeTab = 'experts'"
          >
            知识达人
          </button>
          <button 
            class="toggle-button" 
            :class="{ active: activeTab === 'checkins' }"
            @click="activeTab = 'checkins'"
          >
            打卡榜
          </button>
        </div>

        <div class="rankings-list">
          <div v-for="(user, index) in currentRankings" :key="user.id" class="ranking-item">
            <div class="rank-number">{{ index + 1 }}</div>
            <img :src="user.avatar" :alt="user.name" class="user-avatar">
            <div class="user-name">{{ user.name }}</div>
            <div class="user-stats">
              <template v-if="activeTab === 'experts'">
                获得知识币 {{ (user as ExpertUser).coins }}枚
              </template>
              <template v-else>
                已坚持打卡 {{ (user as CheckinUser).days }}天
              </template>
            </div>
          </div>
        </div>
      </div>
    </main>
    
    <BottomNavigation active="rankings" />
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue';
import TheHeader from '../components/TheHeader.vue';
import BottomNavigation from '../components/BottomNavigation.vue';

const activeTab = ref('experts');

interface ExpertUser {
  id: number;
  name: string;
  avatar: string;
  coins: number;
}

interface CheckinUser {
  id: number;
  name: string;
  avatar: string;
  days: number;
}

import { onMounted, watch } from 'vue';

const expertsRankings = ref<ExpertUser[]>([]);
const checkinRankings = ref<CheckinUser[]>([]);

const loading = ref(false);
const errorMsg = ref('');

const fetchRankings = async (type: 'experts' | 'checkins') => {
  loading.value = true;
  errorMsg.value = '';
  try {
    const res = await fetch(`https://api.moonbeaut.top/api/rankings.php?type=${type}`);
    const data = await res.json();
    if (data.status === 'success') {
      if (type === 'experts') {
        expertsRankings.value = data.data;
      } else {
        checkinRankings.value = data.data;
      }
    } else {
      errorMsg.value = data.message || '获取数据失败';
    }
  } catch (e) {
    errorMsg.value = '网络错误';
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  fetchRankings('experts');
  fetchRankings('checkins');
});

watch(activeTab, (val) => {
  if (val === 'experts' && expertsRankings.value.length === 0) fetchRankings('experts');
  if (val === 'checkins' && checkinRankings.value.length === 0) fetchRankings('checkins');
});

const currentRankings = computed(() =>
  activeTab.value === 'experts' ? expertsRankings.value : checkinRankings.value
);

</script>

<style scoped>
.rankings-page {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
  background-color: var(--color-background);
}

.rankings-header :deep(.header-left) {
  justify-content: center;
}

.rankings-header :deep(.header-title) {
  text-align: center;
}

.main-content {
  flex: 1;
  padding: var(--space-4) 0 calc(64px + var(--space-4));
}

.container {
  padding: 0 var(--space-4);
}

.toggle-container {
  display: flex;
  background-color: var(--color-secondary);
  border-radius: 100px;
  padding: var(--space-1);
  margin-bottom: var(--space-4);
}

.toggle-button {
  flex: 1;
  padding: var(--space-2) var(--space-4);
  border-radius: 100px;
  font-size: 0.9rem;
  color: var(--color-text-secondary);
  transition: all 0.3s ease;
}

.toggle-button.active {
  background-color: var(--color-card);
  color: var(--color-text-primary);
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.rankings-list {
  background-color: var(--color-card);
  border-radius: 12px;
  padding: var(--space-3);
}

.ranking-item {
  display: flex;
  align-items: center;
  padding: var(--space-3);
  border-bottom: 1px solid var(--color-border);
}

.ranking-item:last-child {
  border-bottom: none;
}

.rank-number {
  width: 24px;
  font-size: 1.1rem;
  font-weight: 600;
  color: var(--color-text-primary);
}

.user-avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  margin: 0 var(--space-3);
}

.user-name {
  flex: 1;
  font-weight: 500;
  color: var(--color-text-primary);
}

.user-stats {
  color: var(--color-text-secondary);
  font-size: 0.9rem;
}

@media (max-width: 640px) {
  .container {
    padding: 0 var(--space-3);
  }
  
  .toggle-button {
    padding: var(--space-2);
  }
  
  .ranking-item {
    padding: var(--space-2);
  }
  
  .user-avatar {
    width: 32px;
    height: 32px;
    margin: 0 var(--space-2);
  }
}
</style>