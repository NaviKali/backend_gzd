import { CreateWebUrl, type BaseResponse, sendAxios } from '~/server/index'


export namespace getStudentKeepscoreList {

    export const url: string = CreateWebUrl("StudentKeepscore.StudentKeepscore/getStudentKeepscoreList")

    export type params = {
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
            isMessageSuccess: false,
        })
    }
}