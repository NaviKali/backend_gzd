import { CreateWebUrl, type BaseResponse, sendAxios } from '~/server/index'


export namespace getQuickEntrance {

    export const url: string = CreateWebUrl("QuickEntrance.QuickEntrance/getQuickEntrance")

    export type params = {
    }

    export type Data = {
        quick_entrance_guid	: string | String,
        menu_name: string | String,
        menu_to: string | String,
        menu_icon: string | String
    }

    export type returnResponse = BaseResponse & {
        data: {
            list: Data[],
            count: number
        }
    }

    export const fetch = async (params: params): Promise<void> => {
        return sendAxios(url, params, {
            isMessageWarning: true,
            isMessageError: true,
            isMessageSuccess: false,
        })
    }
}