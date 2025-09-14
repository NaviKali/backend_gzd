import { useRoute, useRouter } from 'vue-router'
import { message } from 'ant-design-vue';


export type RouteHashType = string | string[] | null | undefined

export class RouteHash {
    errChat: string = 'Hash路由寻找失败，为了防止其他外人，我们将返回登录页!'
    /**
     * 开启Hash路由监听
     */
    listen(): void {
        const route = useRoute()
        const router = useRouter()

        //!没有定义routeHash切换登录页
        if (!route.query.routeHash) {
            message.error(this.errChat);
            router.push("/login")
        }
    }
    /**
     *Hash路由跳转 
     *todo 用来Menu菜单跳转[根据Hash值来识别跳转对应页面]
     */
    HashTo(url: string | String): void {
        
    }
}