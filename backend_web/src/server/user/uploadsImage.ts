import { CreateWebUrl, type BaseResponse, sendAxios } from '~/server/index'


export namespace uploadsImage {

    export const url: string = CreateWebUrl("User.User/uploadsImage")

    export type params = {
        file?:string|String,
        fileName?:string|String,
    }

    export type Data = {
        fileName:string|String,
        url:string|String
    }

    export type returnResponse = BaseResponse & {
        data: Data
    }

    export const fetch = async (params: params): Promise<void> => {
        return sendAxios(url, params, {
            isMessageWarning: true,
            isMessageError: true,
            isMessageSuccess: true,
        })
    }
}