<template>
  <div class="points-exchange">
    <TheHeader 
      title="兑换" 
      :showBackButton="true"
    />
    
    <main class="main-content">
      <div class="container">
        <div class="points-cards">
          <div class="points-card">
            <div class="card-label">可用积分</div>
            <div class="card-value">{{ availablePoints }}</div>
          </div>
          <div class="points-card">
            <div class="card-label">可兑换知识币</div>
            <div class="card-value">{{ exchangeableCoins }}</div>
          </div>
        </div>

        <div class="exchange-section">
          <input 
            type="number" 
            v-model="exchangeAmount"
            class="exchange-input"
            placeholder="请输入兑换积分"
          >
          <button 
            class="exchange-button"
            :disabled="!canExchange"
            @click="handleExchange"
          >
            兑换
          </button>
        </div>

        <div class="exchange-rate">
          当前可兑换：{{ exchangeRate }}
        </div>

        <div class="exchange-history">
          <h3 class="section-title">兑换记录</h3>
          <div class="history-label">近期兑换</div>
          
          <div class="history-list">
            <div v-for="record in exchangeHistory" :key="record.id" class="history-item">
              <div class="history-info">
                <div class="history-title">兑换{{ record.coins }}知识币</div>
                <div class="history-time">{{ record.time }}</div>
              </div>
              <div class="history-points">-{{ record.points }} 积分</div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import TheHeader from '../components/TheHeader.vue';

const availablePoints = ref(0);
const exchangeableCoins = ref(0);
const exchangeAmount = ref('');
const exchangeRate = '10积分=1知识币';

interface ExchangeRecord {
  id: number;
  coins: number;
  points: number;
  time: string;
}

const exchangeHistory = ref<ExchangeRecord[]>([]);

const canExchange = computed(() => {
  const amount = Number(exchangeAmount.value);
  return amount > 0 && amount <= availablePoints.value && amount % 10 === 0;
});

const fetchUserPoints = async () => {
  // 假设profile接口返回用户积分和知识币
  const res = await fetch('https://api.moonbeaut.top/api/profile.php', {
  credentials: 'include'
});
  const data = await res.json();
  availablePoints.value = data.user?.points || 0;
  exchangeableCoins.value = data.user?.coins || 0;
};

const fetchExchangeHistory = async () => {
  const res = await fetch('https://api.moonbeaut.top/api/points_exchange.php', { credentials: 'include' });
  const data = await res.json();
  exchangeHistory.value = data.exchanges || [];
};

const handleExchange = async () => {
  const points = Number(exchangeAmount.value);
  if (!canExchange.value) return;
  // 这里假设50积分=5知识币，比例可根据实际调整
  const coins = Math.floor(points / 10);
  try {
    const res = await fetch('https://api.moonbeaut.top/api/points_exchange.php', {
      method: 'POST',
      credentials: 'include',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ coins, points })
    });
    const data = await res.json();
    if (data.success) {
      await fetchUserPoints();
      await fetchExchangeHistory();
      exchangeAmount.value = '';
      alert('兑换成功！');
    } else {
      alert(data.error || '兑换失败');
    }
  } catch (e) {
    alert('兑换失败');
  }
};

onMounted(async () => {
  await fetchUserPoints();
  await fetchExchangeHistory();
});
</script>

<style scoped>
.points-exchange {
  min-height: 100vh;
  background-color: var(--color-background);
}

.main-content {
  padding: var(--space-4);
}

.container {
  max-width: 600px;
  margin: 0 auto;
}

.points-cards {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: var(--space-4);
  margin-bottom: var(--space-6);
}

.points-card {
  background-color: var(--color-card);
  padding: var(--space-4);
  border-radius: 12px;
  text-align: center;
}

.card-label {
  font-size: 0.9rem;
  color: var(--color-text-secondary);
  margin-bottom: var(--space-2);
}

.card-value {
  font-size: 1.5rem;
  font-weight: 600;
  color: var(--color-text-primary);
}

.exchange-section {
  display: flex;
  gap: var(--space-3);
  margin-bottom: var(--space-3);
}

.exchange-input {
  flex: 1;
  padding: var(--space-3);
  border: 1px solid var(--color-border);
  border-radius: 8px;
  font-size: 1rem;
  background-color: var(--color-card);
}

.exchange-button {
  padding: var(--space-3) var(--space-5);
  background-color: var(--color-primary);
  color: white;
  border-radius: 8px;
  font-size: 1rem;
  font-weight: 500;
}

.exchange-button:disabled {
  background-color: var(--color-text-tertiary);
  cursor: not-allowed;
}

.exchange-rate {
  font-size: 0.9rem;
  color: var(--color-text-secondary);
  margin-bottom: var(--space-6);
}

.exchange-history {
  background-color: var(--color-card);
  border-radius: 12px;
  padding: var(--space-4);
}

.section-title {
  font-size: 1.1rem;
  font-weight: 600;
  margin: 0 0 var(--space-3);
}

.history-label {
  font-size: 0.9rem;
  color: var(--color-text-secondary);
  margin-bottom: var(--space-3);
}

.history-list {
  display: flex;
  flex-direction: column;
  gap: var(--space-3);
}

.history-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: var(--space-3);
  background-color: var(--color-secondary);
  border-radius: 8px;
}

.history-title {
  font-size: 0.95rem;
  font-weight: 500;
  margin-bottom: var(--space-1);
}

.history-time {
  font-size: 0.8rem;
  color: var(--color-text-tertiary);
}

.history-points {
  color: var(--color-error);
  font-weight: 500;
}
</style>