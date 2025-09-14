import axios from "axios";
import { AccountLogin } from '~/server/login/AccountLogin'
import { CODE_LOGINOUT } from '~/server/index'
import { message } from 'ant-design-vue';

const service = axios.create({
    headers: { 'Content-Type': 'application/json;charset=utf-8', },
    timeout: 5000, // 设置请求超时时间
});



// 请求拦截器
service.interceptors.request.use(
    (config:any) => {


        // 在请求发送之前做一些处理，例如添加 token 等
        let url: string = config.url!

        if (url.includes(AccountLogin.LoginInterface)) {

        } else {
            /**
             * 获取Token
            */
            const token: string = sessionStorage.getItem("token")!
            console.log(`token:${token}`);
            if (!token) window.location.href = "/login" //!没有token回首页

            config.headers = {
                token: token
            }
        }

        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

// 响应拦截器
service.interceptors.response.use(
    (response) => {
        // 在响应数据返回之前做一些处理

        //!登录超时
        if (response.data.code == CODE_LOGINOUT) {
            message.error(response.data.message)
            setTimeout(() => {
                window.location.href = "/login"
            }, 800);
        }

        return response.data;
    },
    (error) => {
        return Promise.reject(error);
    }
);

export default service;
