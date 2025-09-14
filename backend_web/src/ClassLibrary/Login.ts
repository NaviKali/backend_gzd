import { AccountLogin } from '~/server/login/AccountLogin'

export const NOT_IS_LOGIN_PAGE: number = 0
export const IS_LOGIN_PAGE: number = 1

export class Login {
    /**
     * 清除缓存
     * 
     * @return void
     */
    clear(): void {
        localStorage.clear()
        sessionStorage.clear()
    }
    /**
     * 当前页面是否为Login
     * 
     * @return nubmer
     */
    isCurrentLoginPage(): number {
        let url = window.location.pathname
        return url.replace("/", "").trim() == 'login' ? IS_LOGIN_PAGE : NOT_IS_LOGIN_PAGE
    }
    /**
     * 登录成功
     * 
     * @returns void
     */
    LoginSuccess(data: AccountLogin.Data): void {
        sessionStorage.setItem("token", data.token)
    }
}