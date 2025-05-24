import { defineStore } from 'pinia'

export interface Author {
  id: string;
  name: string;
  avatar: string;
}

export interface Article {
  id: string;
  title: string;
  summary: string;
  content: string;
  coverImage: string;
  publishDate: string;
  rating: number;
  author: Author;
}

export const useArticleStore = defineStore('articles', {
  state: () => ({
    articles: [
      {
        id: '1',
        title: 'Python库加速',
        summary: '在Python开发中，安装第三方库是必不可少的一步。然而，由于网络原因，直接从官方PyPI源安装库可能会非常缓慢...',
        content: `
        <h2>在Python开发中，安装第三方库是必不可少的一步。然而，由于网络原因，直接从官方PyPI源安装库可能会非常缓慢，甚至失败。为了解决这个问题，许多国内镜像源提供了更快的下载速度，其中清华大学的镜像源使用最广泛的之一。本文将详细介绍如何使用清华大学镜像源安装Python库，包括临时使用和永久配置的方法。</h2>
        
        <h3>一、临时使用清华大学镜像源（推荐）</h3>
        <p>在安装库时，可以通过pip的--index-url参数指定清华大学镜像源。例如（安装Crypto库）：</p>
        <pre>pip install pycryptodomex -i https://pypi.tuna.tsinghua.edu.cn/simple</pre>
        <p>这种方式会在本次安装中使用清华大学镜像源，而不会影响后续的pip操作。</p>
        
        <h3>二、永久配置清华大学镜像源</h3>
        <p>如果你希望永久使用pip都默认使用清华大学镜像源，可以修改pip的配置文件。</p>
        
        <h4>配置文件路径：</h4>
        <p>Windows：</p>
        <p>用户级别：%APPDATA%\\pip\\pip.conf</p>
        <p>系统级别：C:\\Program Files\\Python\\XX\\Lib\\site-packages\\pip.conf</p>
        
        <p>Linux/macOS：</p>
        <p>用户级别：~/.pip/pip.conf</p>
        <p>系统级别：/etc/pip.conf</p>
        
        <h4>配置步骤：</h4>
        <p>打开或创建配置文件：</p>
        
        <p>Windows用户可以在C:\\Users\\<你的用户名>创建相应文件。</p>
        `,
        coverImage: 'https://picsum.photos/id/180/300/200',
        publishDate: '2025-02-15',
        rating: 8.5,
        author: {
          id: '101',
          name: 'Moonbeaut',
          avatar: 'https://picsum.photos/id/64/100/100'
        }
      },
      {
        id: '2',
        title: 'Fastjson反序列化',
        summary: 'Fastjson是由alibaba开发并维护的一个JSON工具，以其特有的算法，号称最快的json库',
        content: `
        <h2>Fastjson简介</h2>
        <p>Fastjson是阿里巴巴开发的一个Java语言编写的高性能JSON库，它采用一种"假定有序快速匹配"的算法，号称是目前Java语言中最快的JSON库。Fastjson接口简单易用，已经被广泛应用在各种Java项目中。</p>
        
        <h3>特点</h3>
        <ul>
          <li>快速POJO到JSON的序列化</li>
          <li>支持泛型</li>
          <li>支持JSON与JavaBean之间的转换</li>
          <li>支持注解</li>
        </ul>
        
        <h3>基本用法</h3>
        <p>序列化：将Java对象转换为JSON字符串</p>
        <pre>
        String jsonString = JSON.toJSONString(obj);
        </pre>
        
        <p>反序列化：将JSON字符串转换为Java对象</p>
        <pre>
        VO vo = JSON.parseObject(jsonString, VO.class);
        </pre>
        
        <h3>安全问题</h3>
        <p>Fastjson在1.2.24及之前版本中存在远程代码执行高危安全漏洞。攻击者可以通过构造恶意JSON数据利用Fastjson反序列化的特性执行任意代码。</p>
        
        <p>建议使用最新版本的Fastjson，并启用SafeMode以提高安全性。在1.2.68版本后，默认开启了SafeMode。</p>
        
        <h3>性能对比</h3>
        <p>与其他JSON库如Gson、Jackson相比，Fastjson在序列化和反序列化速度上通常具有明显优势，特别是在处理大型对象时。</p>
        `,
        coverImage: 'https://picsum.photos/id/160/300/200',
        publishDate: '2025-02-15',
        rating: 9.0,
        author: {
          id: '102',
          name: 'TechGuru',
          avatar: 'https://picsum.photos/id/65/100/100'
        }
      },
      {
        id: '3',
        title: 'Python数据分析基础',
        summary: '数据分析是现代商业决策的核心。Python凭借其丰富的库和简洁的语法成为数据分析的首选语言...',
        content: `
        <h2>Python数据分析入门指南</h2>
        <p>数据分析是从原始数据中提取有用信息的过程，Python因其简洁的语法和强大的生态系统成为了数据分析领域的主导语言。本文将介绍Python数据分析的基础知识和常用库。</p>
        
        <h3>核心库介绍</h3>
        <ul>
          <li><strong>NumPy</strong>：提供高性能的多维数组对象和数学函数</li>
          <li><strong>Pandas</strong>：提供数据结构和数据分析工具</li>
          <li><strong>Matplotlib</strong>：用于数据可视化</li>
          <li><strong>Seaborn</strong>：基于Matplotlib的高级可视化库</li>
          <li><strong>Scikit-learn</strong>：机器学习工具</li>
        </ul>
        
        <h3>数据处理流程</h3>
        <ol>
          <li>数据获取：从文件、数据库或API中获取数据</li>
          <li>数据清洗：处理缺失值、异常值、重复数据</li>
          <li>数据转换：特征工程、标准化、正则化</li>
          <li>数据分析：统计分析、假设检验</li>
          <li>数据可视化：展示数据模式和趋势</li>
          <li>建模与预测：使用机器学习算法分析数据</li>
        </ol>
        
        <h3>实践案例</h3>
        <p>以下是一个简单的数据分析示例：</p>
        <pre>
        import pandas as pd
        import matplotlib.pyplot as plt
        
        # 加载数据
        df = pd.read_csv('sales_data.csv')
        
        # 数据清洗
        df = df.dropna()
        
        # 数据分析
        monthly_sales = df.groupby('month')['sales'].sum()
        
        # 数据可视化
        monthly_sales.plot(kind='bar')
        plt.title('Monthly Sales')
        plt.xlabel('Month')
        plt.ylabel('Sales ($)')
        plt.show()
        </pre>
        `,
        coverImage: 'https://picsum.photos/id/0/300/200',
        publishDate: '2025-02-10',
        rating: 9.2,
        author: {
          id: '103',
          name: 'DataWizard',
          avatar: 'https://picsum.photos/id/66/100/100'
        }
      },
      {
        id: '4',
        title: 'Vue.js 3组合式API详解',
        summary: 'Vue.js 3引入的组合式API (Composition API)提供了更灵活的代码组织方式，本文详细介绍其用法...',
        content: `
        <h2>Vue.js 3组合式API详解</h2>
        <p>Vue.js 3的组合式API是Vue框架的一次重要革新，它提供了一种新的编写Vue组件的方式，使代码更易于组织和复用。</p>
        
        <h3>组合式API的核心概念</h3>
        <ul>
          <li><strong>setup函数</strong>：组合式API的入口点</li>
          <li><strong>响应式API</strong>：ref、reactive、computed、watch等</li>
          <li><strong>生命周期钩子</strong>：onMounted、onUpdated等</li>
          <li><strong>依赖注入</strong>：provide/inject</li>
        </ul>
        
        <h3>与选项式API的对比</h3>
        <p>组合式API相比传统的选项式API有以下优势：</p>
        <ul>
          <li>更好的代码组织：相关功能可以组织在一起</li>
          <li>更好的逻辑复用：使用组合函数代替混入</li>
          <li>更好的类型推导：TypeScript支持更加友好</li>
          <li>更小的包体积：通过摇树优化减小打包体积</li>
        </ul>
        
        <h3>基本使用示例</h3>
        <pre>
        import { ref, onMounted } from 'vue'
        
        export default {
          setup() {
            // 响应式状态
            const count = ref(0)
            
            // 方法
            const increment = () => {
              count.value++
            }
            
            // 生命周期钩子
            onMounted(() => {
              console.log('Component mounted')
            })
            
            // 暴露给模板
            return {
              count,
              increment
            }
          }
        }
        </pre>
        
        <h3>&lt;script setup&gt;语法糖</h3>
        <p>Vue 3.2引入了&lt;script setup&gt;语法糖，进一步简化了组合式API的使用：</p>
        <pre>
        &lt;script setup&gt;
        import { ref, onMounted } from 'vue'
        
        // 响应式状态
        const count = ref(0)
        
        // 方法
        const increment = () => {
          count.value++
        }
        
        // 生命周期钩子
        onMounted(() => {
          console.log('Component mounted')
        })
        &lt;/script&gt;
        </pre>
        `,
        coverImage: 'https://picsum.photos/id/139/300/200',
        publishDate: '2025-02-05',
        rating: 9.5,
        author: {
          id: '104',
          name: 'VueMaster',
          avatar: 'https://picsum.photos/id/67/100/100'
        }
      },
      {
        id: '5',
        title: '微服务架构设计模式',
        summary: '微服务已成为现代应用开发的主流架构模式。本文探讨微服务设计中的关键模式和最佳实践...',
        content: `
        <h2>微服务架构设计模式</h2>
        <p>微服务架构是一种将单个应用程序开发为一套小型服务的方法，每个服务运行在自己的进程中，通过轻量级机制通信。</p>
        
        <h3>核心设计模式</h3>
        
        <h4>1. 分解模式</h4>
        <ul>
          <li><strong>按业务能力分解</strong>：基于业务领域划分服务</li>
          <li><strong>按子域分解</strong>：使用领域驱动设计(DDD)的概念</li>
        </ul>
        
        <h4>2. 集成模式</h4>
        <ul>
          <li><strong>API网关</strong>：为客户端提供统一入口</li>
          <li><strong>聚合器模式</strong>：组合多个服务结果</li>
          <li><strong>客户端发现</strong>：客户端直接访问服务注册表</li>
          <li><strong>服务端发现</strong>：通过负载均衡器访问服务</li>
        </ul>
        
        <h4>3. 数据管理模式</h4>
        <ul>
          <li><strong>数据库每服务</strong>：每个服务独立数据库</li>
          <li><strong>事件溯源</strong>：通过事件序列存储状态</li>
          <li><strong>CQRS</strong>：命令查询职责分离</li>
          <li><strong>Saga模式</strong>：管理分布式事务</li>
        </ul>
        
        <h4>4. 部署模式</h4>
        <ul>
          <li><strong>单主机多服务实例</strong>：传统部署方式</li>
          <li><strong>单主机单服务实例</strong>：虚拟机部署</li>
          <li><strong>无服务器部署</strong>：使用FaaS平台</li>
          <li><strong>服务网格</strong>：管理服务间通信</li>
        </ul>
        
        <h3>实施挑战</h3>
        <ul>
          <li>分布式系统复杂性</li>
          <li>服务间通信可靠性</li>
          <li>数据一致性维护</li>
          <li>监控与调试难度</li>
          <li>部署与运维复杂性</li>
        </ul>
        
        <h3>最佳实践</h3>
        <ul>
          <li>建立自动化CI/CD流程</li>
          <li>实施全面监控与日志收集</li>
          <li>采用弹性服务设计</li>
          <li>实施服务治理机制</li>
          <li>构建团队围绕业务能力</li>
        </ul>
        `,
        coverImage: 'https://picsum.photos/id/60/300/200',
        publishDate: '2025-01-28',
        rating: 9.4,
        author: {
          id: '105',
          name: 'ArchitectPro',
          avatar: 'https://picsum.photos/id/68/100/100'
        }
      },
      {
        id: '6',
        title: 'Docker容器最佳实践',
        summary: 'Docker彻底改变了应用部署方式。本指南分享Docker容器使用中的最佳实践和常见陷阱...',
        content: `
        <h2>Docker容器最佳实践</h2>
        <p>Docker容器化技术彻底改变了应用程序的打包、分发和运行方式。本文总结了Docker使用中的关键最佳实践。</p>
        
        <h3>镜像构建最佳实践</h3>
        <ul>
          <li><strong>使用官方基础镜像</strong>：优先使用官方认证的基础镜像</li>
          <li><strong>多阶段构建</strong>：减小最终镜像体积</li>
          <li><strong>最小化层数</strong>：合并RUN指令减少层数</li>
          <li><strong>适当标记版本</strong>：避免使用latest标签</li>
          <li><strong>清理不必要的文件</strong>：删除缓存和临时文件</li>
        </ul>
        
        <h3>安全最佳实践</h3>
        <ul>
          <li><strong>不以root用户运行</strong>：使用非特权用户</li>
          <li><strong>扫描安全漏洞</strong>：使用工具如Trivy、Clair</li>
          <li><strong>使用内容信任</strong>：验证镜像真实性</li>
          <li><strong>限制资源使用</strong>：设置内存和CPU限制</li>
          <li><strong>使用只读文件系统</strong>：防止运行时修改</li>
        </ul>
        
        <h3>容器管理最佳实践</h3>
        <ul>
          <li><strong>健康检查</strong>：配置HEALTHCHECK指令</li>
          <li><strong>合理使用卷</strong>：持久化重要数据</li>
          <li><strong>日志管理</strong>：实施日志轮换策略</li>
          <li><strong>容器编排</strong>：使用Kubernetes或Docker Swarm</li>
          <li><strong>监控</strong>：实施全面的监控解决方案</li>
        </ul>
        
        <h3>Dockerfile示例</h3>
        <pre>
        # 多阶段构建示例
        FROM node:14 AS builder
        WORKDIR /app
        COPY package*.json ./
        RUN npm install
        COPY . .
        RUN npm run build
        
        FROM nginx:alpine
        COPY --from=builder /app/dist /usr/share/nginx/html
        EXPOSE 80
        # 健康检查
        HEALTHCHECK --interval=30s --timeout=3s \
          CMD wget -q --spider http://localhost/ || exit 1
        # 使用非root用户
        USER nginx
        CMD ["nginx", "-g", "daemon off;"]
        </pre>
        `,
        coverImage: 'https://picsum.photos/id/119/300/200',
        publishDate: '2025-01-22',
        rating: 9.3,
        author: {
          id: '106',
          name: 'DockerPro',
          avatar: 'https://picsum.photos/id/69/100/100'
        }
      },
      {
        id: '7',
        title: 'GraphQL API设计指南',
        summary: 'GraphQL正在改变API设计范式。本文探讨GraphQL API的设计原则和最佳实践...',
        content: `
        <h2>GraphQL API设计指南</h2>
        <p>GraphQL是一种用于API的查询语言和运行时，它使客户端能够准确地获取所需的数据，不多也不少。本文将分享GraphQL API设计的关键原则。</p>
        
        <h3>Schema设计原则</h3>
        <ul>
          <li><strong>以领域为中心</strong>：Schema应反映业务领域模型</li>
          <li><strong>考虑查询复杂度</strong>：防止过于复杂的查询导致性能问题</li>
          <li><strong>命名一致性</strong>：保持一致的命名约定</li>
          <li><strong>版本管理</strong>：通过Schema演化而非版本号管理</li>
          <li><strong>考虑分页</strong>：实现标准的Connection模式</li>
        </ul>
        
        <h3>解析器最佳实践</h3>
        <ul>
          <li><strong>数据加载优化</strong>：使用DataLoader解决N+1问题</li>
          <li><strong>批处理</strong>：合并多个相似请求</li>
          <li><strong>缓存策略</strong>：实施适当的缓存机制</li>
          <li><strong>错误处理</strong>：结构化错误响应</li>
          <li><strong>性能监控</strong>：监控解析器执行时间</li>
        </ul>
        
        <h3>安全考虑</h3>
        <ul>
          <li><strong>查询复杂度分析</strong>：限制过于复杂的查询</li>
          <li><strong>速率限制</strong>：防止API滥用</li>
          <li><strong>身份验证与授权</strong>：实施细粒度的访问控制</li>
          <li><strong>输入验证</strong>：验证所有客户端输入</li>
          <li><strong>防止信息泄露</strong>：控制错误消息中的敏感信息</li>
        </ul>
        
        <h3>Schema示例</h3>
        <pre>
        type User {
          id: ID!
          name: String!
          email: String!
          posts(first: Int, after: String): PostConnection!
          createdAt: DateTime!
          updatedAt: DateTime!
        }
        
        type Post {
          id: ID!
          title: String!
          content: String!
          author: User!
          comments(first: Int, after: String): CommentConnection!
          createdAt: DateTime!
          updatedAt: DateTime!
        }
        
        type PostConnection {
          edges: [PostEdge!]!
          pageInfo: PageInfo!
          totalCount: Int!
        }
        
        type PostEdge {
          node: Post!
          cursor: String!
        }
        
        type PageInfo {
          hasNextPage: Boolean!
          endCursor: String
        }
        </pre>
        `,
        coverImage: 'https://picsum.photos/id/143/300/200',
        publishDate: '2025-01-15',
        rating: 9.1,
        author: {
          id: '107',
          name: 'GraphQLGuru',
          avatar: 'https://picsum.photos/id/70/100/100'
        }
      },
      {
        id: '8',
        title: 'React性能优化指南',
        summary: '随着React应用复杂度增加，性能优化变得至关重要。本文分享React应用性能优化的策略...',
        content: `
        <h2>React性能优化指南</h2>
        <p>React凭借其虚拟DOM和声明式编程模型使UI开发变得更加简单，但随着应用复杂度的增加，性能优化变得尤为重要。</p>
        
        <h3>组件优化</h3>
        <ul>
          <li><strong>使用React.memo</strong>：缓存组件渲染结果</li>
          <li><strong>useMemo和useCallback</strong>：避免不必要的计算和渲染</li>
          <li><strong>虚拟化长列表</strong>：使用react-window或react-virtualized</li>
          <li><strong>组件懒加载</strong>：使用React.lazy和Suspense</li>
          <li><strong>优化条件渲染</strong>：减少条件渲染的复杂度</li>
        </ul>
        
        <h3>状态管理优化</h3>
        <ul>
          <li><strong>状态下推</strong>：将状态移动到需要它的组件</li>
          <li><strong>避免状态提升过高</strong>：防止不必要的重渲染</li>
          <li><strong>状态范式化</strong>：避免嵌套状态结构</li>
          <li><strong>使用不可变数据结构</strong>：便于比较和优化</li>
          <li><strong>批量更新状态</strong>：减少渲染次数</li>
        </ul>
        
        <h3>渲染优化</h3>
        <ul>
          <li><strong>使用React DevTools Profiler</strong>：识别性能瓶颈</li>
          <li><strong>避免内联函数</strong>：减少不必要的重新创建</li>
          <li><strong>拆分大型组件</strong>：提高可维护性和性能</li>
          <li><strong>使用Fragment</strong>：减少DOM节点</li>
          <li><strong>合理使用key</strong>：帮助React识别列表变化</li>
        </ul>
        
        <h3>代码示例</h3>
        <pre>
        // 使用React.memo优化组件
        const ExpensiveComponent = React.memo(({ value }) => {
          // 复杂计算或渲染
          return <div>{/* 渲染结果 */}</div>;
        });
        
        // 使用useMemo缓存计算结果
        function SearchResults({ items, query }) {
          const filteredItems = React.useMemo(() => {
            return items.filter(item => 
              item.name.toLowerCase().includes(query.toLowerCase())
            );
          }, [items, query]);
          
          return (
            <ul>
              {filteredItems.map(item => (
                <li key={item.id}>{item.name}</li>
              ))}
            </ul>
          );
        }
        
        // 使用useCallback缓存函数
        function Parent() {
          const [count, setCount] = useState(0);
          
          const handleClick = React.useCallback(() => {
            setCount(c => c + 1);
          }, []);
          
          return <Child onClick={handleClick} />;
        }
        </pre>
        `,
        coverImage: 'https://picsum.photos/id/26/300/200',
        publishDate: '2025-01-10',
        rating: 9.6,
        author: {
          id: '108',
          name: 'ReactNinja',
          avatar: 'https://picsum.photos/id/71/100/100'
        }
      }
    ],
    activeArticle: null as Article | null
  }),
  actions: {
    setActiveArticle(articleId: string) {
      const article = this.articles.find(a => a.id === articleId);
      this.activeArticle = article || null;
    }
  }
})