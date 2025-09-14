import { CreateWebUrl, type BaseResponse, sendAxios } from '~/server/index'


export namespace CreateUserRole {

    export const url: string = CreateWebUrl("User.UserRole/CreateUserRole")

    export type params = {
        user_role_name: string|String
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