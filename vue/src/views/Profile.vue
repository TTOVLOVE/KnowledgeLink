<template>
  <div class="profile-page">
    <TheHeader title="个人中心" />
    
    <main class="main-content">
      <div class="container">
        <div class="user-card" @click="navigateToUserInfo">
          <img :src="user.avatar" :alt="user.nickname || user.name" class="user-avatar">
          <div class="user-info">
            <h2 class="user-name">{{ user.nickname || user.name }}</h2>
            <p class="user-title">{{ user.title }}</p>
          </div>
          <span class="arrow">→</span>
        </div>

        <div class="knowledge-bank">
          <h3 class="section-title">知识银行</h3>
          <div class="bank-stats">
            <div class="stat-item">
              <h4>知识币数量</h4>
              <p class="stat-value">{{ user.coins }}</p>
            </div>
            <div class="stat-item">
              <h4>积分数量</h4>
              <p class="stat-value">{{ user.points }}</p>
            </div>
          </div>
          <div class="bank-actions">
            <button class="bank-button" @click="navigateToExchange">
              <span class="button-icon">↔</span>
              兑换
            </button>
            <button class="bank-button" @click="navigateToDetails">
              <span class="button-icon">📋</span>
              明细
            </button>
          </div>
        </div>

        <div class="collections">
          <div class="section-header">
            <h3 class="section-title">我的收藏</h3>
            <router-link to="/collections" class="view-all">All</router-link>
          </div>
          
          <div class="collection-list">
            <div v-for="item in collections" :key="item.id" class="collection-item">
              <div class="collection-type">{{ item.type }}</div>
              <h4 class="collection-title">{{ item.title }}</h4>
              <p class="collection-date">{{ item.date }}</p>
            </div>
          </div>
        </div>

        <div class="checkin-calendar">
          <h3 class="section-title">打卡日历</h3>
          <p class="checkin-streak">已连续打卡{{ continuousCheckinDays }}天</p>
          <div class="calendar-grid">
            <div class="calendar-row calendar-header">
              <span v-for="day in ['一', '二', '三', '四', '五', '六', '日']" :key="day" class="calendar-cell calendar-header-cell">
                {{ day }}
              </span>
            </div>
            <div class="calendar-row calendar-days">
              <span v-for="cell in calendarCells" :key="cell.key" class="calendar-cell calendar-day" :class="{ checked: cell.checked, empty: cell.empty }">
                {{ cell.display }}
              </span>
            </div>
          </div>
        </div>
      </div>
    </main>

    <button class="fab-button" @click="navigateToPublishArticle">
      <span class="fab-icon">+</span>
    </button>

    <BottomNavigation active="profile" />
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import TheHeader from '../components/TheHeader.vue';
import BottomNavigation from '../components/BottomNavigation.vue';

const router = useRouter();

const user = ref({
  name: '',
  nickname: '',
  title: '',
  avatar: '',
  coins: 0,
  points: 0
});

interface CollectionItem {
  id: number;
  type: string;
  title: string;
  date: string;
}

const collections = ref<CollectionItem[]>([]);

import { computed } from 'vue';

onMounted(async () => {
  try {
    const res = await fetch('https://api.moonbeaut.top/api/profile.php', {
  credentials: 'include'
});
    if (!res.ok) throw new Error('获取个人信息失败');
    const data = await res.json();
    user.value = data.user || user.value;
    collections.value = data.collections || [];
    await fetchCheckinDays();
  } catch (e) {
    // 可选：错误处理
    console.error(e);
  }
});

const checkinDays = ref<string[]>([]);
// 已删除未使用的 storeToRefs 导入
// 已删除未使用的 useUserStore 导入
// 已删除未使用的 userStore 声明
// 已删除未使用的 globalUser 声明
// 已删除未使用的 userId 声明



// 计算连续打卡天数（向前推，直到遇到断档）
const continuousCheckinDays = computed(() => {
  if (checkinDays.value.length === 0) return 0;
  // 按日期降序排列
  const sorted = [...checkinDays.value].sort((a, b) => b.localeCompare(a));
  let count = 1;
  let prev = sorted[0];
  for (let i = 1; i < sorted.length; i++) {
    const prevDate = new Date(prev);
    const currDate = new Date(sorted[i]);
    const diff = (Number(prevDate) - Number(currDate)) / (24*3600*1000);
    if (diff === 1) {
      count++;
      prev = sorted[i];
    } else {
      break;
    }
  }
  return count;
});

const fetchCheckinDays = async () => {
  try {
    const res = await fetch('https://api.moonbeaut.top/api/checkin_day.php', {
      credentials: 'include'
    });
    const data = await res.json();
    if (data.status === 'success') {
      checkinDays.value = data.data;
    }
  } catch {}
};

function formatDate(date: Date) {
  return date.toISOString().slice(0, 10);
}

const calendarCells = computed(() => {
  // 计算本周的周一
  const today = new Date();
  const dayOfWeek = today.getDay() === 0 ? 7 : today.getDay(); // 周日为7
  const monday = new Date(today);
  monday.setDate(today.getDate() - (dayOfWeek - 1));
  // 生成本周7天
  const days = [];
  for (let i = 0; i < 7; i++) {
    const d = new Date(monday);
    d.setDate(monday.getDate() + i);
    const dateStr = formatDate(d);
    days.push({
      key: dateStr,
      display: d.getDate(),
      checked: checkinDays.value.includes(dateStr),
      empty: false
    });
  }
  return days;
});

const navigateToUserInfo = () => {
  router.push('/user-info');
};

const navigateToPublishArticle = () => {
  router.push('/publish-article');
};

const navigateToExchange = () => {
  router.push('/points/exchange');
};

const navigateToDetails = () => {
  router.push('/points/details');
};
</script>

<style scoped>
.profile-page {
  min-height: 100vh;
  background-color: var(--color-background);
  padding-bottom: 64px;
  position: relative;
}

.main-content {
  padding: var(--space-4);
}

.container {
  max-width: 600px;
  margin: 0 auto;
}

.user-card {
  display: flex;
  align-items: center;
  background-color: var(--color-card);
  padding: var(--space-4);
  border-radius: 12px;
  margin-bottom: var(--space-4);
  cursor: pointer;
}

.user-avatar {
  width: 60px;
  height: 60px;
  border-radius: 50%;
  margin-right: var(--space-3);
}

.user-info {
  flex: 1;
}

.user-name {
  font-size: 1.2rem;
  font-weight: 600;
  margin: 0 0 var(--space-1);
}

.user-title {
  font-size: 0.9rem;
  color: var(--color-text-secondary);
  margin: 0;
}

.arrow {
  color: var(--color-text-tertiary);
  font-size: 1.2rem;
}

.knowledge-bank {
  background-color: var(--color-primary);
  color: white;
  border-radius: 12px;
  padding: var(--space-4);
  margin-bottom: var(--space-4);
}

.section-title {
  font-size: 1.1rem;
  font-weight: 600;
  margin: 0 0 var(--space-3);
}

.bank-stats {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: var(--space-3);
  margin-bottom: var(--space-3);
}

.stat-item h4 {
  font-size: 0.9rem;
  font-weight: normal;
  margin: 0 0 var(--space-2);
  opacity: 0.9;
}

.stat-value {
  font-size: 1.8rem;
  font-weight: 600;
  margin: 0;
}

.bank-actions {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: var(--space-3);
}

.bank-button {
  background-color: rgba(255, 255, 255, 0.2);
  color: white;
  border-radius: 8px;
  padding: var(--space-2);
  display: flex;
  align-items: center;
  justify-content: center;
  gap: var(--space-2);
}

.button-icon {
  font-size: 1.2rem;
}

.collections {
  background-color: var(--color-card);
  border-radius: 12px;
  padding: var(--space-4);
  margin-bottom: var(--space-4);
}

.section-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: var(--space-3);
}

.view-all {
  color: var(--color-text-secondary);
  font-size: 0.9rem;
  text-decoration: none;
}

.collection-list {
  display: grid;
  gap: var(--space-3);
}

.collection-item {
  padding: var(--space-3);
  background-color: var(--color-secondary);
  border-radius: 8px;
}

.collection-type {
  font-size: 0.8rem;
  color: var(--color-text-secondary);
  margin-bottom: var(--space-1);
}

.collection-title {
  font-size: 0.95rem;
  font-weight: 500;
  margin: 0 0 var(--space-1);
}

.collection-date {
  font-size: 0.8rem;
  color: var(--color-text-tertiary);
  margin: 0;
}

.checkin-calendar {
  background-color: var(--color-card);
  border-radius: 12px;
  padding: var(--space-4);
}

.checkin-streak {
  color: var(--color-primary);
  font-size: 0.9rem;
  margin: 0 0 var(--space-3);
}

.calendar-grid {
  background-color: var(--color-secondary);
  border-radius: 8px;
  padding: var(--space-3);
}

.calendar-row {
  display: flex;
  flex-direction: row;
  width: 100%;
}

.calendar-header {
  margin-bottom: var(--space-2);
}

.calendar-cell {
  flex: 1 1 0;
  text-align: center;
  box-sizing: border-box;
}

.calendar-header-cell {
  font-size: 0.8rem;
  color: var(--color-text-secondary);
  font-weight: 500;
}

.calendar-days {
  margin: 0;
  padding: 0;
}

.calendar-day {
  width: 36px;
  height: 36px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 8px;
  background: #fff;
  color: #222;
  margin: 2px;
  font-size: 1rem;
  font-weight: 500;
  border: 1px solid #e0e0e0;
  transition: background 0.2s, color 0.2s;
}

.calendar-day.checked {
  background: var(--color-success);
  color: #fff;
  border: 1px solid var(--color-success);
}

.calendar-day.empty {
  background: transparent;
  border: none;
  color: transparent;
  pointer-events: none;
}

.fab-button {
  position: fixed;
  right: var(--space-4);
  bottom: calc(64px + var(--space-4));
  width: 48px;
  height: 48px;
  border-radius: 50%;
  background-color: var(--color-primary);
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  z-index: 100;
}

.fab-icon {
  font-size: 1.5rem;
  font-weight: bold;
}

@media (max-width: 640px) {
  .container {
    padding: 0 var(--space-3);
  }
  
  .user-card {
    padding: var(--space-3);
  }
  
  .user-avatar {
    width: 48px;
    height: 48px;
  }
  
  .user-name {
    font-size: 1.1rem;
  }
  
  .stat-value {
    font-size: 1.5rem;
  }
}
</style>