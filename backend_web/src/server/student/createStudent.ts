import { CreateWebUrl, type BaseResponse, sendAxios } from '~/server/index'


export namespace createStudent {

    export const url: string = CreateWebUrl("Student.Student/createStudent")


    export type params = {
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