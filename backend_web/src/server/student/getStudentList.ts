import { CreateWebUrl, type BaseResponse, sendAxios } from '~/server/index'


export namespace getStudentList {

    export const url: string = CreateWebUrl("Student.Student/getStudentList")

    export type params = {
    }

    export type Data = {
        key:number,
        student_guid: string,
        student_number: string,
        student_name: string,
        student_sex: {
            value: number,
            text: string,
        },
        student_scroe_count: number,
    }

    export type returnResponse = BaseResponse & {
        data: {
            list: Data[],
            count: number
        }
    }

    export const fetch = async (params: params = {}): Promise<void> => {
        return sendAxios(url, params, {
            isMessageWarning: true,
            isMessageError: true,
            isMessageSuccess: false,
        })
    }
}