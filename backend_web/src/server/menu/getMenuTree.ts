import { reactive } from 'vue'
import { CreateWebUrl, type BaseResponse, sendAxios } from '~/server/index'


export namespace getMenuTree {

    export const url: string = CreateWebUrl("Menu/getMenuTree")

    export type params = {
        open?: number
    }

    export type Data = {
        menu_guid: string | String,
        menu_name: string | String,
        menu_to: string | String,
        menu_icon: string | String,
        menu_father_guid: string | String | null,
        children?: Data[],
    }

    export type returnResponse = BaseResponse & {
        data: Data[]
    }

    export const OPEN_GETONELEAVEL: number = 1;
    export const OPEN_ALL: number = 2;
    export const OPEN_GETSONLEVEL:number = 3;

    export const open = reactive({
        OPEN_GETONELEAVEL: "获取一级菜单"
    })

    export const fetch = async (params: params): Promise<void> => {
        return sendAxios(url, params, {
            isMessageWarning: true,
            isMessageError: true,
            isMessageSuccess: false,
        })
    }
}