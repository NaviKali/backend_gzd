import { CreateWebUrl, type BaseResponse, sendAxios } from '~/server/index'


export namespace getStudentKeepscoreTypeList {

    export const url: string = CreateWebUrl("StudentKeepscore.StudentKeepscoreType.StudentKeepscoreType/getStudentKeepscoreTypeList")

    export type params = {
    }

    export type Data = {
        key:string|String,
        student_keepscore_type_guid:string|String,
        student_keepscore_type_name:string|String,
        keepscore_num:number|Number,
        create_datetime:string|String
    }

    export type returnResponse = BaseResponse & {
        data: Data
    }

    export const fetch = async (params: params): Promise<void> => {
        return sendAxios(url, params, {
            isMessageWarning: true,
            isMessageError: true,
            isMessageSuccess: false,
        })
    }
}