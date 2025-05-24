import { createApp } from 'vue'
import { createRouter, createWebHistory } from 'vue-router'
import { createPinia } from 'pinia'
import './assets/styles/main.css'
import App from './App.vue'

// Import components for routing
import Home from './views/Home.vue'
import ArticleDetail from './views/ArticleDetail.vue'
import Questions from './views/Questions.vue'
import QuestionDetail from './views/QuestionDetail.vue'
import Rankings from './views/Rankings.vue'
import Checkin from './views/Checkin.vue'
import NewTask from './views/NewTask.vue'
import Profile from './views/Profile.vue'
import UserInfo from './views/UserInfo.vue'
import PublishQuestion from './views/PublishQuestion.vue'
import PublishArticle from './views/PublishArticle.vue'
import Collections from './views/Collections.vue'
import PointsExchange from './views/PointsExchange.vue'
import PointsDetails from './views/PointsDetails.vue'
import CategoryView from './views/CategoryView.vue'

// Create router
const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/', component: Home },
    { path: '/article/:id', component: ArticleDetail },
    { path: '/questions', component: Questions },
    { path: '/question/:id', component: QuestionDetail },
    { path: '/rankings', component: Rankings },
    { path: '/checkin', component: Checkin },
    { path: '/new-task', component: NewTask },
    { path: '/tasks', component: () => import('./views/Tasks.vue') },
    { path: '/profile', component: Profile },
    { path: '/user-info', component: UserInfo },
    { path: '/publish-question', component: PublishQuestion },
    { path: '/publish-article', component: PublishArticle },
    { path: '/collections', component: Collections },
    { path: '/points/exchange', component: PointsExchange },
    { path: '/points/details', component: PointsDetails },
    { path: '/category/:id', component: CategoryView },
    { path: '/login', component: () => import('./views/LoginRegister.vue')}
// 需要 shims-vue.d.ts 文件支持
  ]
})

// Create store
const pinia = createPinia()

// Create app
const app = createApp(App)

// Use plugins
import { useUserStore } from './store/user';
app.use(router)
app.use(pinia)

// 全局拉取用户信息
const userStore = useUserStore();
userStore.fetchUser();

// Mount app
app.mount('#app')