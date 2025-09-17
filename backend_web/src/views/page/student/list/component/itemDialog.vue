<template>
    <a-modal title="学生详情" width="80%">
        <a-tabs v-model:activeKey="activeKey" centered>
            <a-tab-pane key="keepscore" tab="记分情况">
                <a-card>
                    <a-button type="primary" danger v-if="selectedRowKeysData.length != 0"
                        @click="HandleDelete">删除记分记录</a-button>
                </a-card>
                <a-spin tip="加载中..." :spinning="Spinning">
                    <!-- 表格视图 -->
                    <a-table :row-selection="rowSelection" :columns="tableColumnsForKeepScore" :data-source="data"
                        class="Shadow" :scroll="{ x: 'calc(700px + 50%)', y: 500 }" :pagination="false">
                        <template #bodyCell="{ column, text }">
                            <template v-if="column.dataIndex == 'student_keepscore_type_name'">
                                <a-tag color="cyan">
                                    {{ text }}</a-tag>
                            </template>
                            <template v-if="column.dataIndex == 'keepscore_num'">
                                <a-tag color="green">
                                    <template #icon>
                                        <PlusOutlined />
                                    </template>
                                    {{ text }}
                                </a-tag>
                            </template>
                            <template v-if="column.dataIndex === 'create_datetime'">
                                <a-tag color="processing">{{ text }}</a-tag>
                            </template>
                        </template>
                    </a-table>
                </a-spin>
                <!-- 分页 -->
                <a-card class="PagePacka Shadow">
                    <pagination @handlePageChange="handlePageChange" :count="dataCount" />
                </a-card>
            </a-tab-pane>
        </a-tabs>
        <template #footer>
        </template>
    </a-modal>
</template>
<script setup lang="ts">
import { onUpdated, ref } from 'vue'
import { getStudentKeepscoreList, tableColumnsForKeepScore, deleteStudentKeepscore } from '~/server/student_keepscore/index.ts'
import { isRequestSuccess } from '~/server'
import pagination from '~/components/pagination.vue';

const emits = defineEmits(["handleSuccess"])
const activeKey = ref<string>('keepscore');
const Spinning = ref<boolean>(false);
const selectedRowKeysData = ref<getStudentKeepscoreList.Data[]>([])
const data = ref<getStudentKeepscoreList.Data[]>([]);
const dataCount = ref<number>(0);
const props = defineProps({
    guid: {
        type: String,
        required: true
    }
})

/**
 * 多选
 */
const rowSelection = ref({
    checkStrictly: false,
    onChange: (
        _: (string | number)[],
        selectedRows: getStudentKeepscoreList.Data[]
    ) => {
        selectedRowKeysData.value = selectedRows;
    },
});

onUpdated(() => {
    getStudentKeepscoreListFetch()
})

const getStudentKeepscoreListFetch = async (params:getStudentKeepscoreList.params = null): Promise<void> => {
    Spinning.value = true
    await getStudentKeepscoreList.fetch({
        student_guid: props.guid,
        ...params
    }).then((res: getStudentKeepscoreList.returnResponse): void => {
        if (isRequestSuccess) {
            data.value = res.data.list
            dataCount.value = res.data.count
            Spinning.value = false
        }
    })
}


/**
 * 分页操作
 * 
 * @returns void
 */
const handlePageChange = (page: object): void => {
    getStudentKeepscoreListFetch(page)
}


const HandleDelete = (): void => {
    Spinning.value = true
    deleteStudentKeepscore.fetch({
        student_keepscore_guid: selectedRowKeysData.value.map(
            (v: deleteStudentKeepscore.Data) => v.student_keepscore_guid
        ),
    }).then((res: deleteStudentKeepscore.returnResponse) => {
        if (isRequestSuccess(res)) {
            getStudentKeepscoreListFetch()
            selectedRowKeysData.value = [];
            Spinning.value = false
            emits('handleSuccess', true)
        }
    });
}


</script>
<style scoped lang="less"></style>