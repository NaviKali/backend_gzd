import { createApp, onMounted, provide, type Component } from 'vue'
import './style.css'
import App from './App.vue'
import router from './router'
import 'ant-design-vue/dist/reset.css';
import Antd from 'ant-design-vue';
import Icon, * as icons from '@ant-design/icons-vue';


const app = createApp(App)


const IconData: Record<string, Component> = icons
Object.keys(IconData).forEach((key: string) => {
    app.component(key, IconData[key])
})

app.config.globalProperties.$antIcons = IconData
/**
 * *Use
 */
app.use(Antd)
app.use(router)
/**
 * *Mount
 */
app.mount('#app')