<template>
    <a-modal title="添加菜单">
        <a-form :model="formState" name="basic" :label-col="{ span: 0 }" :wrapper-col="{ span: 16 }" autocomplete="off"
            @finish="onFinish">

            <a-form-item label="菜单名称" name="menu_name" :rules="[{ required: true, message: '请输入菜单名称!' }]">
                <a-input v-model:value="formState.menu_name" placeholder="请输入菜单名称" />
            </a-form-item>
            <a-form-item label="菜单跳转" name="menu_to" :rules="[{ required: true, message: '请输入菜单跳转!' }]">
                <a-input v-model:value="formState.menu_to" placeholder="请输入菜单跳转" />
            </a-form-item>
            <a-form-item label="菜单图标" name="menu_icon">
                <selectMenuIconComponent  v-model:value="formState.menu_icon" />
            </a-form-item>
            <a-form-item label="继承菜单" name="menu_father_guid">
                <a-select ref="select" v-model:value="formState.menu_father_guid" style="width: 120px">
                    <a-select-option v-for="(item, index) in menuList" :key="index" :value="item.menu_guid">
                        <component :is="$antIcons[item.menu_icon]" />{{ item.menu_name }}
                    </a-select-option>
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
import { onUpdated, reactive, ref } from 'vue'
import { getMenuTree,CreateMenu } from '~/server/menu/index'
import { isRequestSuccess } from '~/server';
import selectMenuIconComponent  from '~/components/selectMenuIcon.vue'


interface FormState {
    menu_name: string
    menu_to: string
    menu_icon: string | null
    menu_father_guid: string | null
}

const menuList = ref<getMenuTree.Data[]>();


onUpdated(() => {
    getMenuTree.fetch({
        open:getMenuTree.OPEN_ALL
    }).then((res: getMenuTree.returnResponse) => {
        if (isRequestSuccess(res))
            menuList.value = res.data
    })
})

const emits = defineEmits(["HandleAddSuccess"])

const formState = reactive<FormState>({
    menu_name: '',
    menu_to: '',
    menu_icon: null,
    menu_father_guid: null
});

const onFinish = async (values: any): Promise<void> => {
    CreateMenu.fetch(values).then((res: CreateMenu.returnResponse):void => {
        if (isRequestSuccess(res))
            emits("HandleAddSuccess", true)
    })
};

</script>
<style scoped></style>