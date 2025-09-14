import { addStudentKeepscoreType } from './student_keepscore_type/addStudentKeepscoreType.ts'
import { deleteStudentKeepscoreType } from './student_keepscore_type/deleteStudentKeepscoreType.ts'
import { editStudentKeepscoreType } from './student_keepscore_type/editStudentKeepscoreType.ts'
import { getStudentKeepscoreTypeList } from './student_keepscore_type/getStudentKeepscoreTypeList.ts'
import {ref} from 'vue'

export const tableColumnsForKeepScoreType = ref<{
    title: string,
    dataIndex: string,
    key?: string,
    width?: number,
    fixed?: string
}[]>([
    {
        title: '记分类型名称',
        dataIndex: 'student_keepscore_type_name',
        key: 'student_keepscore_type_name',
        width: 180,
        fixed: 'left'
    },
    {
        title: '记分分数',
        width: 180,
        dataIndex: 'keepscore_num',
        key: 'keepscore_num',
        fixed: 'left'
    },
    {
        title: '创建时间',
        dataIndex: 'create_datetime',
        width: 200,
        key: 'create_datetime'
    },
]);

export {
    addStudentKeepscoreType,
    deleteStudentKeepscoreType,
    editStudentKeepscoreType,
    getStudentKeepscoreTypeList,
}