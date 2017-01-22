### 1.技术对比。
- 传统bootstrap等UI库，结合jquery，通过后端模板渲染。(相对落后)
- vue,react等组件化框架（主流趋势）

### 2. react
##### 相对完善的UI库：
- material UI,amazeui UI,ant design,bootstrap react.学习门槛较高，生态圈完善.

##### bootstrap react: 
- 如果作为wap版开发，不建议使用。操作交互不够友好,未知BUG

##### amazeui UI react 版:
- 界面有点丑,star 1K左右，社区不是很活跃

##### ant design:
- 阿里巴巴蚂蚁金服的UI库，中文文档，但测试demo时样式有问题

##### material UI:
- 20K+ star,基于google material风格编写的react UI组件库。
- 问题: 从0.8版本开始，所有组件css in js，采取内联方式进行开发.也是就说我们要用只能用0.7.5版本的。css in js还不适合小型公司，抛弃了sass less,返回到了原始内联

### 3. vue
- 易上手,中文文档,可是ui库相对较少.开发效率会比较快,入门简单
- Muse-UI:material UI 的vue实现，中文文档 (个人写的,感觉会有坑)
- element: 饿了么团队基于vue实现的UI库
- vue-material:Material design for Vue.js

### 4. 解决方案
- react UI库 + (redux + router 可能部分页面要用到)
- vue UI库 + (vuex + vue router可能部分页面要用到)
- 自建UI库

### 5. 构建工具 webpack + gulp
### 6. 前后端配合
- 1. 依旧采取php+twig模板，只是渲染全部交由前端。(后端写一个规则，前端可以自由扩展路由与模板)
- 2. 后端模板统一跳转到一个twig文件，单一入口，前端通过url去抓取数据渲染页面.（每个不同路由js,css的映射做不了）
