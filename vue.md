# [库题1](http://www.h-camel.com/category.html)

## Vue篇
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