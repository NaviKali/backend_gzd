import { addStudentKeepscoreType } from './student_keepscore_type/addStudentKeepscoreType.ts'
import { deleteStudentKeepscoreType } from './student_keepscore_type/deleteStudentKeepscoreType.ts'
import { editStudentKeepscoreType } from './student_keepscore_type/editStudentKeepscoreType.ts'
import { getStudentKeepscoreTypeList } from './student_keepscore_type/getStudentKeepscoreTypeList.ts'
import { addStudentKeepscore } from './addStudentKeepscore.ts'
import { getStudentKeepscoreList } from './getStudentKeepscoreList.ts'
import { deleteStudentKeepscore } from './deleteStudentKeepscore.ts'


import { ref } from 'vue'

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

export const tableColumnsForKeepScore = ref<{
    title: string,
    dataIndex: string,
    key?: string,
    width?: number,
    fixed?: string
}[]>([
    {
        title: '学生姓名',
        dataIndex: 'student_name',
        key: 'student_name',
        width: 180,
        fixed: 'left'
    },
    {
        title: '记分类型',
        width: 180,
        dataIndex: 'student_keepscore_type_name',
        key: 'student_keepscore_type_name',
        fixed: 'left'
    },
    {
        title: '记分',
        width: 180,
        dataIndex: 'keepscore_num',
        key: 'keepscore_num',
        fixed: 'left'
    },
    {
        title: '提交日期',
        dataIndex: 'student_keepscore_date',
        width: 200,
        key: 'student_keepscore_date'
    },
]);

export {
    addStudentKeepscoreType,
    deleteStudentKeepscoreType,
    editStudentKeepscoreType,
    getStudentKeepscoreTypeList,
    addStudentKeepscore,
    getStudentKeepscoreList,
    deleteStudentKeepscore,
}