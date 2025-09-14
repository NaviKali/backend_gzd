import { CreateWebUrl, type BaseResponse, sendAxios } from '~/server/index'


export namespace AccountLogin {

    export const LoginInterface:string = "AccountLogin";

    export const url: string = CreateWebUrl("Login.Login/AccountLogin")

    export type params = {
        account: string | String,
        password: string | String,
        vcode: string | String,
    }

    export type Data = {
        user_guid: string | String,
        token: string | String,
        login_status: string | String,
    }

    export type returnResponse = BaseResponse & {
        data: Data
    }

    export const fetch = async (params: params): Promise<void> => {
        return sendAxios(url, params, {
            isMessageWarning: true,
            isMessageError:true,
            isMessageSuccess:true,
        })
    }
}