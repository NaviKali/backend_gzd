import { CreateWebUrl, type BaseResponse, sendAxios } from '~/server/index'


export namespace CreateMenu {

    export const url: string = CreateWebUrl("Menu/CreateMenu")

    export type params = {
        menu_name: string | String,
        menu_to: string | String,
        menu_icon: string | String,
        menu_father_guid?: string | String
    }

    export type Data = {
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