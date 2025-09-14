<template>
  <main>
    <header>
      <a-breadcrumb>
        <a-breadcrumb-item>用户管理</a-breadcrumb-item>
        <a-breadcrumb-item>角色列表</a-breadcrumb-item>
      </a-breadcrumb>
    </header>
    <header>
      <a-collapse v-model:activeKey="activeKey" expand-icon-position="end">
        <a-collapse-panel key="where" header="筛选查询">
          <a-form layout="inline" :model="formState">
            <a-form-item label="职务名称">
              <a-input v-model:value="formState.user_role_name" placeholder="请输入职务名称" @input="handleInput" />
            </a-form-item>
          </a-form>
        </a-collapse-panel>
        <a-collapse-panel key="where" header="操作集合" :show-arrow="false">
          <a-button type="primary" @click="addDialogModel = true">添加职务</a-button>
          <a-button type="primary" danger v-if="selectedRowKeysData.length != 0"
            @click="HandleDeleteRole">删除职务</a-button>
        </a-collapse-panel>
        <a-collapse-panel key="do" header="功能" :show-arrow="false">
          <a-button @click="handleChangeView">切换视图</a-button>
        </a-collapse-panel>
      </a-collapse>
    </header>
    <a-spin tip="加载中..." :spinning="Spinning" v-if="userInfo().getUserConfig">
      <!-- 表格视图 -->
      <a-table :row-selection="rowSelection" :columns="tableColumns" :data-source="data" class="Shadow"
        v-if="userInfo().getUserConfig.user_config_view_model == VIEW_MODEL_TABLE" :pagination="false">
        <template #bodyCell="{ column, text, record }">
          <template v-if="column.dataIndex === 'bind_user_list'">
            <a-tag color="processing" v-if="text.length == 0">{{ Context.NOT_BIND }}</a-tag>
            <a-tag v-else color="success" v-for="(item, index) in text" :key="index">{{ item }}</a-tag>
          </template>
          <template v-if="column.dataIndex === 'create_datetime'">
            <a-tag color="processing">{{ text }}</a-tag>
          </template>
          <template v-if="column.dataIndex == 'operation'">
            <a-button type="primary" @click="handleTableEdit(record)">编辑</a-button>
          </template>
        </template>
        <template #footer>
          <pagination @handlePageChange="handlePageChange" :count="dataCount" />
        </template>
      </a-table>
      <!-- 卡片视图 -->
      <div class="card-view" v-if="userInfo().getUserConfig.user_config_view_model == VIEW_MODEL_CARD">
        <cardView :data="data" :handleClick="handleCardClick" :handleEdit="handleCardEdit"
          :handleDelete="handleCardDelete">
          <template #title="{ item }">
            <p>{{ item.user_role_name }}</p>
          </template>
          <template #content="{ item }">
            <a-tag color="processing" v-if="item.bind_user_list.length == 0">{{ Context.NOT_BIND }}</a-tag>
            <a-tag v-else color="success" v-for="(_item, index) in item.bind_user_list" :key="index">{{ _item }}</a-tag>
          </template>
        </cardView>
      </div>
    </a-spin>
    <addDialog v-model:open="addDialogModel" @HandleAddSuccess="HandleSuccess" />
    <editDialog v-model:open="editDialogModel" :item="editItem" @HandleEditSuccess="HandleSuccess" />
  </main>
</template>
<script lang="ts" setup>
import { ref, reactive, onMounted, inject } from 'vue';
import { getUserRoleList, tableColumns, Context, deleteUserRole } from '~/server/user/user_role';
import { isRequestSuccess } from '~/server';
import addDialog from './component/addDialog.vue';
import type { getUserInfo } from "~/views";
import type { changeViewModelType } from "~/views";
import { VIEW_MODEL_TABLE, VIEW_MODEL_CARD } from "~/views";
import cardView from "~/components/cardView.vue";
import editDialog from "./component/editDialog.vue";
import pagination from '~/components/pagination.vue';

const activeKey = ref<string>("where");
const Spinning = ref<boolean>(true);
const addDialogModel = ref<boolean>(false);
const editDialogModel = ref<boolean>(false);
const editItem = ref<getUserRoleList.Data.list>({});
const formState = reactive<{
  user_role_name: string;
}>({
  user_role_name: "",
});
const data = ref<getUserRoleList.Data.list[]>([]);
const dataCount = ref<number>();
const selectedRowKeysData = ref<getUserRoleList.Data.list[]>([]);
const userInfo = inject<getUserInfo>("getUserInfo")!;
const changeViewModel = inject<changeViewModelType>("changeViewModel")!;



onMounted((): void => {
  getUserRoleListFetch()
})

const getUserRoleListFetch = (params: getUserRoleList.params = null): void => {
  Spinning.value = true
  getUserRoleList.fetch(!params ? formState : params).then(async (res: getUserRoleList.returnResponse): Promise<void> => {
    if (isRequestSuccess(res)) {
      data.value = res.data.list
      dataCount.value = res.data.count
      await (Spinning.value = false);
    }
  })
}

const rowSelection = ref({
  checkStrictly: false,
  onChange: (
    _: (string | number)[],
    selectedRows: getUserRoleList.Data[]
  ) => {
    selectedRowKeysData.value = selectedRows;
  },
});

const HandleSuccess = (status: boolean): void => {
  if (status) getUserRoleListFetch()
}

/**
 * 删除用户职务
 * 
 * @returns void
 */
const HandleDeleteRole = (): void => {
  Spinning.value = true
  deleteUserRole.fetch({
    user_role_guid: selectedRowKeysData.value.map(
      (v: getUserRoleList.Data): void => v.user_role_guid
    ),
  }).then((res: deleteUserRole.returnResponse): void => {
    if (isRequestSuccess(res)) {
      getUserRoleListFetch();
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
  getUserRoleListFetch({ ...formState, ...page })
}


/**
 * 表格编辑
 * 
 * @param record 
 * @returns void
 */
const handleTableEdit = (record: getUserRoleList.Data.list): void => {
  editItem.value = record
  editDialogModel.value = true
}

/**
 * 搜索框
 * 
 * @returns void
 */
const handleInput = (): void => {
  getUserRoleListFetch()
}

/**
 * 切换试图
 * 
 * @returns void
 */
const handleChangeView = (): void => {
  changeViewModel()
};


/**
 * 卡片点击
 * 
 * @param item any 菜单
 * @returns void
 */
const handleCardClick = (item: any): void => { };

/**
 * 卡片删除
 * 
 * @param item any 菜单
 * @returns void
 */
const handleCardDelete = async (item: any): Promise<void> => {
  Spinning.value = true;
  await deleteUserRole.fetch({
    user_role_guid: [item.user_role_guid],
  }).then((res: deleteUserRole.returnResponse): void => {
    if (isRequestSuccess(res)) {
      getUserRoleListFetch();
      Spinning.value = false;
    }
  });
};

/**
 * 卡片编辑
 * 
 * @param item any 菜单
 * @returns void
 */
const handleCardEdit = async (item: any): Promise<void> => {
  editItem.value = item
  editDialogModel.value = true
};


</script>
<style scoped>
header {
  margin-top: 20px;
  margin-bottom: 20px;
}

.ant-btn:nth-child(2n) {
  margin-left: 5px;
}
</style>