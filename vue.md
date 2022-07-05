# [库题1](http://www.h-camel.com/category.html)

## Vue篇
[路由](https://www.jianshu.com/p/d844f004ea40)
1. vue的特点
``` 
优点：
- SPA，单页面应用，加载速率快
- 组件化，根据需要添加功能
- 强大丰富的API
- 生命周期钩子函数，选项式的代码组织方式
- 数据驱动视图，对真实DOM抽象出虚拟DOM，配合Diff算法、响应式和观察者、异步队列等手段以最小代价更新DOM

缺点：
- 不利于SEO（Search Engine Optimization）
- CSR（Client side render）性能问题，首屏加载时间较长
- 不支持IE8及以下浏览器

优化：
- CDN提速
- 压缩请求
- 延迟加载、SPA组件懒加载

```

2. 双向绑定
``` 
解释：
- v-bind:单向绑定，数据只能从data流向页面
- v-model:双向绑定，数据不仅能从data流向页面，也能从页面流向data
原理：

- 监听器Observer，用来监听所有属性，有变动，就通知订阅制
- 订阅者Watcher，每一个Watcher都绑定一个更新函数，watcher收到变化通知后执行更新函数，从而更新视图
- 解析器Compile，扫描和解析每个节点的相关指令，初始化模板数据及初始化订阅者
```

3. 组件通信
```
父子通信
- props
- $emit
- $parents

兄弟组件通信
- 父组件作为中继
- vuex
- event bus

跨组件通信
- $attrs/$listeners
- provide/inject
```

4. v-show 和 v-if的区别
```
- v-if动态的向DOM树内添加或者删除DOM元素；v-show通过DOM元素的display样式属性控制显隐
- v-if的切换开销更高，但渲染效果更加理想；v-show的切换开销小，可能受DOM元素本身的display样式属性影响
```

5. 自定义指令和钩子函数
```vue

<script>
//注册一个全局自定义指令
Vue.directive('focus', {
  //每当指令绑定到元素上时，会立即执行这个bind函数，只执行一次
  bind: function () {

  },
  //inserted表示元素插入到DOM中时，会执行inserted函数，只触发一次，el表示被绑定的那个原生js对象
  inserted: function (el) {
    el.focus()
  },
  //当VNode更新时会执行updated，可能触发多次
  updated:function(){

  }
})

var vm = new Vue({
  el: '#app',
  data: { },
  directives: {
  'color': { //指令名要加上引号
    bind: function (el, binding) {
      el.style.color = binding.value
    }
  }
}
})
</script>
```

6. 怎么使css样式只在当前组件中生效
```vue
<style scoped> </style>
```

7. 怎么在vue中使用插件
```  
npm安装插件包，导入到main.js，最后vue.use使用
```

8. v-for循环中key有什么用
```
key唯一标识，防止复用DOM，使diff算法更加高效
```

9. vue如何监听键盘事件

```
v-on:keyup.键盘事件修饰符
```

10. watch和computed的区别
```
watch适合监控，computed适合计算
```

11. watch如何深度监听对象变化

```vue
watch: {
'cityName.name': {
handler(newName, oldName) {
// ...
},
deep: true,//深度调用
immediate: true//立即调用
}
}
```

12. 为什么data属性必须声明为返回一个初始数据对应的函数呢

```
对象为引用类型，当重用组件时，由于数据对象都指向同一个data对象，当在一个组件中修改data时，其他重用的组件中的data会同时被修改；而使用返回对象的函数，由于每次返回的都是一个新对象（Object的实例），引用地址不同，则不会出现这个问题。
```
13. $nextTick有什么作用

``` 
vue响应式的改变一个值以后，此时的dom并不会立即更新，如果需要在数据改变以后立即通过dom做一些操作，可以使用$nextTick获得更新后的dom。
```

14. 分别说说vue能监听到数组或对象变化的场景，还有哪些场景是监听不到的？无法监听时有什么解决方案？

``` 
对于对象：
Vue 无法检测 property 的添加或移除，解决方案
Vue.set(vm.someObject, 'b', 2)
对于数组：
当你利用索引直接设置一个数组项时或者修改数组的长度时，Vue无法检测，解决方案
Vue.set(vm.items, indexOfItem, newValue)
vm.items.splice(newLength)
```

15. v-if和v-for的优先级是什么？如果这两个同时出现时，那应该怎么优化才能得到更好的性能？

``` 
v-if在前
```

16. axios是什么

``` 
异步请求
```

17. 如何中断axios的请求？

``` 
- AbortController

const controller = new AbortController();

axios.get('/foo/bar', {
   signal: controller.signal
}).then(function(response) {
   //...
});
// cancel the request
controller.abort()

- CancelToken 

const CancelToken = axios.CancelToken;
const source = CancelToken.source();

axios.get('/user/12345', {
  cancelToken: source.token
}).catch(function (thrown) {
  if (axios.isCancel(thrown)) {
    console.log('Request canceled', thrown.message);
  } else {
    // handle error
  }
});
// cancel the request (the message parameter is optional)
source.cancel('Operation canceled by the user.');
```

18. 你有封装过axios吗？主要是封装哪方面的？

``` 
统一管理接口（超时处理、错误处理、token）
```

19. 如何引入scss？引入后如何使用

``` 
- 安装包
- 配置loader
- 添加lang属性，，即<style lang="scss">。
```

20. 常用的指令

``` 
v-if v-show v-for v-on v-model v-bind
```

21. 手写过滤器

``` 
全局过滤器
Vue.filter('addHobby',(val,hobby)=>{
return val + hobby
})
局部过滤器
filters:{
addHobby(val,hobby){
return val + hobby
}
}
```

22. 在vue项目中如何引入第三方库（比如jQuery）？有哪些方法可以做到？

``` 
配置webpack
```

23. vue在开发过程中要同时跟N个不同的后端人员联调接口（请求的url不一样）时你该怎么办

``` 
devServer中把所有的服务人员的地址代理都写进去，然后动态更改接口的baseUrl，这样切换不同后端人员的时候不用重启
```

24. vue怎么获取DOM节点？

```
 <input ref="myInput" type="text" value="hello world">
  
this.$refs.xx
```

25. vue在created和mounted这两个生命周期中请求数据有什么区别呢？

``` 
created DOM还未加载完
mounted DOM已加载完，页面已完成渲染
```

26. vue部署上线前需要做哪些准备工作

``` 
是否需要配置nginx，publicPath，是不是需要配置cdn
```

27. vue-cli默认是单页面的，那要弄成多页面该怎么办呢

``` 
在 multi-page 模式下构建应用。每个“page”应该有一个对应的 JavaScript 入口文件
```

28. vue-router是用来做什么的？它有哪些组件？

``` 
让SPA像多页面应用一样实现跳转

router-view router-link
```

29. vue-router钩子函数有哪些？都有哪些参数
>
>- 导航被触发。
>- 在失活的组件里调用beforeRouteLeave守卫。
>- 调用全局的beforeEach守卫。 
>- 在重用的组件里调用beforeRouteUpdate守卫 (2.2+)。 
>- 在路由配置里调用beforeEnter。 
>- 解析异步路由组件。 
>- 在被激活的组件里调用beforeRouteEnter。 调用全局的beforeResolve守卫(2.5+)。 
>- 导航被确认。 调用全局的afterEach钩子。 
>- 触发DOM更新。 
>- 调用beforeRouteEnter守卫中传给next的回调函数，创建好的组件实例会作为回调函数的参数传入。


30. 如果vue-router使用history模式，部署时要注意什么？

>vue应用为SPA，hash模式下，改变hash值，网络请求地址不会变，刷新url或者改变url不会出现404问题。
history模式，如果刷新url或改变url，网络请求的地址不存在，因为vue应用实际只有一个html，找不到会出现404错误。
解决办法，将所有的网络请求全部指向根页面，就不会出现404了

31. 路由之间是怎么跳转的？有哪些方式？
>- 组件跳转：router-link
>- 编程导航：router.push router.go router.replace 

32. 怎么实现路由懒加载呢？
>- es6的import方法：()=>import()
>- vue的异步组件技术：component: resolve => require(['放入需要加载的路由地址'], resolve)

33. 怎样动态加载路由
> router.addRoute

34. 在vue组件中怎么获取到当前的路由信息
> this.$route.path

35. 如何获取路由传过来的参数
> this.$route.params

36. 切换到新路由时，页面要滚动到顶部或保持原先的滚动位置怎么做呢？

```
//在路由实例中配置
scrollBehavior(ro,form,savedPosition){
//滚动到顶部
return {x:0,y:0}
//保持原先的滚动位置
return {selector：falsy}
}
```

37. vue-router如何响应路由参数的变化

> watch

38. 有用过vuex吗？它主要解决的是什么问题？推荐在哪些场景用？
> 全局变量，登录人信息，token，浏览记录，跨组件数据传递

39. vuex的store有几个属性值？分别讲讲它们的作用是什么？
>- state：存放公共数据的地方 
>- getter：获取根据业务场景处理返回的数据 
>- mutations：唯一修改state的方法，修改过程是同步的 
>- action：异步处理，通过分发操作触发mutation 
>- module：将store模块分割，减少代码臃肿

40. 页面刷新后vuex的state数据丢失怎么解决
> sessionStorage

41. 怎么监听vuex数据的变化？
> watch computed

42. 请求数据是写在组件的methods中还是在vuex的action中
> 如果请求的数据是多个组件共享的，为了方便只写一份，就写vuex里面，如果是组件独用的就写在当前组件里面

43. ElementUI表格组件如何实现动态表头
> el-table-column

44. ElementUI怎么修改组件的默认样式？
> 自定义主题

45. ElementUI是怎么做表单验证的？在循环里对每个input验证怎么做呢？
> this.$ref.ruleForm.validate

46. 