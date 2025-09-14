import { CreateWebUrl, type BaseResponse, sendAxios } from '~/server/index'


export namespace getUserList {

    export const url: string = CreateWebUrl("User.User/getUserList")

    export type params = {
        user_name?: string | String
    }

    export type Data = {
        user_guid:string|String,
        user_name:string|String,
        user_sex:{
            value:number,
            text:string|String
        },
        user_role_guid:string|String,
        user_image:string|String,
        user_phone:string|String,
        user_email:string|String,
        user_status_guid:string|String,
        user_information:string|String,
        create_datetime:string|String
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