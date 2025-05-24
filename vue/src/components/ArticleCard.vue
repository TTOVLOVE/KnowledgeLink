<template>
  <router-link :to="`/article/${article.id}`" class="article-card-link">
    <div class="article-card">
      <div class="cover">
        <img :src="article.cover" :alt="article.title">
      </div>
      <div class="content">
        <h3 class="title">{{ article.title }}</h3>
        <div class="meta">
          <span class="author">{{ article.author.name }}</span>
          <span class="category">{{ article.category }}</span>
          <span class="rating">{{ article.rating }}分</span>
        </div>
        <p class="summary">{{ article.summary }}</p>
      </div>
    </div>
  </router-link>
</template>

<script setup lang="ts">
import { defineProps } from 'vue';

interface Author {
  id: number | string;
  name: string;
  avatar: string;
}

interface Article {
  id: number | string;
  title: string;
  author: Author;
  cover: string;
  summary: string;
  category: string;
  rating: number | string;
}

defineProps<{ article: Article }>()
</script>

<style scoped>
.article-card-link {
  text-decoration: none;
  color: inherit;
  display: block;
  cursor: pointer;
}
.article-card-link:hover .article-card {
  box-shadow: 0 4px 24px 0 rgba(0,0,0,0.10);
  transform: translateY(-2px) scale(1.01);
}
.article-card {
  background-color: var(--color-bg-secondary);
  border-radius: var(--radius-md);
  overflow: hidden;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.cover {
  width: 100%;
  height: 200px;
  overflow: hidden;
}
.cover img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
.content {
  padding: var(--space-4);
}
.title {
  margin: 0 0 var(--space-2);
  font-size: 1.2rem;
  color: var(--color-text-primary);
}
.meta {
  display: flex;
  gap: var(--space-3);
  margin-bottom: var(--space-2);
  font-size: 0.9rem;
  color: var(--color-text-secondary);
}
.summary {
  margin: 0;
  font-size: 0.9rem;
  color: var(--color-text-secondary);
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>