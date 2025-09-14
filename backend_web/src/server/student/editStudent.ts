import { CreateWebUrl, type BaseResponse, sendAxios } from '~/server/index'


export namespace editStudent {

    export const url: string = CreateWebUrl("Student.Student/editStudent")

    export type params = {
        student_guid:string|String
        student_number:string|String,
        student_name:string|String,
        student_sex:number|Number,
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