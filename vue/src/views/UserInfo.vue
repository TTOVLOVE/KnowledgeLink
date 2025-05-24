<template>
  <div class="user-info-page">
    <TheHeader 
      title="个人信息" 
      :showBackButton="true"
    />
    
    <main class="main-content">
      <div class="container">
        <div class="avatar-section">
          <img :src="user.avatar" :alt="user.nickname" class="user-avatar">
          <template v-if="editMode">
            <input type="file" accept="image/*" @change="onAvatarChange" />
            <span v-if="avatarUploading" style="color:gray">头像上传中...</span>
            <span v-if="avatarUploadError" style="color:red">{{ avatarUploadError }}</span>
          </template>
        </div>

        <div class="info-list">
          <div class="info-item">
            <label>昵称:</label>
            <div class="info-value">
              <template v-if="editMode">
                <input v-model="user.nickname" type="text" class="edit-input" />
              </template>
              <template v-else>
                {{ user.nickname }}
              </template>
            </div>
          </div>

          <div class="info-item">
            <label>性别:</label>
            <div class="gender-selector">
              <button 
                class="gender-button"
                :class="{ active: user.gender === 'male' }"
                @click="editMode && (user.gender = 'male')"
                :disabled="!editMode"
              >
                男
              </button>
              <button 
                class="gender-button"
                :class="{ active: user.gender === 'female' }"
                @click="editMode && (user.gender = 'female')"
                :disabled="!editMode"
              >
                女
              </button>
            </div>
          </div>

          <div class="info-item">
            <label>个性签名:</label>
            <div class="info-value">
              <template v-if="editMode">
                <input v-model="user.signature" type="text" class="edit-input" />
              </template>
              <template v-else>
                {{ user.signature }}
              </template>
            </div>
          </div>

          <div class="info-item">
            <label>生日:</label>
            <div class="info-value">
              <template v-if="editMode">
                <input v-model="user.birthday" type="date" class="edit-input" />
              </template>
              <template v-else>
                {{ user.birthday }}
              </template>
            </div>
          </div>

          <div class="info-item">
            <label>手机号:</label>
            <div class="info-value">
              <template v-if="editMode">
                <input v-model="user.phone" type="text" class="edit-input" />
              </template>
              <template v-else>
                {{ user.phone }}
              </template>
            </div>
          </div>

          <div class="info-item">
            <label>微信号:</label>
            <div class="info-value">
              <template v-if="editMode">
                <input v-model="user.wechat" type="text" class="edit-input" />
              </template>
              <template v-else>
                {{ user.wechat }}
              </template>
            </div>
          </div>

          <div class="info-item">
            <label>QQ号:</label>
            <div class="info-value">
              <template v-if="editMode">
                <input v-model="user.qq" type="text" class="edit-input" />
              </template>
              <template v-else>
                {{ user.qq }}
              </template>
            </div>
          </div>
        </div>

        <div class="action-buttons">
          <button class="action-button" v-if="!editMode" @click="editMode = true">修改信息</button>
          <button class="action-button" v-else @click="saveUserInfo">保存</button>
          <span v-if="successMsg" style="color:green">{{ successMsg }}</span>
          <span v-if="errorMsg" style="color:red">{{ errorMsg }}</span>
          <button class="action-button logout" @click="handleLogout">退出登录</button>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import TheHeader from '../components/TheHeader.vue';

const avatarUploading = ref(false);
const avatarUploadError = ref('');

// 头像上传逻辑（本地接口）
const onAvatarChange = async (e: Event) => {
  const file = (e.target as HTMLInputElement).files?.[0];
  if (!file) return;
  avatarUploading.value = true;
  avatarUploadError.value = '';
  const formData = new FormData();
  formData.append('avatar', file);
  try {
    const res = await fetch('https://api.moonbeaut.top/api/upload_avatar.php', {
      method: 'POST',
      body: formData
    });
    const data = await res.json();
    if (data.status === 'success') {
      user.value.avatar = data.url;
    } else {
      avatarUploadError.value = data.message || '上传失败';
    }
  } catch (err) {
    avatarUploadError.value = '上传出错';
  } finally {
    avatarUploading.value = false;
  }
};

const user = ref({
  nickname: '',
  avatar: '',
  gender: 'unknown',
  signature: '',
  birthday: '',
  phone: '',
  wechat: '',
  qq: ''
});
const loading = ref(false);
const successMsg = ref('');
const errorMsg = ref('');
const editMode = ref(false);

// 获取用户信息
const fetchUserInfo = async () => {
  loading.value = true;
  try {
    const res = await fetch('https://api.moonbeaut.top/api/user_info.php', {
      credentials: 'include'
    });
    const data = await res.json();
    if (data.status === 'success') {
      Object.assign(user.value, data.data);
    } else {
      errorMsg.value = data.message || '获取用户信息失败';
    }
  } catch (e) {
    errorMsg.value = '网络错误';
  } finally {
    loading.value = false;
  }
};

// 保存用户信息
const saveUserInfo = async () => {
  if (!validateFields()) return;
  loading.value = true;
  try {
    const res = await fetch('https://api.moonbeaut.top/api/user_info.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      credentials: 'include',
      body: JSON.stringify(user.value)
    });
    const data = await res.json();
    if (data.status === 'success') {
      successMsg.value = '保存成功';
      editMode.value = false;
    } else {
      errorMsg.value = data.message || '保存失败';
    }
  } catch (e) {
    errorMsg.value = '网络错误';
  } finally {
    loading.value = false;
  }
};
// 字段校验
function validateFields() {
  if (!user.value.nickname || user.value.nickname.trim() === '') {
    errorMsg.value = '昵称不能为空';
    return false;
  }
  if (user.value.phone && !/^1[3-9]\d{9}$/.test(user.value.phone)) {
    errorMsg.value = '手机号格式不正确';
    return false;
  }
  if (user.value.birthday) {
    const today = new Date();
    const birth = new Date(user.value.birthday);
    if (birth > today) {
      errorMsg.value = '生日不能晚于今天';
      return false;
    }
  }
  return true;
}

// 已删除未使用的 onSave 声明

fetchUserInfo();
import { useRouter } from 'vue-router';
const router = useRouter();

const handleLogout = async () => {
  try {
    await fetch('https://api.moonbeaut.top/api/logout.php', {
      method: 'POST',
      credentials: 'include',
    });
    // 清空前端用户信息
    user.value = {
      nickname: '', gender: '', signature: '', birthday: '', phone: '', wechat: '', qq: '', avatar: ''
    };
    // 跳转到首页或登录页
    router.push('/login');
  } catch (e) {
    errorMsg.value = '退出登录失败';
  }
};
</script>

<style scoped>
.user-info-page {
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

.avatar-section {
  display: flex;
  justify-content: center;
  margin-bottom: var(--space-6);
}

.user-avatar {
  width: 100px;
  height: 100px;
  border-radius: 50%;
  object-fit: cover;
}

.info-list {
  background-color: var(--color-card);
  border-radius: 12px;
  overflow: hidden;
  margin-bottom: var(--space-6);
}

.info-item {
  display: flex;
  padding: var(--space-4);
  border-bottom: 1px solid var(--color-border);
}

.info-item:last-child {
  border-bottom: none;
}

label {
  width: 80px;
  color: var(--color-text-secondary);
  font-size: 0.95rem;
}

.info-value {
  flex: 1;
  color: var(--color-text-primary);
  font-size: 0.95rem;
}

.gender-selector {
  display: flex;
  gap: var(--space-3);
}

.gender-button {
  padding: var(--space-1) var(--space-3);
  border-radius: 16px;
  background-color: var(--color-secondary);
  color: var(--color-text-secondary);
  font-size: 0.9rem;
}

.gender-button.active {
  background-color: var(--color-primary);
  color: white;
}

.action-buttons {
  display: flex;
  flex-direction: column;
  gap: var(--space-3);
}

.action-button {
  width: 100%;
  padding: var(--space-3);
  border-radius: 8px;
  background-color: var(--color-secondary);
  color: var(--color-text-primary);
  font-size: 1rem;
  font-weight: 500;
}

.action-button.logout {
  background-color: var(--color-error);
  color: white;
}
</style>