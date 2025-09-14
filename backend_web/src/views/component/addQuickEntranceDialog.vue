<template>
    <a-modal title="添加快捷入口">
        <a-form :model="formState" name="basic" :label-col="{ span: 0 }" :wrapper-col="{ span: 16 }" autocomplete="off"
            @finish="onFinish">

            <a-form-item label="菜单" name="menu_guid" :rules="[{ required: true, message: '请选择菜单!' }]">

                <a-select ref="select" v-model:value="formState.menu_guid" style="width: 240px">
                    <a-select-option v-for="(item, index) in menuList" :value="item.menu_guid">
                        <a-tag :bordered="false" color="processing">{{ item.menu_to }}</a-tag>
                        <component :is="$antIcons[item.menu_icon]" />
                        {{ item.menu_name }}
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
import { getMenuTree } from '~/server/menu/index'
import { isRequestSuccess } from '../../server'
import { addQuickEntrance } from '~/server/quick_entrance/index'


interface FormState {
    menu_guid: string
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
    menu_guid: ''
});

const onFinish = async (values: any): Promise<void> => {
    addQuickEntrance.fetch(values).then((res: addQuickEntrance.returnResponse) => {
        if (isRequestSuccess(res)) {
            emits("HandleAddSuccess", true)
        }
    })
};


</script>
<style scoped></style>