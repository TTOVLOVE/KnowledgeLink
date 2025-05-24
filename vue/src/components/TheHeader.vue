<template>
  <header class="header">
    <div class="container header-container">
      <div class="header-left">
        <button @click="handleBack" class="back-button" v-if="showBackButton">
          <span class="back-icon">←</span>
        </button>
        <h1 class="header-title" v-if="title">{{ title }}</h1>
        <div class="breadcrumb" v-if="breadcrumb">
          <router-link to="/" class="breadcrumb-home">{{ breadcrumb.home }}</router-link>
          <span class="breadcrumb-separator">></span>
          <span class="breadcrumb-current">{{ breadcrumb.current }}</span>
        </div>
      </div>
      <div class="search-container" v-if="showSearch">
        <div class="search-box">
          <input type="text" class="search-input" v-model="searchKeyword" placeholder="搜索..." @keyup.enter="onSearch" />
          <button class="search-button" @click="onSearch">
            <span class="search-icon">🔍</span>
          </button>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { useRouter } from 'vue-router';

const emit = defineEmits(['search']);
const searchKeyword = ref('');

const onSearch = () => {
  emit('search', searchKeyword.value.trim());
};

defineProps<{
  title?: string;
  showBackButton?: boolean;
  showSearch?: boolean;
  breadcrumb?: {
    home: string;
    current: string;
  };
}>();

const router = useRouter();

const handleBack = () => {
  router.back();
};
</script>

<style scoped>
.header {
  padding: var(--space-3) 0;
  background-color: var(--color-secondary);
  border-radius: 0 0 var(--space-4) var(--space-4);
  position: sticky;
  top: 0;
  z-index: 100;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.header-container {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.header-left {
  display: flex;
  align-items: center;
  gap: var(--space-2);
}

.header-title {
  font-size: 1.5rem;
  font-weight: 600;
  margin: 0;
  color: var(--color-text-primary);
}

.back-button {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 36px;
  height: 36px;
  background: var(--color-card);
  border-radius: 50%;
  color: var(--color-text-primary);
  text-decoration: none;
  transition: background-color 0.2s ease, transform 0.2s ease;
  border: none;
  cursor: pointer;
}

.back-button:hover {
  background-color: var(--color-secondary-dark);
  transform: translateX(-2px);
}

.back-icon {
  font-size: 1.2rem;
  line-height: 1;
}

.breadcrumb {
  display: flex;
  align-items: center;
  gap: var(--space-2);
  font-size: 0.9rem;
  color: var(--color-text-secondary);
}

.breadcrumb-home {
  color: var(--color-text-secondary);
  text-decoration: none;
}

.breadcrumb-home:hover {
  color: var(--color-primary);
}

.breadcrumb-separator {
  color: var(--color-text-tertiary);
}

.breadcrumb-current {
  color: var(--color-text-primary);
  font-weight: 500;
}

.search-container {
  flex: 1;
  max-width: 400px;
  margin-left: var(--space-4);
}

.search-box {
  display: flex;
  position: relative;
  width: 100%;
}

.search-input {
  width: 100%;
  padding: var(--space-2) var(--space-5) var(--space-2) var(--space-3);
  border: none;
  border-radius: 20px;
  background-color: var(--color-card);
  font-size: 0.9rem;
  transition: box-shadow 0.2s ease;
}

.search-input:focus {
  outline: none;
  box-shadow: 0 0 0 2px var(--color-primary-light);
}

.search-button {
  position: absolute;
  right: var(--space-2);
  top: 50%;
  transform: translateY(-50%);
  background: none;
  border: none;
  color: var(--color-text-tertiary);
  cursor: pointer;
  padding: var(--space-1);
}

.search-button:hover {
  color: var(--color-primary);
}

@media (max-width: 768px) {
  .header-title {
    font-size: 1.2rem;
  }
  
  .search-container {
    max-width: 200px;
  }
}
</style>