import { CreateWebUrl, type BaseResponse, sendAxios } from '~/server/index'


export namespace getCurrentUserInformation {

    export const url: string = CreateWebUrl("User.User/getCurrentUserInformation")

    export type params = {
    }

    export type Data = {
        user_id: number,
        user_guid: string,
        user_name: string,
        user_sex: number,
        user_role_guid: string | null,
        user_image: string | null,
        user_phone: string | null,
        user_email: string | null,
        user_status_guid: string,
        user_information: string | null,
        create_datetime: string,
        update_datetime: string,
        delete_datetime: string,
        user_role_id: number,
        user_role_name: string,
        user_status_id: number,
        user_status_name: string,
        getUserConfig: {
            user_config_id: number,
            user_config_guid: string,
            user_guid: string,
            user_config_menu_collapsed: number,
            user_config_notification_position: string,
            user_config_footnote: number,
            user_config_watermark: number,
            create_datetime: string,
            update_datetime: string,
            delete_datetime: string
        }
    }

    export type returnResponse = BaseResponse & {
        data: Data,
    }

    export const fetch = async (params: params): Promise<void> => {
        return sendAxios(url, params, {
            isMessageWarning: true,
            isMessageError: true,
            isMessageSuccess: false,
        })
    }
}