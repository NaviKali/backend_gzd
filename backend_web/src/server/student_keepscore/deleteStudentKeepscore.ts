import { CreateWebUrl, type BaseResponse, sendAxios } from '~/server/index'


export namespace deleteStudentKeepscore {

    export const url: string = CreateWebUrl("StudentKeepscore.StudentKeepscore/deleteStudentKeepscore")

    export type params = {
        student_keepscore_guid: string[] | String[]
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