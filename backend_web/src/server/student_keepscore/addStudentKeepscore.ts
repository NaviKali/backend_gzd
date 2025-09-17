import { CreateWebUrl, type BaseResponse, sendAxios } from '~/server/index'


export namespace addStudentKeepscore {

    export const url: string = CreateWebUrl("StudentKeepscore.StudentKeepscore/addStudentKeepscore")

    export type params = {
        student_number: string | String
        student_keepscore_type_guid: string | String
        student_keepscore_date:string|String
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