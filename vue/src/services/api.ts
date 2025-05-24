import axios from 'axios';

// 创建 axios 实例
const api = axios.create({
  baseURL: '/api', // 基础 URL，将通过 vite 代理转发到后端
  timeout: 10000, // 请求超时时间
  headers: {
    'Content-Type': 'application/json',
  },
});

// 请求拦截器
api.interceptors.request.use(
  (config) => {
    // 在这里可以添加 token 等认证信息
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

// 响应拦截器
api.interceptors.response.use(
  (response) => {
    return response.data;
  },
  (error) => {
    // 统一错误处理
    console.error('API Error:', error);
    return Promise.reject(error);
  }
);

// 封装 GET 请求
export const get = (url: string, params?: any) => {
  return api.get(url, { params });
};

// 封装 POST 请求
export const post = (url: string, data?: any) => {
  return api.post(url, data);
};

// 封装 PUT 请求
export const put = (url: string, data?: any) => {
  return api.put(url, data);
};

// 封装 DELETE 请求
export const del = (url: string) => {
  return api.delete(url);
};

export default api; 