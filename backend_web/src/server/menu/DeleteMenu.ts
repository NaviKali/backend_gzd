import { CreateWebUrl, type BaseResponse, sendAxios } from '~/server/index'


export namespace DeleteMenu {

    export const url: string = CreateWebUrl("Menu/DeleteMenu")

    export type params = {
        menu_guid: string[] | String[],
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