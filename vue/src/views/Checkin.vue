<template>
  <div class="checkin-page">
    <div class="greeting-section">
      <div class="greeting-header">
        <h1 class="greeting">Hi,<br/>{{ user?.name || '新手小白' }}<br/>{{ greetingMessage }}</h1>
        <img src="../static/images/xiaoren.png" alt="Avatar" class="avatar">
      </div>
      
      <div class="progress-card">
        <div class="progress-ring">
          <div class="progress-circle" :style="progressCircleStyle">
            <span class="progress-text">{{ progressPercent }}%</span>
          </div>
        </div>
        <div class="progress-message">
          WOW！<br/>
          今天的目标快达成了！
        </div>
      </div>
    </div>

    <div class="tasks-section">
      <div class="section-header">
        <h2>今日任务</h2>
        <router-link to="/tasks" class="view-all">查看所有</router-link>
      </div>

      <div class="tasks-list">
        <div v-for="task in tasks" :key="task.id" class="task-item">
          <div class="task-info">
            <img :src="getTaskIcon(task)" :alt="task.name" class="task-icon">
            <span class="task-name">{{ task.name }}</span>
          </div>
          <button class="checkin-button" :class="{ checked: task.checked }" @click="!task.checked && checkin(task.id)" :disabled="task.checked">
            {{ task.checked ? '已打卡' : '打卡' }}
          </button>
        </div>
      </div>

      <button class="new-task-button" @click="navigateToNewTask">
        <span class="plus-icon">+</span>
        <span>新建任务</span>
      </button>
    </div>

    <BottomNavigation active="checkin" />
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import BottomNavigation from '../components/BottomNavigation.vue';
import { useUserStore } from '../store/user';
import { storeToRefs } from 'pinia';
import { watch } from 'vue';

const userStore = useUserStore();
const { user } = storeToRefs(userStore);

const router = useRouter();

const tasks = ref<{id:number;name:string;icon?:string;checked:boolean}[]>([]);

const fetchTasks = async () => {
  try {
    const res = await fetch('https://api.moonbeaut.top/api/checkin.php', {
      credentials: 'include'
    });
    const data = await res.json();
    if (data.status === 'success') {
      tasks.value = data.data;
    }
  } catch {}
};

const checkin = async (taskId: number) => {
  try {
    const res = await fetch('https://api.moonbeaut.top/api/checkin.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      credentials: 'include',
      body: JSON.stringify({ task_id: taskId })
    });
    const data = await res.json();
    if (data.status === 'success') {
      fetchTasks();
    } else {
      alert(data.error || '打卡失败');
    }
  } catch {
    alert('打卡失败');
  }
};

// 根据当前时间段生成问候语
const greetingMessage = computed(() => {
  const hour = new Date().getHours();
  if (hour >= 5 && hour < 12) return '早上好啊！';
  if (hour >= 12 && hour < 18) return '下午好啊！';
  if (hour >= 18 && hour < 23) return '晚上好啊！';
  return '夜深了，注意休息哦！';
});



const progressPercent = computed(() => {
  if (!tasks.value.length) return 0;
  const checked = tasks.value.filter(t => t.checked).length;
  return Math.round((checked / tasks.value.length) * 100);
});

onMounted(fetchTasks);

let checkinDaySubmitted = false;
watch(progressPercent, async (val: number) => {
  if (val === 100 && !checkinDaySubmitted) {
    checkinDaySubmitted = true;
    try {
      await fetch('https://api.moonbeaut.top/api/checkin_day.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        credentials: 'include',
        body: JSON.stringify({})
      });
    } catch {}
  }
  if (val < 100) {
    checkinDaySubmitted = false;
  }
});


const progressCircleStyle = computed(() => {
  // 80% -> 'conic-gradient(var(--color-primary) 80%, #e0e0e0 0)'
  return {
    background: `conic-gradient(var(--color-primary) ${progressPercent.value}%, #e0e0e0 0)`
  };
});

const getTaskIcon = (task: {id: number; icon?: string}) => {
  // 优先用数据库返回的 icon 字段
  if (task.icon) return task.icon;
  // 兼容历史数据
  const icons: Record<number, string> = {
    1: '/images/默认打卡.png',
    2: '/images/阅读.png',
    3: '/images/python.png',
    4: '/images/靶场搭建.png'
  };
  return icons[task.id] || '/images/默认打卡.png';
};

const navigateToNewTask = () => {
  router.push('/new-task');
};
</script>

<style scoped>
.checkin-page {
  min-height: 100vh;
  background-color: var(--color-background);
  padding-bottom: 64px;
}

.greeting-section {
  background-color: var(--color-card);
  padding: var(--space-4);
  border-radius: 0 0 20px 20px;
}

.greeting-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: var(--space-4);
}

.greeting {
  font-size: 1.5rem;
  font-weight: 600;
  line-height: 1.4;
  margin: 0;
}

.avatar {
  width: 80px;
  height: 80px;
  border-radius: 16px;
}

.progress-card {
  background-color: var(--color-secondary);
  border-radius: 12px;
  padding: var(--space-4);
  display: flex;
  align-items: center;
  gap: var(--space-4);
}

.progress-ring {
  width: 80px;
  height: 80px;
  position: relative;
}

.progress-circle {
  width: 100%;
  height: 100%;
  border-radius: 50%;
  background: conic-gradient(var(--color-primary) 80%, #e0e0e0 0);
  display: flex;
  align-items: center;
  justify-content: center;
}

.progress-circle::before {
  content: '';
  position: absolute;
  width: 90%;
  height: 90%;
  background: var(--color-secondary);
  border-radius: 50%;
  top: 5%;
  left: 5%;
}

.progress-text {
  position: relative;
  z-index: 1;
  font-weight: 600;
  color: var(--color-primary);
}

.progress-message {
  font-size: 1.1rem;
  font-weight: 500;
  line-height: 1.4;
}

.tasks-section {
  padding: var(--space-4);
}

.section-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: var(--space-4);
}

.section-header h2 {
  font-size: 1.2rem;
  margin: 0;
}

.view-all {
  color: var(--color-text-secondary);
  font-size: 0.9rem;
  text-decoration: none;
}

.tasks-list {
  display: flex;
  flex-direction: column;
  gap: var(--space-3);
  margin-bottom: var(--space-4);
}

.task-item {
  background-color: var(--color-secondary);
  border-radius: 12px;
  padding: var(--space-3);
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.task-info {
  display: flex;
  align-items: center;
  gap: var(--space-2);
}

.task-icon {
  width: 24px;
  height: 24px;
  object-fit: contain;
}

.task-name {
  font-weight: 500;
}

.checkin-button {
  background-color: var(--color-primary);
  color: white;
  padding: var(--space-1) var(--space-3);
  border-radius: 16px;
  font-size: 0.9rem;
}

.checkin-button.checked {
  background-color: var(--color-text-tertiary);
}

.new-task-button {
  width: 100%;
  background-color: white;
  border: 2px solid var(--color-border);
  border-radius: 12px;
  padding: var(--space-3);
  display: flex;
  align-items: center;
  justify-content: center;
  gap: var(--space-2);
  color: var(--color-text-secondary);
  font-weight: 500;
}

.plus-icon {
  font-size: 1.2rem;
  font-weight: 600;
}
</style>