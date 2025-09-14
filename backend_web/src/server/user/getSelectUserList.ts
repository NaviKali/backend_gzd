import { CreateWebUrl, type BaseResponse, sendAxios } from '~/server/index'


export namespace getSelectUserList {

    export const url: string = CreateWebUrl("User.User/getSelectUserList")

    export type params = {
        user_name?:string|String
    }

    export type Data = {
        user_guid:string|String,
        user_name:string|String,
}

export type returnResponse = BaseResponse & {
    data: Data[]
}

export const fetch = async (params: params): Promise<void> => {
    return sendAxios(url, params, {
        isMessageWarning: true,
        isMessageError: true,
        isMessageSuccess: false,
    })
}
}