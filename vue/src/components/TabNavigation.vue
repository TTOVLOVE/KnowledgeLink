<template>
  <div class="tabs-container">
    <div class="tabs">
      <button 
        v-for="tab in tabs" 
        :key="tab.id"
        class="tab-button"
        :class="{ active: activeTab === tab.id }"
        @click="$emit('change', tab.id)"
      >
        {{ tab.label }}
      </button>
    </div>
    <div class="tab-indicator" :style="indicatorStyle"></div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';

interface Tab {
  id: string;
  label: string;
}

const props = defineProps<{
  tabs: Tab[];
  activeTab: string;
}>();

defineEmits<{
  (e: 'change', tabId: string): void;
}>();

const indicatorStyle = computed(() => {
  const activeIndex = props.tabs.findIndex(tab => tab.id === props.activeTab);
  const width = 100 / props.tabs.length;
  
  return {
    width: `${width}%`,
    left: `${activeIndex * width}%`
  };
});
</script>

<style scoped>
.tabs-container {
  position: relative;
  width: 100%;
  border-bottom: 1px solid var(--color-border);
  margin-bottom: var(--space-4);
}

.tabs {
  display: flex;
  width: 100%;
}

.tab-button {
  flex: 1;
  padding: var(--space-3) var(--space-2);
  background: none;
  border: none;
  font-size: 1rem;
  font-weight: 500;
  color: var(--color-text-secondary);
  cursor: pointer;
  transition: color 0.2s ease;
  position: relative;
  z-index: 1;
}

.tab-button:hover {
  color: var(--color-text-primary);
}

.tab-button.active {
  color: var(--color-primary);
  font-weight: 600;
}

.tab-indicator {
  position: absolute;
  bottom: 0;
  height: 2px;
  background-color: var(--color-primary);
  transition: all 0.3s ease;
}
</style>