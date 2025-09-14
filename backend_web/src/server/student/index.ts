import { ref, reactive } from 'vue'
import { getStudentList } from './getStudentList'
import { createStudent } from './createStudent'
import { deleteStudent } from './deleteStudent'
import { editStudent } from './editStudent'

export const tableColumns = ref<{
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
        title: '学生学号',
        width: 180,
        dataIndex: 'student_number',
        key: 'student_number',
        fixed: 'left'
    },
    {
        title: '学生性别',
        dataIndex: 'student_sex',
        width: 200,
        key: 'student_sex'
    },
    {
        title: '学生得分总分',
        width: 200,
        dataIndex: 'student_score_count',
        key: 'student_score_count'
    },
    {
        title: '创建时间',
        dataIndex: 'create_datetime',
        width: 200,
        key: 'create_datetime'
    },
    {
        title: '操作',
        dataIndex: 'operation',
        width: 200,
        key: 'operation',
        fixed: 'right'
    }
]);

export const STUDENT_SEX_MAN: number = 1
export const STUDENT_SEX_WOMAN: number = 2
export const StudentSex = reactive({
    STUDENT_SEX_MAN: '男',
    STUDENT_SEX_WOMAN: '女',
})


export {
    getStudentList,
    editStudent,
    createStudent,
    deleteStudent,
}