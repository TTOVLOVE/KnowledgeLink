<template>
  <div class="new-task-page">
    <TheHeader 
      title="新建任务" 
      :showBackButton="true"
    />
    
    <main class="main-content">
      <div class="container">
        <div class="form-group">
          <label>标题</label>
          <input 
            type="text" 
            placeholder="任务名称"
            v-model="taskName"
          >
        </div>

        <div class="form-group">
          <label>类型</label>
          <div class="select-wrapper">
            <select v-model="taskType">
              <option value="">任务类型</option>
              <option value="study">学习</option>
              <option value="work">工作</option>
              <option value="exercise">运动</option>
            </select>
          </div>
        </div>

        <div class="form-group">
          <label>开始时间</label>
          <input 
            type="date" 
            v-model="startDate"
          >
        </div>

        <div class="form-group">
          <label>结束时间</label>
          <input 
            type="date" 
            v-model="endDate"
          >
        </div>

        <button class="create-button" @click="createTask">
          创建
        </button>
      </div>
    </main>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import TheHeader from '../components/TheHeader.vue';

const router = useRouter();
const taskName = ref('');
const taskType = ref('');
const startDate = ref('');
const endDate = ref('');

const createTask = async () => {
  if (!taskName.value.trim()) {
    alert('请输入任务名称');
    return;
  }
  try {
    const res = await fetch('https://api.moonbeaut.top/api/new_task.php', {
      credentials: 'include',
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        name: taskName.value.trim(),
        type: taskType.value,
        start_date: startDate.value,
        end_date: endDate.value,
        // icon 可根据类型自动分配
        icon: getIconByType(taskType.value)
      })
    });
    const data = await res.json();
    if (data.status === 'success') {
      router.back();
    } else {
      alert(data.error || '创建失败');
    }
  } catch {
    alert('创建失败');
  }
};

function getIconByType(type: string) {
  if (type === 'study') return '/images/python.png';
  if (type === 'work') return '/images/默认打卡.png';
  if (type === 'exercise') return '/images/阅读.png';
  return '/images/默认打卡.png';
}
</script>

<style scoped>
.new-task-page {
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

.form-group {
  margin-bottom: var(--space-4);
}

label {
  display: block;
  font-size: 1rem;
  font-weight: 500;
  margin-bottom: var(--space-2);
  color: var(--color-text-primary);
}

input, select {
  width: 100%;
  padding: var(--space-3);
  border: 1px solid var(--color-border);
  border-radius: 8px;
  background-color: var(--color-card);
  font-size: 1rem;
  color: var(--color-text-primary);
}

input::placeholder {
  color: var(--color-text-tertiary);
}

.select-wrapper {
  position: relative;
}

.select-wrapper::after {
  content: '▼';
  position: absolute;
  right: var(--space-3);
  top: 50%;
  transform: translateY(-50%);
  color: var(--color-text-tertiary);
  pointer-events: none;
}

select {
  appearance: none;
  padding-right: var(--space-6);
}

.reward-text {
  text-align: center;
  color: var(--color-text-secondary);
  margin: var(--space-6) 0;
}

.create-button {
  width: 100%;
  padding: var(--space-3);
  background-color: var(--color-primary);
  color: white;
  border-radius: 8px;
  font-size: 1rem;
  font-weight: 500;
}
</style>