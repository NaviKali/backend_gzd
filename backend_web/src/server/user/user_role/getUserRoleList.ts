import { CreateWebUrl, type BaseResponse, sendAxios } from '~/server/index'


export namespace getUserRoleList {

    export const url: string = CreateWebUrl("User.UserRole/getUserRoleList")

    export type params = {
        user_role_name:string|String,
    }

    export type Data = {
        user_role_guid:string|String,
        user_role_name:string|String,
        bind_user_list:string[],
        create_datetime:string|String,
    }

    export type returnResponse = BaseResponse & {
        data: {
            list:Data[],
            count:number
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