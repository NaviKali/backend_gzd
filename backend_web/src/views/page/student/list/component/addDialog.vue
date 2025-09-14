<template>
    <a-modal title="添加学生">
        <a-form :model="formState" name="basic" :label-col="{ span: 0 }" :wrapper-col="{ span: 16 }" autocomplete="off"
            @finish="onFinish">
            <a-form-item label="学生学号" name="student_number" :rules="[{ required: true, message: '请输入学生学号!' }]">
                <a-input v-model:value="formState.student_number" placeholder="请输入学生学号" />
            </a-form-item>
            <a-form-item label="学生姓名" name="student_name" :rules="[{ required: true, message: '请输入学生姓名!' }]">
                <a-input v-model:value="formState.student_name" placeholder="请输入学生姓名" />
            </a-form-item>
            <a-form-item label="学生性别" name="student_sex" :rules="[{ required: true, message: '请选择学生性别!' }]">
                <a-select ref="select" v-model:value="formState.student_sex" style="width: 200px" placeholder="请选择学生性别">
                    <a-select-option v-for="(item, index) in StudentSexArr" :key="index" :value="item.value">{{
                        item.content
                        }}</a-select-option>
                </a-select>
            </a-form-item>
            <a-form-item :wrapper-col="{ offset: 0, span: 16 }">
                <a-button type="primary" html-type="submit">确定</a-button>
            </a-form-item>
        </a-form>
        <template #footer>
        </template>
    </a-modal>
</template>
<script setup lang="ts">
import { reactive, ref,defineEmits } from 'vue'
import { debounce } from 'lodash-es'
import { StudentSex, createStudent, STUDENT_SEX_MAN, STUDENT_SEX_WOMAN } from '~/server/student/index.ts'
import { isRequestSuccess } from '~/server'

interface FormState {
    student_number: string
    student_name: string
    student_sex: number | null
}
interface options {
    label: string,
    value: string
}

const StudentSexArr = ref<{
    value: number,
    content: string
}[]>([{
    value: STUDENT_SEX_MAN,
    content: StudentSex.STUDENT_SEX_MAN
},
{
    value: STUDENT_SEX_WOMAN,
    content: StudentSex.STUDENT_SEX_WOMAN
}
]);

const formState = reactive<FormState>({
    student_name: String(""),
    student_number: String(),
    student_sex: null,
});
const options = ref<options[]>([]);
const emit = defineEmits(['HandleAddSuccess']);

const onFinish = (values: FormState): void => {
    createStudent.fetch(values).then((res: createStudent.returnResponse): void => {
        if (isRequestSuccess(res))
            emit("HandleAddSuccess", true)
    })
};


</script>
<style scoped></style>