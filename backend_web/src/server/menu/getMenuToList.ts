import { reactive } from 'vue'
import { CreateWebUrl, type BaseResponse, sendAxios } from '~/server/index'


export namespace getMenuToList {

    export const url: string = CreateWebUrl("Menu/getMenuToList")

    export type params = {
    }

    export type Data = {
    }

    export type returnResponse = BaseResponse & {
        data: string[]
    }

    export const fetch = async (params: params): Promise<void> => {
        return sendAxios(url, params, {
            isMessageWarning: true,
            isMessageError: true,
            isMessageSuccess: false,
        })
    }
}