import service from '~/utils/request.ts'
import { message } from 'ant-design-vue';



export const CODE_SUCCESS = 200
export const CODE_WARNING = 404
export const CODE_ERROR = 444
export const CODE_LOGINOUT = 300

/**
 * 创建Web接口路径
 * @param url:string 后缀路径
 * @returns string
 */
export const CreateWebUrl = (url: string): string => {
    return `/api/${url}`
}

/**
 * 获取文件上传路径
 * @param url:string 后缀路径5
 * @returns
 */
export const getUploadsUrl = (url: string): string => {
    return import.meta.env.VITE_UPLOADS_URL + url
}

export type BaseResponse = {
    code: number,
    message: string,
    datetime: string,
    options: {
        php: string | String,
        function: string | String,
        workdir: string | String,
        user: string | String,
        ip: string | String,
    }
}
export type sendAxiosConfigType = {
    isMessageWarning: boolean,
    isMessageError: boolean,
    isMessageSuccess: boolean
}

export const isRequestSuccess = (res: BaseResponse & any): boolean => {
    return res.code == CODE_SUCCESS ? true : false
}

export const sendAxios = (url: string, params: any, config: sendAxiosConfigType = {
    isMessageWarning: false,
    isMessageError: true,
    isMessageSuccess: false,
}): Promise<any> => {
    return service.post(url, params).then((res: any) => {
        //*Show Warning Message
        config.isMessageWarning ? (() => {
            if (res.code == CODE_WARNING)
                message.warning(res.message)
        })() : null
        //*Show Error Message
        config.isMessageError ? (() => {
            if (res.code == CODE_ERROR)
                message.error(res.message)
        })() : null
        //*Show Success Message
        config.isMessageSuccess ? (() => {
            if (res.code == CODE_SUCCESS)
                message.success(res.message)
        })() : null

        return res
    })

}