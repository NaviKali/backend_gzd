import { CreateWebUrl, type BaseResponse, sendAxios } from '~/server/index'


export namespace getStudentKeepscoreTypeList {

    export const url: string = CreateWebUrl("StudentKeepscore.StudentKeepscoreType.StudentKeepscoreType/getStudentKeepscoreTypeList")

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