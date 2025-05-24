/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-11.7.2-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: knowledge_sharing_platform
-- ------------------------------------------------------
-- Server version	5.7.26

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `answers`
--

DROP TABLE IF EXISTS `answers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `answers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `likes` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `answers`
--

LOCK TABLES `answers` WRITE;
/*!40000 ALTER TABLE `answers` DISABLE KEYS */;
INSERT INTO `answers` VALUES
(1,1,1,'我推荐官方文档+B站up主视频，配合实战项目效果最好！','2025-04-29 16:00:08',1),
(2,1,2,'可以多刷LeetCode题目，锻炼动手能力。','2025-04-29 16:00:08',1),
(3,2,3,'常见原因有：数据类型不一致、使用了函数、like左模糊、隐式转换等。','2025-04-29 16:00:08',0),
(4,1,1,'菜鸟教程不错，可以试试','2025-04-29 16:07:51',1),
(5,6,1,'回答功能测试','2025-04-29 16:14:12',2),
(6,6,1,'测试回答','2025-04-29 22:49:27',0),
(7,6,8,'测试','2025-04-29 22:53:47',0),
(8,8,3,'测试回答','2025-04-29 23:27:45',1),
(9,8,9,'ceshi','2025-04-30 14:00:50',1),
(10,9,10,'测试回答','2025-04-30 14:30:13',1);
/*!40000 ALTER TABLE `answers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `articles`
--

DROP TABLE IF EXISTS `articles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` int(11) NOT NULL,
  `rating` decimal(3,1) DEFAULT '0.0',
  `cover` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `summary` text COLLATE utf8mb4_unicode_ci,
  `content` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `articles_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `articles`
--

LOCK TABLES `articles` WRITE;
/*!40000 ALTER TABLE `articles` DISABLE KEYS */;
INSERT INTO `articles` VALUES
(1,1,'如何高效学习编程',1,4.8,'https://picsum.photos/400/200?random=1','本文介绍了一些高效学习编程的方法和技巧...','本文介绍了高效学习编程的思路','2025-04-28 07:58:54','2025-04-29 06:57:15'),
(2,3,'人工智能的发展趋势',2,4.5,'https://picsum.photos/400/200?random=2','探讨人工智能领域的最新发展和未来趋势...','详细内容...','2025-04-28 07:58:54','2025-04-29 07:14:25'),
(3,2,'Web 开发最佳实践',4,4.7,'https://picsum.photos/400/200?random=3','分享 Web 开发中的一些最佳实践和经验...','<h2>Maven是Java项目中最流行的构建和依赖管理工具之一。本文将介绍如何使用Maven管理项目依赖，以及如何配置国内镜像源加速依赖下载。</h2>\r\n\r\n<h3>一、Maven安装</h3>\r\n<p>1. 下载Maven二进制包</p >\r\n<p>2. 解压并配置环境变量</p >\r\n<pre># Linux/macOS示例\r\nexport MAVEN_HOME=/opt/apache-maven-3.6.3\r\nexport PATH=$PATH:$MAVEN_HOME/bin</pre>\r\n\r\n<h3>二、pom.xml依赖配置</h3>\r\n<p>在pom.xml中添加依赖：</p >\r\n<pre>&lt;dependency&gt;\r\n    &lt;groupId&gt;org.springframework.boot&lt;/groupId&gt;\r\n    &lt;artifactId&gt;spring-boot-starter-web&lt;/artifactId&gt;\r\n    &lt;version&gt;2.5.0&lt;/version&gt;\r\n&lt;/dependency&gt;</pre>\r\n\r\n<h3>三、配置阿里云镜像源</h3>\r\n<p>修改settings.xml文件加速下载：</p >\r\n<pre>&lt;mirror&gt;\r\n    &lt;id&gt;aliyunmaven&lt;/id&gt;\r\n    &lt;mirrorOf&gt;*&lt;/mirrorOf&gt;\r\n    &lt;name&gt;阿里云公共仓库&lt;/name&gt;\r\n    &lt;url&gt;https://maven.aliyun.com/repository/public&lt;/url&gt;\r\n&lt;/mirror&gt;</pre>','2025-04-28 07:58:54','2025-04-29 07:14:25'),
(4,3,'Python库加速',3,8.5,'https://picsum.photos/id/180/300/200','在Python开发中，安装第三方库是必不可少的一步。然而，由于网络原因，直接从官方PyPI源安装库可能会非常缓慢...','<h2>在Python开发中，安装第三方库是必不可少的一步。然而，由于网络原因，直接从官方PyPI源安装库可能会非常缓慢，甚至失败。为了解决这个问题，许多国内镜像源提供了更快的下载速度，其中清华大学的镜像源使用最广泛的之一。本文将详细介绍如何使用清华大学镜像源安装Python库，包括临时使用和永久配置的方法。</h2>\n\n<h3>一、临时使用清华大学镜像源（推荐）</h3>\n<p>在安装库时，可以通过pip的--index-url参数指定清华大学镜像源。例如（安装Crypto库）：</p>\n<pre>pip install pycryptodomex -i https://pypi.tuna.tsinghua.edu.cn/simple </pre>\n<p>这种方式会在本次安装中使用清华大学镜像源，而不会影响后续的pip操作。</p>\n\n<h3>二、永久配置清华大学镜像源</h3>\n<p>如果你希望永久使用pip都默认使用清华大学镜像源，可以修改pip的配置文件。</p>\n\n<h4>配置文件路径：</h4>\n<p>Windows：</p>\n<p>用户级别：%APPDATA%\\pip\\pip.conf</p>\n<p>系统级别：C:\\Program Files\\Python\\XX\\Lib\\site-packages\\pip.conf</p>\n\n<p>Linux/macOS：</p>\n<p>用户级别：~/.pip/pip.conf</p>\n<p>系统级别：/etc/pip.conf</p>\n\n<h4>配置步骤：</h4>\n<p>打开或创建配置文件：</p>\n\n<p>Windows用户可以在C:\\Users\\<你的用户名>创建相应文件。</p>','2025-02-14 16:00:00','2025-04-29 07:14:25'),
(5,2,'Fastjson反序列化',1,9.0,'https://picsum.photos/id/160/300/200','Fastjson是由alibaba开发并维护的一个JSON工具，以其特有的算法，号称最快的json库','<h2>Fastjson简介</h2>\n<p>Fastjson是阿里巴巴开发的一个Java语言编写的高性能JSON库，它采用一种\"假定有序快速匹配\"的算法，号称是目前Java语言中最快的JSON库。Fastjson接口简单易用，已经被广泛应用在各种Java项目中。</p>\n\n<h3>特点</h3>\n<ul>\n  <li>快速POJO到JSON的序列化</li>\n  <li>支持泛型</li>\n  <li>支持JSON与JavaBean之间的转换</li>\n  <li>支持注解</li>\n</ul>\n\n<h3>基本用法</h3>\n<p>序列化：将Java对象转换为JSON字符串</p>\n<pre>\nString jsonString = JSON.toJSONString(obj);\n</pre>\n\n<p>反序列化：将JSON字符串转换为Java对象</p>\n<pre>\nVO vo = JSON.parseObject(jsonString, VO.class);\n</pre>\n\n<h3>安全问题</h3>\n<p>Fastjson在1.2.24及之前版本中存在远程代码执行高危安全漏洞。攻击者可以通过构造恶意JSON数据利用Fastjson反序列化的特性执行任意代码。</p>\n\n<p>建议使用最新版本的Fastjson，并启用SafeMode以提高安全性。在1.2.68版本后，默认开启了SafeMode。</p>\n\n<h3>性能对比</h3>\n<p>与其他JSON库如Gson、Jackson相比，Fastjson在序列化和反序列化速度上通常具有明显优势，特别是在处理大型对象时。</p>','2025-02-14 16:00:00','2025-04-29 07:14:25'),
(7,4,'Vue.js 3组合式API详解',7,9.5,'https://picsum.photos/id/139/300/200','Vue.js 3引入的组合式API (Composition API)提供了更灵活的代码组织方式，本文详细介绍其用法...','<h2>Vue.js 3组合式API详解</h2>\n<p>Vue.js 3的组合式API是Vue框架的一次重要革新，它提供了一种新的编写Vue组件的方式，使代码更易于组织和复用。</p>\n\n<h3>组合式API的核心概念</h3>\n<ul>\n  <li><strong>setup函数</strong>：组合式API的入口点</li>\n  <li><strong>响应式API</strong>：ref、reactive、computed、watch等</li>\n  <li><strong>生命周期钩子</strong>：onMounted、onUpdated等</li>\n  <li><strong>依赖注入</strong>：provide/inject</li>\n</ul>\n\n<h3>与选项式API的对比</h3>\n<p>组合式API相比传统的选项式API有以下优势：</p>\n<ul>\n  <li>更好的代码组织：相关功能可以组织在一起</li>\n  <li>更好的逻辑复用：使用组合函数代替混入</li>\n  <li>更好的类型推导：TypeScript支持更加友好</li>\n  <li>更小的包体积：通过摇树优化减小打包体积</li>\n</ul>\n\n<h3>基本使用示例</h3>\n<pre>\nimport { ref, onMounted } from \'vue\'\n\nexport default {\n  setup() {\n    // 响应式状态\n    const count = ref(0)\n    \n    // 方法\n    const increment = () => {\n      count.value++\n    }\n    \n    // 生命周期钩子\n    onMounted(() => {\n      console.log(\'Component mounted\')\n    })\n    \n    // 暴露给模板\n    return {\n      count,\n      increment\n    }\n  }\n}\n</pre>\n\n<h3>&lt;script setup&gt;语法糖</h3>\n<p>Vue 3.2引入了&lt;script setup&gt;语法糖，进一步简化了组合式API的使用：</p>\n<pre>\n&lt;script setup&gt;\nimport { ref, onMounted } from \'vue\'\n\n// 响应式状态\nconst count = ref(0)\n\n// 方法\nconst increment = () => {\n  count.value++\n}\n\n// 生命周期钩子\nonMounted(() => {\n  console.log(\'Component mounted\')\n})\n&lt;/script&gt;\n</pre>','2025-02-04 16:00:00','2025-04-29 07:14:25'),
(8,5,'React性能优化指南',8,9.6,'https://picsum.photos/id/26/300/200','随着React应用复杂度增加，性能优化变得至关重要。本文分享React应用性能优化的策略...','<h2>React性能优化指南</h2>\n<p>React凭借其虚拟DOM和声明式编程模型使UI开发变得更加简单，但随着应用复杂度的增加，性能优化变得尤为重要。</p>\n\n<h3>组件优化</h3>\n<ul>\n  <li><strong>使用React.memo</strong>：缓存组件渲染结果</li>\n  <li><strong>useMemo和useCallback</strong>：避免不必要的计算和渲染</li>\n  <li><strong>虚拟化长列表</strong>：使用react-window或react-virtualized</li>\n  <li><strong>组件懒加载</strong>：使用React.lazy和Suspense</li>\n  <li><strong>优化条件渲染</strong>：减少条件渲染的复杂度</li>\n</ul>\n\n<h3>状态管理优化</h3>\n<ul>\n  <li><strong>状态下推</strong>：将状态移动到需要它的组件</li>\n  <li><strong>避免状态提升过高</strong>：防止不必要的重渲染</li>\n  <li><strong>状态范式化</strong>：避免嵌套状态结构</li>\n  <li><strong>使用不可变数据结构</strong>：便于比较和优化</li>\n  <li><strong>批量更新状态</strong>：减少渲染次数</li>\n</ul>\n\n<h3>渲染优化</h3>\n<ul>\n  <li><strong>使用React DevTools Profiler</strong>：识别性能瓶颈</li>\n  <li><strong>避免内联函数</strong>：减少不必要的重新创建</li>\n  <li><strong>拆分大型组件</strong>：提高可维护性和性能</li>\n  <li><strong>使用Fragment</strong>：减少DOM节点</li>\n  <li><strong>合理使用key</strong>：帮助React识别列表变化</li>\n</ul>\n\n<h3>代码示例</h3>\n<pre>\n// 使用React.memo优化组件\nconst ExpensiveComponent = React.memo(({ value }) => {\n  // 复杂计算或渲染\n  return <div>{/* 渲染结果 */}</div>;\n});\n\n// 使用useMemo缓存计算结果\nfunction SearchResults({ items, query }) {\n  const filteredItems = React.useMemo(() => {\n    return items.filter(item => \n      item.name.toLowerCase().includes(query.toLowerCase())\n    );\n  }, [items, query]);\n  \n  return (\n    <ul>\n      {filteredItems.map(item => (\n        <li key={item.id}>{item.name}</li>\n      ))}\n    </ul>\n  );\n}\n\n// 使用useCallback缓存函数\nfunction Parent() {\n  const [count, setCount] = useState(0);\n  \n  const handleClick = React.useCallback(() => {\n    setCount(c => c + 1);\n  }, []);\n  \n  return <Child onClick={handleClick} />;\n}\n</pre>','2025-01-09 16:00:00','2025-04-29 07:14:25'),
(9,6,'测试文章发布功能',4,0.0,'https://picsum.photos/400/200?random=2','测试测试测试\n测试文章发布功能\n测试测试测试','测试测试测试\n测试文章发布功能\n测试测试测试','2025-04-28 14:18:47','2025-04-29 07:14:25'),
(11,1,'测试文章发布总结',5,0.0,'https://picsum.photos/id/152/3888/2592','测试内容','<h2>测试html编辑器</h2>\n<p>测试测试测试文本编辑器</p>\n<pre>pip install pycryptodomex -i https://pypi.tuna.tsinghua.edu.cn/simple #测试代码块</pre>','2025-04-29 07:44:43','2025-04-29 07:44:43'),
(13,8,'测试用户发布文章',3,0.0,'https://picsum.photos/id/140/2448/2448','测试用户test发布文章','<h2>test发布文章</h2>','2025-04-29 15:18:28','2025-04-29 15:18:28'),
(14,3,'测试文章发布',3,0.0,'https://picsum.photos/id/180/300/200','测试','测试测试','2025-04-30 06:33:48','2025-04-30 06:33:48');
/*!40000 ALTER TABLE `articles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `count` int(11) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES
(1,'JavaScript','javascript-original.svg',128,'2025-04-28 07:58:41','2025-04-28 11:25:48'),
(2,'TypeScript','typescript-original.svg',86,'2025-04-28 07:58:41','2025-04-28 11:25:48'),
(3,'Python','python-original.svg',156,'2025-04-28 07:58:41','2025-04-28 11:25:48'),
(4,'Java','java-original.svg',100,'2025-04-28 07:58:41','2025-04-28 11:25:48'),
(5,'Go','go-original.svg',77,'2025-04-28 07:58:41','2025-04-28 11:25:48'),
(6,'Node.js','nodejs-original.svg',92,'2025-04-28 07:58:41','2025-04-28 11:25:48'),
(7,'Vue.js','vuejs-original.svg',110,'2025-04-28 11:25:48','2025-04-28 11:25:48'),
(8,'React','react-original.svg',134,'2025-04-28 11:25:48','2025-04-28 11:25:48');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `checkin_records`
--

DROP TABLE IF EXISTS `checkin_records`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `checkin_records` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `checkin_date` date NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_user_task_date` (`user_id`,`task_id`,`checkin_date`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `checkin_records`
--

LOCK TABLES `checkin_records` WRITE;
/*!40000 ALTER TABLE `checkin_records` DISABLE KEYS */;
INSERT INTO `checkin_records` VALUES
(1,1,1,'2025-04-29','2025-04-29 16:25:56'),
(2,1,2,'2025-04-29','2025-04-29 16:26:00'),
(3,1,3,'2025-04-29','2025-04-29 16:26:01'),
(4,1,4,'2025-04-29','2025-04-29 16:26:02'),
(5,1,5,'2025-04-29','2025-04-29 16:33:01'),
(6,1,6,'2025-04-29','2025-04-29 16:52:53'),
(7,8,8,'2025-04-29','2025-04-29 23:08:20'),
(8,8,9,'2025-04-29','2025-04-29 23:08:21'),
(9,9,10,'2025-04-30','2025-04-30 14:03:08'),
(10,10,11,'2025-04-30','2025-04-30 14:31:24');
/*!40000 ALTER TABLE `checkin_records` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `coins`
--

DROP TABLE IF EXISTS `coins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `coins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `article_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `count` int(11) NOT NULL DEFAULT '1',
  `time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `article_id` (`article_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `coins`
--

LOCK TABLES `coins` WRITE;
/*!40000 ALTER TABLE `coins` DISABLE KEYS */;
INSERT INTO `coins` VALUES
(1,2,1,1,'2025-04-28 21:27:09'),
(2,1,1,1,'2025-04-28 21:27:20'),
(3,2,1,1,'2025-04-28 21:33:32'),
(4,1,1,1,'2025-04-28 21:34:36'),
(5,1,1,1,'2025-04-28 21:35:10'),
(6,1,1,1,'2025-04-28 21:38:20'),
(7,9,1,1,'2025-04-29 15:07:57'),
(8,11,1,1,'2025-04-29 19:59:23'),
(9,11,1,1,'2025-04-29 19:59:26'),
(10,11,1,1,'2025-04-29 19:59:28'),
(11,11,1,1,'2025-04-29 22:46:25'),
(12,11,8,1,'2025-04-29 22:49:01'),
(13,13,3,1,'2025-04-30 14:32:09');
/*!40000 ALTER TABLE `coins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `collections`
--

DROP TABLE IF EXISTS `collections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `collections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `type` varchar(20) NOT NULL,
  `title` varchar(200) NOT NULL,
  `date` date NOT NULL,
  `link` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `collections`
--

LOCK TABLES `collections` WRITE;
/*!40000 ALTER TABLE `collections` DISABLE KEYS */;
INSERT INTO `collections` VALUES
(1,1,'资源收藏','Vue3组合式API示例','2023-10-14','/article/7'),
(2,1,'资源收藏','Python库加速','2025-04-01','/article/4'),
(3,1,'资源收藏','Fastjson反序列化','2025-04-02','/article/5'),
(5,1,'资源收藏','如何高效学习编程','2025-04-28','/article/1'),
(6,1,'资源收藏','人工智能的发展趋势','2025-04-28','/article/2'),
(7,1,'资源收藏','测试文章发布总结','2025-04-29','/article/11'),
(8,8,'资源收藏','测试文章发布总结','2025-04-29','/article/11'),
(9,3,'资源收藏','Python库加速','2025-04-29','/article/4'),
(10,9,'资源收藏','测试用户发布文章','2025-04-30','/article/13'),
(11,10,'资源收藏','测试用户发布文章','2025-04-30','/article/13');
/*!40000 ALTER TABLE `collections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `article_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `avatar` varchar(255) DEFAULT '',
  `text` text NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `article_id` (`article_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comments`
--

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
INSERT INTO `comments` VALUES
(1,4,1,'akaky','https://picsum.photos/id/64/100/100','非常好的文章','2025-04-28 21:12:57'),
(2,1,1,'akaky','https://picsum.photos/id/64/100/100','好文章','2025-04-28 21:41:42'),
(3,10,1,'akaky','https://picsum.photos/id/64/100/100','评论测试评论测试','2025-04-29 15:28:18'),
(4,11,1,'akaky','https://picsum.photos/id/64/100/100','很好，值得推荐','2025-04-29 22:27:43'),
(5,11,1,'akaky','https://picsum.photos/id/64/100/100','测试','2025-04-29 22:34:42'),
(20,13,10,'test2','http://localhost:8000/uploads/avatars/avatar_6811c32a3fce94.75241845.jpg','测试','2025-04-30 14:29:41'),
(19,13,9,'fufu','http://localhost:8000/uploads/avatars/avatar_6811bbfb4e0a26.10679658.webp','ceshi ','2025-04-30 13:59:54'),
(14,13,8,'test','http://localhost:8000/uploads/avatars/avatar_6810e039e5f967.54828223.jpg','测试评论','2025-04-30 11:16:22'),
(15,13,8,'test','http://localhost:8000/uploads/avatars/avatar_6810e039e5f967.54828223.jpg','测试评论','2025-04-30 11:16:36'),
(16,13,8,'test','http://localhost:8000/uploads/avatars/avatar_681195f59e9b41.28020917.jpg','测试评论','2025-04-30 11:18:55'),
(17,13,3,'moon','https://picsum.photos/id/40/4106/2806','测试评论','2025-04-30 11:19:17'),
(18,13,8,'test','http://localhost:8000/uploads/avatars/avatar_681195f59e9b41.28020917.jpg','测试','2025-04-30 11:19:51');
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `points_details`
--

DROP TABLE IF EXISTS `points_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `points_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `time` datetime NOT NULL,
  `points` int(11) NOT NULL,
  `type` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=37 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `points_details`
--

LOCK TABLES `points_details` WRITE;
/*!40000 ALTER TABLE `points_details` DISABLE KEYS */;
INSERT INTO `points_details` VALUES
(1,1,'每日签到','2025-04-28 19:56:19',10,'积分'),
(2,1,'兑换知识币','2025-04-28 19:56:19',-100,'积分'),
(3,1,'兑换知识币','2025-04-28 19:56:19',1,'知识币'),
(4,1,'兑换知识币','2025-04-28 20:07:41',-100,'积分'),
(5,1,'兑换知识币','2025-04-28 20:07:41',10,'知识币'),
(6,1,'兑换知识币','2025-04-28 20:07:57',-100,'积分'),
(7,1,'兑换知识币','2025-04-28 20:07:57',10,'知识币'),
(8,1,'兑换知识币','2025-04-28 20:09:34',-100,'积分'),
(9,1,'兑换知识币','2025-04-28 20:09:34',10,'知识币'),
(10,1,'给文章投币','2025-04-28 21:33:32',-1,'知识币'),
(11,1,'给文章投币','2025-04-28 21:34:36',-1,'知识币'),
(12,1,'给文章投币','2025-04-28 21:35:10',-1,'知识币'),
(13,1,'给文章投币','2025-04-28 21:38:20',-1,'知识币'),
(14,1,'给文章投币','2025-04-29 15:07:57',-1,'知识币'),
(15,1,'兑换知识币','2025-04-29 19:50:02',-100,'积分'),
(16,1,'兑换知识币','2025-04-29 19:50:02',10,'知识币'),
(17,1,'兑换知识币','2025-04-29 19:58:23',-10,'积分'),
(18,1,'兑换知识币','2025-04-29 19:58:23',1,'知识币'),
(19,1,'兑换知识币','2025-04-29 19:58:31',-200,'积分'),
(20,1,'兑换知识币','2025-04-29 19:58:31',20,'知识币'),
(21,1,'兑换知识币','2025-04-29 19:58:44',-1000,'积分'),
(22,1,'兑换知识币','2025-04-29 19:58:44',100,'知识币'),
(23,1,'兑换知识币','2025-04-29 19:58:56',-390,'积分'),
(24,1,'兑换知识币','2025-04-29 19:58:56',39,'知识币'),
(25,1,'给文章投币','2025-04-29 19:59:23',-1,'知识币'),
(26,1,'给文章投币','2025-04-29 19:59:26',-1,'知识币'),
(27,1,'给文章投币','2025-04-29 19:59:28',-1,'知识币'),
(28,1,'兑换知识币','2025-04-29 21:25:09',-100,'积分'),
(29,1,'兑换知识币','2025-04-29 21:25:09',10,'知识币'),
(30,8,'兑换知识币','2025-04-29 22:34:05',-10,'积分'),
(31,8,'兑换知识币','2025-04-29 22:34:05',1,'知识币'),
(32,1,'给文章投币','2025-04-29 22:46:25',-1,'知识币'),
(33,8,'给文章投币','2025-04-29 22:49:01',-1,'知识币'),
(34,3,'给文章投币','2025-04-30 14:32:09',-1,'知识币'),
(35,3,'兑换知识币','2025-04-30 14:32:25',-10,'积分'),
(36,3,'兑换知识币','2025-04-30 14:32:25',1,'知识币');
/*!40000 ALTER TABLE `points_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `points_exchange`
--

DROP TABLE IF EXISTS `points_exchange`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `points_exchange` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `coins` int(11) NOT NULL,
  `points` int(11) NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `points_exchange`
--

LOCK TABLES `points_exchange` WRITE;
/*!40000 ALTER TABLE `points_exchange` DISABLE KEYS */;
INSERT INTO `points_exchange` VALUES
(1,1,1,100,'2025-04-28 19:55:32'),
(2,1,10,100,'2025-04-28 20:07:41'),
(3,1,10,100,'2025-04-28 20:07:57'),
(4,1,10,100,'2025-04-28 20:09:34'),
(5,1,10,100,'2025-04-29 19:50:02'),
(6,1,1,10,'2025-04-29 19:58:23'),
(7,1,20,200,'2025-04-29 19:58:31'),
(8,1,100,1000,'2025-04-29 19:58:44'),
(9,1,39,390,'2025-04-29 19:58:56'),
(10,1,10,100,'2025-04-29 21:25:09'),
(11,8,1,10,'2025-04-29 22:34:05'),
(12,3,1,10,'2025-04-30 14:32:25');
/*!40000 ALTER TABLE `points_exchange` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `questions`
--

DROP TABLE IF EXISTS `questions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `content` text NOT NULL,
  `reward` int(11) DEFAULT '0',
  `tags` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `questions`
--

LOCK TABLES `questions` WRITE;
/*!40000 ALTER TABLE `questions` DISABLE KEYS */;
INSERT INTO `questions` VALUES
(1,1,'如何高效学习Vue3？','最近开始学习Vue3，有哪些高效的学习方法或资料推荐？',10,'Vue,前端,学习','2025-04-29 15:56:00','2025-04-29 15:56:00'),
(2,3,'MySQL索引失效的常见原因有哪些？','在实际开发中遇到MySQL索引失效，大家都遇到过哪些情况？',8,'MySQL,数据库,性能','2025-04-29 15:56:00','2025-04-29 15:56:00'),
(3,2,'React和Vue在大项目中的优缺点？','有做过大型项目的同学能分享下React和Vue各自的优缺点吗？',12,'React,前端,框架','2025-04-29 15:56:00','2025-04-29 16:08:46'),
(4,4,'产品经理如何与开发高效沟通？','作为产品经理，怎样能让需求和开发沟通更顺畅？',5,'产品经理,沟通,协作','2025-04-29 15:56:00','2025-04-29 15:56:00'),
(5,6,'Go语言适合哪些场景？','最近公司在推Go，有哪些业务场景特别适合用Go语言实现？',7,'Go,后端,语言选择','2025-04-29 15:56:00','2025-04-29 15:56:00'),
(6,1,'问题发布测试功能','测试测试测试，测试问题发布功能',2,'','2025-04-29 16:13:49','2025-04-29 16:13:49'),
(7,1,'测试用户发布问题','用户每个页面session不一致如何解决',1,'','2025-04-29 22:55:15','2025-04-29 22:55:15'),
(8,8,'测试用户发布问题','测试问题发布seession',1,'','2025-04-29 22:58:45','2025-04-29 22:58:45'),
(9,9,'1212测试测试','测试测试问题发布',1,'','2025-04-30 14:02:07','2025-04-30 14:02:07'),
(10,10,'测试问题','测试测试',3,'','2025-04-30 14:30:52','2025-04-30 14:30:52');
/*!40000 ALTER TABLE `questions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_checkin_days`
--

DROP TABLE IF EXISTS `user_checkin_days`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_checkin_days` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `checkin_date` date NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_user_date` (`user_id`,`checkin_date`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_checkin_days`
--

LOCK TABLES `user_checkin_days` WRITE;
/*!40000 ALTER TABLE `user_checkin_days` DISABLE KEYS */;
INSERT INTO `user_checkin_days` VALUES
(1,1,'2025-04-29','2025-04-29 16:52:54'),
(2,1,'2025-04-25','2025-04-29 19:26:04'),
(3,1,'2025-04-26','2025-04-29 19:26:04'),
(4,1,'2025-04-27','2025-04-29 19:26:04'),
(5,1,'2025-04-28','2025-04-29 19:26:04'),
(6,2,'2025-04-27','2025-04-29 19:27:11'),
(7,2,'2025-04-28','2025-04-29 19:27:11'),
(8,2,'2025-04-29','2025-04-29 19:27:11'),
(9,3,'2025-04-20','2025-04-29 19:27:50'),
(10,3,'2025-04-21','2025-04-29 19:27:50'),
(11,3,'2025-04-29','2025-04-29 19:27:50'),
(12,4,'2025-04-29','2025-04-29 19:28:00'),
(13,8,'2025-04-29','2025-04-29 23:11:26'),
(14,9,'2025-04-30','2025-04-30 14:03:08'),
(15,10,'2025-04-30','2025-04-30 14:31:24');
/*!40000 ALTER TABLE `user_checkin_days` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_tasks`
--

DROP TABLE IF EXISTS `user_tasks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_tasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_tasks`
--

LOCK TABLES `user_tasks` WRITE;
/*!40000 ALTER TABLE `user_tasks` DISABLE KEYS */;
INSERT INTO `user_tasks` VALUES
(1,1,'默认任务','/src/static/images/默认打卡.png','2025-04-29 16:25:50'),
(2,1,'阅读《活着》','/src/static/images/阅读.png','2025-04-29 16:25:50'),
(3,1,'python编程学习','/src/static/images/python.png','2025-04-29 16:25:50'),
(4,1,'靶场搭建','/src/static/images/靶场搭建.png','2025-04-29 16:25:50'),
(6,1,'测试新建任务','/src/static/images/python.png','2025-04-29 16:42:09'),
(7,1,'测试任务','/src/static/images/默认打卡.png','2025-04-29 22:59:33'),
(8,8,'测试任务','/src/static/images/默认打卡.png','2025-04-29 23:03:08'),
(9,8,'测试任务','/src/static/images/python.png','2025-04-29 23:03:30'),
(10,9,'新建任务测试','/src/static/images/python.png','2025-04-30 14:03:06'),
(11,10,'测试任务','/src/static/images/python.png','2025-04-30 14:31:21');
/*!40000 ALTER TABLE `user_tasks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL DEFAULT '',
  `nickname` varchar(50) NOT NULL DEFAULT '',
  `gender` enum('male','female','unknown') NOT NULL DEFAULT 'unknown',
  `signature` varchar(255) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `wechat` varchar(50) DEFAULT NULL,
  `qq` varchar(20) DEFAULT NULL,
  `title` varchar(100) DEFAULT '',
  `avatar` varchar(255) DEFAULT '',
  `coins` int(11) DEFAULT '0',
  `points` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES
(1,'akaky','$2y$10$PuvhuNVBdXyO8wODikPGNuQ4Z7iygntKTaEh2IbhrAujc6y5oQCHK','moonbeaut','male','认真学习中','2004-08-03','13512341234','ttovlove','2359389098','知识达人 Lv.1','http://localhost:8000/uploads/avatars/avatar_6810cc98a431a3.35489205.jpg',199,2100),
(2,'新手小白','$2y$10$KgDLOQ2WeAzUCy68IzOkVOLKdsy8NgAwhEXD2e9XjSY/43d6Lv2tC','新手小白','unknown',NULL,NULL,NULL,NULL,NULL,'前端开发工程师','https://picsum.photos/id/65/4912/3264',100,200),
(3,'moon','$2y$10$XfyqZh4AaKotiZK56zdDLuU385mUObmkboo9/Qk3LWXE1rCzG3I06','moon','unknown',NULL,NULL,NULL,NULL,NULL,'后端开发工程师','https://picsum.photos/id/40/4106/2806',80,140),
(4,'wanan','$2y$10$wDYtT5GI5uv5ezVUJ6y2JuQ7WSXoOChFSpSOpuTJLpicca8Wd9Ohi','wanan','unknown',NULL,NULL,NULL,NULL,NULL,'全栈工程师','https://picsum.photos/id/152/3888/2592',60,120),
(5,'小红','$2y$10$s2fuT9jnINCUPknOFh5cm.vW2tfJQt4sh4Q9GUyWL.aPvYBCpbOT2','小红','unknown',NULL,NULL,NULL,NULL,NULL,'UI设计师','https://randomuser.me/api/portraits/women/23.jpg',50,90),
(6,'小明','$2y$10$D3ZyI.LjkaDna99DN7.GfeZKtS9wCAv30VWj6EH2ZNHUFcXiP.7xG','小明','unknown',NULL,NULL,NULL,NULL,NULL,'产品经理','https://randomuser.me/api/portraits/men/12.jpg',40,70),
(7,'testuser','$2y$10$Tmmqulec/mGXHvzQhdy/r.ha3d2pkZYP4RA6B0qh7iPTNnr9Zvsfu','aa_test','unknown',NULL,NULL,NULL,NULL,NULL,'','https://picsum.photos/id/140/2448/2448',0,0),
(8,'test','$2y$10$7EKzuGFbs1UIzebPoJ25h.6vvQO/AvE/jBhjpwp6lJBAXm4rNNNGG','testuser','female','测试用户',NULL,NULL,NULL,NULL,'','http://localhost:8000/uploads/avatars/avatar_681195f59e9b41.28020917.jpg',10,90),
(9,'fufu','$2y$10$xxIgFs9iPEURcTAagtSt3.c7PSBBEPGY8mjbzZJA8zdW/Ea32WbeC','fufu','unknown',NULL,NULL,NULL,NULL,NULL,'','http://localhost:8000/uploads/avatars/avatar_6811bbfb4e0a26.10679658.webp',0,0),
(10,'test2','$2y$10$Jlpx2lsmNe8ZM4Xod8N.3eWqeVSL4sqZD9fwkuahWzwiZ2hz7fYDq','testuser2','unknown',NULL,NULL,NULL,NULL,NULL,'','http://localhost:8000/uploads/avatars/avatar_6811c32a3fce94.75241845.jpg',0,0),
(11,'hashtest','$2y$10$9eQLgx2Y/wJl.bdh274mz./rYGW2GFE4or8I9ouY2YgSgyZgvaLLi','hash','unknown',NULL,NULL,NULL,NULL,NULL,'','',0,0);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'knowledge_sharing_platform'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2025-05-02 19:33:59
