<template>
  <div class="tasks-page">
    <TheHeader title="所有任务" :showBackButton="true" />
    <main class="main-content">
      <div class="tasks-list">
        <div v-for="task in tasks" :key="task.id" class="task-item">
          <img :src="task.icon || getDefaultIcon(task.id)" :alt="task.name" class="task-icon">
          <span class="task-name">{{ task.name }}</span>
          <button class="delete-btn" @click="deleteTask(task.id)">删除</button>
        </div>
      </div>
      <p v-if="tasks.length === 0" class="empty-text">暂无任务</p>
    </main>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import TheHeader from '../components/TheHeader.vue';
import { storeToRefs } from 'pinia';
import { useUserStore } from '../store/user';
const userStore = useUserStore();
const { user } = storeToRefs(userStore);
const userId = computed(() => user.value?.id);

const tasks = ref<{id:number;name:string;icon?:string}[]>([]);

const fetchTasks = async () => {
  try {
    const res = await fetch(`https://api.moonbeaut.top/api/tasks.php?user_id=${userId}`);
    const data = await res.json();
    if (data.status === 'success') {
      tasks.value = data.data;
    }
  } catch {}
};

const deleteTask = async (taskId:number) => {
  if (!confirm('确定要删除该任务吗？')) return;
  try {
    const res = await fetch('https://api.moonbeaut.top/api/tasks.php', {
      method: 'DELETE',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ user_id: userId, task_id: taskId })
    });
    const data = await res.json();
    if (data.status === 'success') {
      tasks.value = tasks.value.filter(t => t.id !== taskId);
    } else {
      alert(data.error || '删除失败');
    }
  } catch {
    alert('删除失败');
  }
};

function getDefaultIcon(id:number) {
  const icons: Record<number, string> = {
    1: '/src/static/images/默认打卡.png',
    2: '/src/static/images/阅读.png',
    3: '/src/static/images/python.png',
    4: '/src/static/images/靶场搭建.png'
  };
  return icons[id] || '/src/static/images/默认打卡.png';
}

onMounted(fetchTasks);
</script>

<style scoped>
.tasks-page {
  min-height: 100vh;
  background: var(--color-background);
}
.main-content {
  padding: var(--space-4);
}
.tasks-list {
  max-width: 600px;
  margin: 0 auto;
}
.task-item {
  display: flex;
  align-items: center;
  background: var(--color-card);
  border-radius: 12px;
  margin-bottom: var(--space-4);
  padding: var(--space-3);
  gap: var(--space-3);
}
.task-icon {
  width: 48px;
  height: 48px;
  border-radius: 8px;
}
.task-name {
  flex: 1;
  font-size: 1.1rem;
  font-weight: 500;
}
.delete-btn {
  background: var(--color-danger);
  color: #fff;
  border: none;
  padding: 8px 16px;
  border-radius: 6px;
  cursor: pointer;
  font-size: 1rem;
}
.delete-btn:hover {
  background: #d32f2f;
}
.empty-text {
  text-align: center;
  color: #888;
  margin-top: 40px;
}
</style>
