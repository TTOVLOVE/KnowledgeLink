<template>
  <div class="login-register-page">
    <div class="form-card">
      <h2>{{ isLoginMode ? '登录' : '注册' }}</h2>
      <form @submit.prevent="handleSubmit">
        <div class="form-group">
          <label>用户名</label>
          <input v-model="form.name" placeholder="请输入用户名" required />
        </div>
        <div class="form-group">
          <label>密码</label>
          <input v-model="form.password" type="password" placeholder="请输入密码" required />
        </div>
        <div class="form-group" v-if="!isLoginMode">
          <label>昵称</label>
          <input v-model="form.nickname" placeholder="请输入昵称" required />
        </div>
        <div class="form-actions">
          <button type="submit">{{ isLoginMode ? '登录' : '注册' }}</button>
          <button type="button" class="switch-btn" @click="switchMode">
            {{ isLoginMode ? '没有账号？注册' : '已有账号？登录' }}
          </button>
        </div>
        <div class="form-error" v-if="error">{{ error }}</div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';

const isLoginMode = ref(true);
const form = ref({ name: '', password: '', nickname: '' });
const error = ref('');
const router = useRouter();

const switchMode = () => {
  isLoginMode.value = !isLoginMode.value;
  error.value = '';
};

const handleSubmit = async () => {
  error.value = '';
  const url = isLoginMode.value
    ? 'https://api.moonbeaut.top/api/login.php'
    : 'https://api.moonbeaut.top/api/register.php';
  const payload = isLoginMode.value
    ? { name: form.value.name, password: form.value.password }
    : { name: form.value.name, password: form.value.password, nickname: form.value.nickname };
  try {
    const res = await fetch(url, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      credentials: 'include',
      body: JSON.stringify(payload),
    });
    const data = await res.json();
    if (data.status === 'success') {
      if (isLoginMode.value) {
        // 登录成功，跳转个人中心
        router.push('/profile');
      } else {
        // 注册成功，跳转登录页并切换到登录模式
        isLoginMode.value = true;
        error.value = '';
        form.value.password = '';
        form.value.nickname = '';
        // 可用提示
        error.value = '注册成功，请登录';
      }
    } else {
      error.value = data.message || '操作失败';
    }
  } catch (e) {
    error.value = '网络错误';
  }
};
</script>

<style scoped>
.login-register-page {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #f5f6fa;
}
.form-card {
  background: #fff;
  border-radius: 8px;
  box-shadow: 0 2px 12px rgba(0,0,0,0.08);
  padding: 32px 28px;
  width: 320px;
}
.form-card h2 {
  margin-bottom: 24px;
  text-align: center;
  color: #333;
}
.form-group {
  margin-bottom: 18px;
}
.form-group label {
  display: block;
  margin-bottom: 6px;
  color: #666;
}
.form-group input {
  width: 100%;
  padding: 8px 10px;
  border: 1px solid #e0e0e0;
  border-radius: 4px;
  font-size: 16px;
}
.form-actions {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: 10px;
}
.form-actions button {
  padding: 8px 18px;
  border: none;
  border-radius: 4px;
  background: #409eff;
  color: #fff;
  font-size: 16px;
  cursor: pointer;
  transition: background 0.2s;
}
.form-actions .switch-btn {
  background: transparent;
  color: #409eff;
  font-size: 14px;
  text-decoration: underline;
  padding: 0;
}
.form-error {
  color: #e74c3c;
  margin-top: 12px;
  text-align: center;
  font-size: 15px;
}
</style>
