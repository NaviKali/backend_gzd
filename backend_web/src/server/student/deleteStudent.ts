import { CreateWebUrl, type BaseResponse, sendAxios } from '~/server/index'


export namespace deleteStudent {

    export const url: string = CreateWebUrl("Student.Student/deleteStudent")

    export type params = {
        student_guid:string|String,
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