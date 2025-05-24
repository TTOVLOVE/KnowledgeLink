<template>
  <nav class="bottom-nav">
    <router-link to="/" class="nav-item" :class="{ active: active === 'home' }">
      <span class="nav-icon">🏠</span>
      <span class="nav-text">首页</span>
    </router-link>
    <router-link to="/questions" class="nav-item" :class="{ active: active === 'questions' }">
      <span class="nav-icon">❓</span>
      <span class="nav-text">问答</span>
    </router-link>
    <router-link to="/checkin" class="nav-item" :class="{ active: active === 'checkin' }">
      <span class="nav-icon">✅</span>
      <span class="nav-text">打卡</span>
    </router-link>
    <router-link to="/rankings" class="nav-item" :class="{ active: active === 'rankings' }">
      <span class="nav-icon">📊</span>
      <span class="nav-text">排行</span>
    </router-link>
    <router-link to="/profile" class="nav-item" :class="{ active: active === 'profile' }">
      <span class="nav-icon">👤</span>
      <span class="nav-text">我的</span>
    </router-link>
  </nav>
</template>

<script setup lang="ts">
defineProps<{
  active: 'home' | 'questions' | 'checkin' | 'rankings' | 'profile';
}>();
</script>

<style scoped>
.bottom-nav {
  position: fixed;
  bottom: 0;
  left: 0;
  right: 0;
  display: flex;
  justify-content: space-around;
  align-items: center;
  height: 64px;
  background-color: var(--color-card);
  box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.05);
  z-index: 100;
}

.nav-item {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  flex: 1;
  height: 100%;
  color: var(--color-text-tertiary);
  text-decoration: none;
  transition: color 0.2s ease;
  padding: var(--space-2) 0;
}

.nav-item:hover {
  color: var(--color-primary-light);
}

.nav-item.active {
  color: var(--color-primary);
}

.nav-icon {
  font-size: 1.4rem;
  margin-bottom: var(--space-1);
}

.nav-text {
  font-size: 0.75rem;
  font-weight: 500;
}
</style>