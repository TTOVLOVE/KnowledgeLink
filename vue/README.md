# Vue 3 + TypeScript + Vite

This template should help get you started developing with Vue 3 and TypeScript in Vite. The template uses Vue 3 `<script setup>` SFCs, check out the [script setup docs](https://v3.vuejs.org/api/sfc-script-setup.html#sfc-script-setup) to learn more.

Learn more about the recommended Project Setup and IDE Support in the [Vue Docs TypeScript Guide](https://vuejs.org/guide/typescript/overview.html#project-setup).

# 知识分享平台

这是一个基于 Vue 3 + TypeScript + Vite 的前端项目，使用 PHP 作为后端。

## 项目结构

```
project/
├── src/                # 前端源代码
│   ├── components/    # Vue 组件
│   ├── services/      # API 服务
│   └── ...
└── php/               # PHP 后端
    ├── api/          # API 端点
    └── ...
```

## 开发环境设置

### 前端开发

1. 安装依赖：
```bash
npm install
```

2. 启动开发服务器：
```bash
npm run dev
```

### 后端开发

1. 确保已安装 PHP（推荐 PHP 7.4+）
2. 在 php 目录下启动 PHP 内置服务器：
```bash
php -S localhost:8000
```

## API 使用说明

### 基础配置

- 前端 API 请求基础 URL: `/api`
- 后端服务器地址: `http://localhost:8000`

### 示例 API

- GET `/api/example`
  - 返回示例数据
- POST `/api/example`
  - 接收 JSON 数据并返回处理结果

### 前端 API 调用示例

```typescript
import { get, post } from './services/api'

// GET 请求
const response = await get('/example')

// POST 请求
const response = await post('/example', {
  name: '测试数据',
  value: 123
})
```

## 注意事项

1. 开发时确保前端和后端服务器同时运行
2. 所有 API 请求都会通过 Vite 代理转发到后端
3. 后端 API 已配置 CORS，支持跨域请求
