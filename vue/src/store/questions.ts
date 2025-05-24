import { defineStore } from 'pinia';

export interface Question {
  id: string;
  title: string;
  content: string;
  author: {
    id: string;
    name: string;
    avatar: string;
  };
  tags: string[];
  createdAt: string;
  answerCount: number;
  reward: number;
}

export interface Answer {
  id: string;
  questionId: string;
  content: string;
  author: {
    id: string;
    name: string;
    avatar: string;
  };
  createdAt: string;
  isAccepted: boolean;
  likes: number;
  isLiked: boolean;
}

export const useQuestionStore = defineStore('questions', {
  state: () => ({
    questions: [
      {
        id: '1',
        title: 'React Hooks 在性能优化方面有什么最佳实践？',
        content: '最近在开发一个大型应用，发现使用 useCallback 和 useMemo 虽性能提升，但请教下大家在实际项目中是如何处理的？',
        author: {
          id: '1',
          name: '新手小白',
          avatar: 'https://picsum.photos/id/64/100/100'
        },
        tags: ['React', '性能优化'],
        createdAt: '2025-02-20',
        answerCount: 0,
        reward: 5
      },
      {
        id: '2',
        title: '如何优化 Flutter 应用的启动时间？',
        content: '我们的 Flutter 应用启动时间较长，已经尝试了减少图和缩略图加载，还有什么其他建议吗？',
        author: {
          id: '2',
          name: 'akaky',
          avatar: 'https://picsum.photos/id/65/100/100'
        },
        tags: ['Flutter', '性能优化'],
        createdAt: '2025-02-19',
        answerCount: 12,
        reward: 8
      }
    ],
    answers: [
      {
        id: '1',
        questionId: '1',
        content: '计对您提到的问题，我建议从以下几个方面着手优化：1. 代码分割 - 使用 React.lazy() 实现组件懒加载 - 路由级别的代码分割 - 第三方库按需引入 2. 资源优化 - 使用 webpack-bundle-analyzer 分析包体积 - 开启 gzip 压缩 - 合理使用 Tree Shaking 3. 渲染优化 - 使用 React.memo 避免不必要的重渲染 - 合理使用 useMemo 和 useCallback - 虚拟列表处理长列表 4. 缓存策略 - 合理配置 HTTP 缓存 - 使用 Service Worker 缓存静态资源',
        author: {
          id: '3',
          name: '张三',
          avatar: 'https://picsum.photos/id/66/100/100'
        },
        createdAt: '2025-02-21 14:30',
        isAccepted: true,
        likes: 42,
        isLiked: false
      }
    ]
  }),
  actions: {
    getQuestionById(id: string) {
      return this.questions.find(q => q.id === id);
    },
    getAnswersForQuestion(questionId: string) {
      return this.answers.filter(answer => answer.questionId === questionId);
    }
  }
});