import { CreateWebUrl, type BaseResponse, sendAxios } from '~/server/index'


export namespace setUserConfig {

    export const url: string = CreateWebUrl("User.UserConfig/setUserConfig")

    export type params = {
        user_config_menu_collapsed: number,
        user_config_notification_position: string,
        user_config_footnote: number,
        user_config_watermark: number,
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