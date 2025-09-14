import { ref } from "vue";
import { getUserRoleList } from "./getUserRoleList";
import { CreateUserRole } from "./CreateUserRole";
import { deleteUserRole } from "./deleteUserRole";
import { editUserRole } from "./editUserRole";

export const tableColumns = ref<{
    title: string,
    dataIndex: string,
    key?: string,
    fixed?: boolean
}[]>([
    {
        title: '用户职务名称',
        dataIndex: 'user_role_name',
        key: 'user_role_name',
    },
    {
        title: '职务所属用户',
        dataIndex: 'bind_user_list',
        key: 'bind_user_list'
    },
    {
        title: '创建时间',
        dataIndex: 'create_datetime',
        key: 'create_datetime'
    },
    {
        title: '操作',
        dataIndex: 'operation',
        key: 'operation'
    }
]);

export const Context:{
    NOT_BIND:string
} = {
    NOT_BIND:"未绑定"
}

export { getUserRoleList,CreateUserRole,deleteUserRole,editUserRole }