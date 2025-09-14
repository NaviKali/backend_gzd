import { CreateWebUrl, type BaseResponse, sendAxios } from '~/server/index'


export namespace editStudentKeepscoreType {

    export const url: string = CreateWebUrl("StudentKeepscore.StudentKeepscoreType.StudentKeepscoreType/editStudentKeepscoreType")

    export type params = {
        student_keepscore_type_guid:string|String
        student_keepscore_type_name:string|String
        keepscore_num:number|Number
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