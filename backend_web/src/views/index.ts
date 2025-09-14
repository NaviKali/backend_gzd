export type changeIsLoginPageType = (is: boolean) => {}
export type subjectInfoType = {
    name: string
}
export type userConfigInfoType = {
    menuCollapsed: boolean
}
export type changeRouterType = (name: string) => {}
export type getUserInfo = () => {}

//* 视图模式
export const VIEW_MODEL_TABLE:string = "table"
export const VIEW_MODEL_CARD:string = "card"
export type viewModelType = typeof VIEW_MODEL_TABLE | typeof VIEW_MODEL_CARD
export type changeViewModelType = () => {}