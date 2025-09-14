<template>
    <main>
        <a-card style="width:95%" class="Shadow">
            <template #title>
                <a-tag style="font-size: 15px;" color="success">
                    <FieldTimeOutlined />
                    {{ getNewDate() }}
                </a-tag>
            </template>
            <template #extra><a-button>查看日历</a-button></template>
        <!-- please write this you like data -->
        <p>please write this you like data</p>
        </a-card>
        <a-divider />
        <a-card title="比值概率" class="Shadow" style="width: 45%">
            <a-row :gutter="16">
                <a-col :span="12">
                    <a-card>
                        <a-statistic title="Feedback" :value="11.28" :precision="2" suffix="%"
                            :value-style="{ color: '#3f8600' }" style="margin-right: 50px">
                            <template #prefix>
                                <arrow-up-outlined />
                            </template>
                        </a-statistic>
                    </a-card>
                </a-col>
                <a-col :span="12">
                    <a-card>
                        <a-statistic title="Idle" :value="9.3" :precision="2" suffix="%" class="demo-class"
                            :value-style="{ color: '#cf1322' }">
                            <template #prefix>
                                <arrow-down-outlined />
                            </template>
                        </a-statistic>
                    </a-card>
                </a-col>
            </a-row>
        </a-card>
        <a-card title="快捷入口" class="Shadow" style="width: 40%">
            <template #extra><a @click="openAddQuickEntranceDialogModel">
                    <AppstoreAddOutlined />
                </a></template>
            <a-spin :spinning="quickEntranceSpinning">
                <a-card>
                    <a-card-grid v-for="(item, index) in quickEntranceList" :key="index"
                        style="width: 50%; text-align: center" @click="handleQuickEntranceListRouterTo(item.menu_to)">
                        <div style="display: flex;justify-content: center;">
                            <a-tag color="cyan">
                                <component :is="$antIcons[item.menu_icon]" />
                                {{ item.menu_name }}
                            </a-tag>
                            <a-popover title="如果需要删除点击此按钮">
                                <template #content>
                                    <a-button type="primary" danger
                                        @click="handleDeleteQuickEntrance(item.quick_entrance_guid)">删除</a-button>
                                </template>
                                <a-tag color="red">
                                    <DeleteOutlined />
                                </a-tag>
                            </a-popover>
                        </div>
                    </a-card-grid>
                </a-card>
            </a-spin>
        </a-card>

        <addQuickEntranceDialog v-model:open="addQuickEntranceDialogModel" @HandleAddSuccess="HandleAddSuccess" />
    </main>
</template>
<script lang="ts" setup>
import { inject, onMounted, ref } from 'vue'
import { getQuickEntrance, deleteQuickEntrance } from '~/server/quick_entrance/index'
import addQuickEntranceDialog from '~/views/component/addQuickEntranceDialog.vue'
import { isRequestSuccess } from '../server'
import type { changeRouterType } from '.'


const quickEntranceSpinning = ref<boolean>(true);

const addQuickEntranceDialogModel = ref<boolean>(false)
const changeRouter: changeRouterType = inject("changeRouter")!

onMounted(() => {
    getQuickEntranceList()
})

const quickEntranceList = ref<getQuickEntrance.Data[]>([]);

const getQuickEntranceList = (): void => {
    getQuickEntrance.fetch({}).then((res: getQuickEntrance.returnResponse): void => {
        if (isRequestSuccess(res)) {
            quickEntranceSpinning.value = false
            quickEntranceList.value = res.data.list
        }
    })
}

const openAddQuickEntranceDialogModel = (): void => {
    addQuickEntranceDialogModel.value = true
}

const handleQuickEntranceListRouterTo = (routerTo: string): void => {
    changeRouter(routerTo)
}

const handleDeleteQuickEntrance = async (guid: string): Promise<void> => {
    deleteQuickEntrance.fetch({
        quick_entrance_guid: guid
    }).then((res: deleteQuickEntrance.returnResponse):void => {
        if (isRequestSuccess(res)) getQuickEntranceList()
    })
}

const HandleAddSuccess = (status: boolean):void => {
    if (status) getQuickEntranceList()
}

const getNewDate =():string =>{
    const date = new Date()
    return `${date.getFullYear()}年${date.getMonth()}月${date.getDate()}日`

}

</script>
<style scoped lang="less">
main {
    display:flex;
    justify-content: space-around;
    align-content: center;
    align-items: center;
    flex-wrap: wrap;
}
</style>