import { CreateWebUrl, type BaseResponse, sendAxios } from '~/server/index'


export namespace addQuickEntrance {

    export const url: string = CreateWebUrl("QuickEntrance.QuickEntrance/addQuickEntrance")

    export type params = {
        menu_guid:string
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