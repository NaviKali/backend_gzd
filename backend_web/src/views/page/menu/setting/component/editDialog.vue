<template>
    <a-modal title="编辑菜单">
        <a-form :model="props.item" name="basic" :label-col="{ span: 0 }" :wrapper-col="{ span: 16 }" autocomplete="off"
            @finish="onFinish">

            <a-form-item label="菜单名称" name="menu_name" :rules="[{ required: true, message: '请输入菜单名称!' }]">
                <a-input v-model:value="props.item.menu_name" placeholder="请输入菜单名称" />
            </a-form-item>
            <a-form-item label="菜单跳转" name="menu_to" :rules="[{ required: true, message: '请输入菜单跳转!' }]">
                <a-input v-model:value="props.item.menu_to" placeholder="请输入菜单跳转" />
            </a-form-item>
            <a-form-item label="菜单图标" name="menu_icon">
                <selectMenuIconComponent  v-model:value="props.item.menu_icon" />
            </a-form-item>
            <a-form-item label="继承菜单" name="menu_father_guid">
                <a-select ref="select" v-model:value="props.item.menu_father_guid" style="width: 120px">
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
import { onUpdated, ref } from 'vue'
import { getMenuTree,EditMenu } from '~/server/menu/index'
import { isRequestSuccess } from '~/server';
import selectMenuIconComponent  from '~/components/selectMenuIcon.vue'

const props = defineProps({
    item:{
        type:Object,
        required:true,
        default:{}
    }
})
const emits = defineEmits(["HandleAddSuccess"])
const menuList = ref<getMenuTree.Data[]>();

onUpdated(() => {
    getMenuTree.fetch({
        open:getMenuTree.OPEN_ALL
    }).then((res: getMenuTree.returnResponse) => {
        if (isRequestSuccess(res))
            menuList.value = res.data
    })
})

const onFinish = async (_: any): Promise<void> => {
    EditMenu.fetch(props.item).then(
    async (res: EditMenu.returnResponse): Promise<void> => {
      if (isRequestSuccess(res)) {
        emits("HandleAddSuccess", true)
      }
    }
  );
};

</script>
<style scoped></style>