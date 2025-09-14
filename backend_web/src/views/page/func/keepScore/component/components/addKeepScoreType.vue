<template>
    <a-modal title="添加记分类型" width="50%">

        <a-form :model="formState" name="basic" autocomplete="off" @finish="onFinish" >
            <a-form-item label="记分类型名称" name="student_keepscore_type_name"
                :rules="[{ required: true, message: '请输入记分类型名称!' }]">
                <a-input v-model:value="formState.student_keepscore_type_name" />
            </a-form-item>
            <a-form-item label="记分分数" name="keepscore_num" :rules="[{ required: true, message: '请输入记分分数!' }]">
                <a-input-number v-model:value="formState.keepscore_num" size="large" :min="1" :max="10" />
            </a-form-item>
            <a-form-item>
                <a-button type="primary" html-type="submit">添加</a-button>
            </a-form-item>
        </a-form>

        <template #footer>
        </template>
    </a-modal>
</template>
<script setup lang="ts">
import { reactive } from 'vue';
import { addStudentKeepscoreType } from '~/server/student_keepscore/index.ts'
import { isRequestSuccess } from '~/server';
import {debounce} from 'lodash-es'

const emit = defineEmits(["handleAddSuccess"])

const formState = reactive<addStudentKeepscoreType.params>({
    student_keepscore_type_name: '',
    keepscore_num: 1
});


const onFinish = debounce((values: any) => {
    addStudentKeepscoreType.fetch(values).then((res: addStudentKeepscoreType.returnResponse) => {
        if (isRequestSuccess(res)) emit('handleAddSuccess', true)
    })
},200);

</script>
<style scoped lang="less"></style>