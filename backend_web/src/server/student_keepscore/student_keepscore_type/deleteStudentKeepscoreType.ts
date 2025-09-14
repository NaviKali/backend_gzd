import { CreateWebUrl, type BaseResponse, sendAxios } from '~/server/index'


export namespace deleteStudentKeepscoreType {

    export const url: string = CreateWebUrl("StudentKeepscore.StudentKeepscoreType.StudentKeepscoreType/deleteStudentKeepscoreType")

    export type params = {
        student_keepscore_type_guid:string|String
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