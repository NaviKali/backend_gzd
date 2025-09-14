<template>
    <a-modal title="添加职务">
        <a-form :model="formState" name="basic" :label-col="{ span: 0 }" :wrapper-col="{ span: 16 }" autocomplete="off"
            @finish="onFinish">
            <a-form-item label="职务名称" name="user_role_name" :rules="[{ required: true, message: '请输入职务名称!' }]">
                <a-input v-model:value="formState.user_role_name" placeholder="请输入职务名称" />
            </a-form-item>
            <a-form-item label="所属用户绑定" name="user_guid" :rules="[{ required: true, message: '请选择所属用户绑定!' }]">
                <a-select v-model:value="formState.user_guid" :options="options" mode="multiple" :size="size"   
                    placeholder="请选择所属用户绑定" style="width: 200px" @search="handleSearch"  :filter-option="false"></a-select>
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
import { onUpdated, reactive,ref } from 'vue'
import { isRequestSuccess } from '~/server';
import { CreateUserRole } from '~/server/user/user_role';
import type { SelectProps } from 'ant-design-vue';
import { getSelectUserList } from '~/server/user/index'
import {debounce} from 'lodash-es'

interface FormState {
    user_role_name: string
    user_guid: string[]
}
interface options {
    label:string,
    value:string
}

const size = ref<SelectProps['size']>('middle');
const formState = reactive<FormState>({
    user_role_name: String(),
    user_guid:Array<string>(),
});
const options = ref<options[]>([]);
const emit = defineEmits(['HandleAddSuccess']);




onUpdated(():void=>{
    getSelectUserListFetch()  
})

/**
 * 获取查询用户列表下拉菜单
 * 
 * @param _user_name 用户搜索姓名 string|null default:null 
 * @returns void
 */
const getSelectUserListFetch = (_user_name:string|null = null):void =>{
    getSelectUserList.fetch({
        user_name:_user_name
    }).then((res: getSelectUserList.returnResponse): void => {
        if (isRequestSuccess(res)) {
            options.value = res.data.map((item: getSelectUserList.Data): options => ({
                label: item.user_name,
                value: item.user_guid
            }));
        }
    });
}

const onFinish = async (values: any): Promise<void> => {
    CreateUserRole.fetch(values).then((res: CreateUserRole.returnResponse): void => {
        if (isRequestSuccess(res))
            emit("HandleAddSuccess", true)
    })
};

/**
 * 搜索对应用户
 * 
 * @default 防抖
 * @param _v 搜索值 string
 * @returns void
 */
const handleSearch = debounce((_v:string):void =>{
    getSelectUserListFetch(_v)
},200)

</script>
<style scoped></style>