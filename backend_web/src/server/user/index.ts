import { getCurrentUserInformation } from './getCurrentUserInformation'
import { uploadsImage } from './uploadsImage'
import { getUserList } from './getUserList'
import { reactive, ref } from 'vue';
import { getSelectUserList } from './getSelectUserList'

export const tableColumns = ref<{
    title: string,
    dataIndex: string,
    key?: string,
    width?:number,
    fixed?: string
}[]>([
    {
        title: '用户姓名',
        dataIndex: 'user_name',
        key: 'user_name',
        width:180,
        fixed: 'left'
    },
    {
        title: '头像',
        width:180,
        dataIndex: 'user_image',
        key: 'user_image',
        fixed: 'left'
    },
    {
        title: '性别',
        dataIndex: 'user_sex',
        width:200,
        key: 'user_sex'
    },
    {
        title: '职务',
        width:200,
        dataIndex: 'user_role_name',
        key: 'user_role_name'
    },
    {
        title: '手机号',
        width:200,
        dataIndex: 'user_phone',
        key: 'user_phone'
    },
    {
        title: '邮箱',
        dataIndex: 'user_email',
        width:200,
        key: 'user_email'
    },
    {
        title: '创建时间',
        dataIndex: 'create_datetime',
        width:200,
        key: 'create_datetime'
    },
    {
        title: '操作',
        dataIndex: 'operation',
        width:200,
        key: 'operation',
        fixed: 'right'
    }
]);

export const USER_SEX_MAN: number = 1
export const USER_SEX_WOMAN: number = 2
export const UserSex = reactive({
    USER_SEX_MAN: "男",
    USER_SEX_WOMAN: "女"
})

export {
    getUserList,
    uploadsImage,
    getCurrentUserInformation,
    getSelectUserList
}