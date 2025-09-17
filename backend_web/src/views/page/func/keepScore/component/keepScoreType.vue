<template>
  <a-modal title="记分类型管理" width="80%">
    <a-card class="Shadow">
    <a-button type="primary" ghost @click="addKeepScoreTypeModel = true">添加记分类型</a-button>
    <a-button type="primary" danger v-if="selectedRowKeysData.length != 0" @click="HandleDelete">删除记分类型</a-button>
  </a-card>
    <a-spin tip="加载中..." :spinning="Spinning">
      <!-- 表格视图 -->
      <a-table :row-selection="rowSelection" :columns="tableColumnsForKeepScoreType" :data-source="data" class="Shadow"
        :scroll="{ x: 'calc(700px + 50%)', y: 500 }" :pagination="false">
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

    <template #footer>
    </template>
    <addKeepScoreTypeView v-model:open="addKeepScoreTypeModel" @handleAddSuccess="handleSuccess" />
  </a-modal>
</template>
<script setup lang="ts">
import { onUpdated, reactive, ref } from 'vue'
import { getStudentKeepscoreTypeList, tableColumnsForKeepScoreType, deleteStudentKeepscoreType } from '~/server/student_keepscore/index.ts'
import addKeepScoreTypeView from './components/addKeepScoreType.vue'
import { isRequestSuccess } from '~/server';
import pagination from '~/components/pagination.vue';

const addKeepScoreTypeModel = ref<boolean>(false);
const Spinning = ref<boolean>(false);
const selectedRowKeysData = ref<getStudentKeepscoreTypeList.Data[]>([])
const data = ref<getStudentKeepscoreTypeList.Data[]>([])
const dataCount = ref<number>(0);
const formState = reactive({})

onUpdated(() => {
  getStudentKeepscoreTypeListFetch()
})


/**
 * 多选
 */
const rowSelection = ref({
  checkStrictly: false,
  onChange: (
    _: (string | number)[],
    selectedRows: getStudentKeepscoreTypeList.Data[]
  ) => {
    selectedRowKeysData.value = selectedRows;
  },
});

const getStudentKeepscoreTypeListFetch = (params: getStudentKeepscoreTypeList.params = null): void => {
  Spinning.value = true
  getStudentKeepscoreTypeList.fetch(!params ? formState : params).then(async (res: getStudentKeepscoreTypeList.returnResponse): Promise<void> => {
    if (isRequestSuccess(res)) {
      data.value = res.data.list
      dataCount.value = res.data.count
      await (Spinning.value = false)
    }
  })
}

const HandleDelete = (): void => {
  Spinning.value = true
  deleteStudentKeepscoreType.fetch({
    student_keepscore_type_guid: selectedRowKeysData.value.map(
      (v: deleteStudentKeepscoreType.Data) => v.student_keepscore_type_guid
    ),
  }).then((res: deleteStudentKeepscoreType.returnResponse) => {
    if (isRequestSuccess(res)) {
      getStudentKeepscoreTypeListFetch()
      selectedRowKeysData.value = [];
      Spinning.value = false
    }
  });
}

/**
 * 分页操作
 * 
 * @returns void
 */
const handlePageChange = (page: object): void => {
  getStudentKeepscoreTypeListFetch({ ...formState, ...page })
}

const handleSuccess = (status: boolean): void => {
  if (status) getStudentKeepscoreTypeListFetch()
}


</script>
<style scoped lang="less">
.ant-btn:nth-child(2n) {
  margin-left: 5px;
}
</style>