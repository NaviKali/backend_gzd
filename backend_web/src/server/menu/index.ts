import { getMenuTree } from './getMenuTree'
import { EditMenu } from './EditMenu'
import { CreateMenu } from './CreateMenu'
import { getMenuToList } from './getMenuToList'
import { DeleteMenu } from './DeleteMenu'
import { ref } from 'vue';

export const tableColumns = ref<{
    title: string,
    dataIndex: string,
    key?: string,
    fixed?: boolean
}[]>([
    {
        title: '菜单名称',
        dataIndex: 'menu_name',
        key: 'menu_name',
    },
    {
        title: '菜单跳转',
        dataIndex: 'menu_to',
        key: 'menu_to'
    },
    {
        title: '菜单icon图标',
        dataIndex: 'menu_icon',
        key: 'menu_icon'
    },
    {
        title: '操作',
        dataIndex: 'operation',
    },
]);

export const editColumns = ref<string[]>(['menu_name', 'menu_to', 'menu_icon']);

export {
    getMenuTree,
    EditMenu,
    CreateMenu,
    getMenuToList,
    DeleteMenu
}