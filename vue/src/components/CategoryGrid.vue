<template>
  <div class="category-grid">
    <div class="grid">
      <div 
        v-for="category in categories" 
        :key="category.id" 
        class="category-item"
        @click="handleCategoryClick(category)"
      >
        <div class="icon">
          <img :src="`/images/icon/${category.icon}`" :alt="category.name" />
        </div>
        <div class="name">{{ category.name }}</div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { defineProps } from 'vue';
import { useRouter } from 'vue-router';

interface Category {
  id: number;
  name: string;
  icon: string;
}

defineProps<{ categories: Category[] }>();

const router = useRouter();

const handleCategoryClick = (category: Category) => {
  // 跳转到/category/分类英文名
  router.push(`/category/${category.name.toLowerCase()}`);
};
</script>

<style scoped>
.category-grid {
  padding: var(--space-4) var(--space-3);
  display: flex;
  justify-content: center;
}

.grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: var(--space-3);
  justify-items: center;
}

.category-item {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: var(--space-3);
  background-color: var(--color-bg-secondary);
  border-radius: var(--radius-md);
  cursor: pointer;
  transition: transform 0.2s ease;
}

.category-item:hover {
  transform: translateY(-2px);
}

.icon {
  width: 48px;
  height: 48px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 8px;
  background: transparent !important;
}

.icon img {
  width: 100%;
  height: 100%;
  background: transparent !important;
  font-size: 1.2rem;
}

.name {
  font-size: 0.9rem;
  color: var(--color-text-primary);
  text-align: center;
}

@media (min-width: 768px) {
  .grid {
    grid-template-columns: repeat(6, 1fr);
  }
}
</style>