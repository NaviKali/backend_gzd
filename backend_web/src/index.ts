import { provide } from "vue"

type defineProvideArrType = {
    name: string,
    define: any
}


/**
 * 批量依赖注入
 * 
 * @param arr 
 * @function
 * @version 1.0
 */
export const defineProvideArr = (arr: defineProvideArrType[]): void => {
    arr.forEach((item: defineProvideArrType) => {
        provide(item.name, item.define)
    })
}