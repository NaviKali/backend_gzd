<template>
  <main>
    <header>
      <a-breadcrumb>
        <a-breadcrumb-item>菜单</a-breadcrumb-item>
        <a-breadcrumb-item>菜单列表</a-breadcrumb-item>
      </a-breadcrumb>
    </header>
    <header>
      <a-collapse v-model:activeKey="activeKey" expand-icon-position="end">
        <a-collapse-panel key="where" header="筛选查询">
          <a-form layout="inline" :model="formState">
            <a-form-item label="菜单名称">
              <a-input v-model:value="formState.menu_name" placeholder="请输入菜单名称" @input="handleInput" />
            </a-form-item>
          </a-form>
        </a-collapse-panel>
        <a-collapse-panel key="where" header="操作集合" :show-arrow="false">
          <a-button type="primary" @click="addDialogModel = true">添加菜单</a-button>
          <a-button type="primary" danger v-if="selectedRowKeysData.length != 0"
            @click="HandleDeleteMenu">删除菜单</a-button>
        </a-collapse-panel>
        <a-collapse-panel key="do" header="功能" :show-arrow="false">
          <a-button @click="handleChangeView">切换视图</a-button>
        </a-collapse-panel>
      </a-collapse>
    </header>
    <a-spin tip="加载中..." :spinning="Spinning" v-if="userInfo().getUserConfig">
      <!-- 表格视图 -->
      <a-table :row-selection="rowSelection" :columns="tableColumns" :data-source="data" class="Shadow"
        v-if="userInfo().getUserConfig.user_config_view_model == VIEW_MODEL_TABLE">
        <template #bodyCell="{ column, text, record }">
          <template v-if="editColumns.includes(column.dataIndex)">
            <div v-if="editableData[record.menu_guid]">
              <a-select v-if="
                editableData[record.menu_guid] &&
                column.dataIndex == 'menu_to'
              " ref="select" v-model:value="editableData[record.menu_guid][column.dataIndex]" style="width:400px"
                show-search>
                <a-select-option v-for="(item, index) in router.getRoutes()" :key="index" :value="item.path">{{
                  item.path }}</a-select-option>
              </a-select>
              <a-select v-else-if="
                editableData[record.menu_guid] &&
                column.dataIndex == 'menu_icon'
              " ref="select" v-model:value="editableData[record.menu_guid][column.dataIndex]" style="width: 80px">
                <a-select-option v-for="(item, index) in Object.keys(IconData)" :key="index" :value="item">
                  <component :is="$antIcons[item]" />
                </a-select-option>
              </a-select>
              <a-input v-else-if="editableData[record.menu_guid]"
                v-model:value="editableData[record.menu_guid][column.dataIndex]" style="width:200px" />
            </div>
            <div v-else-if="column.dataIndex == 'menu_to'">
              <a-tag color="blue">{{ text }}</a-tag>
            </div>
            <div v-else-if="column.dataIndex == 'menu_icon'">
              <component :is="$antIcons[text]" />
            </div>
            <div v-else>
              {{ text }}
            </div>
          </template>
          <template v-if="column.dataIndex == 'operation'">
            <div v-if="editableData[record.menu_guid]">
              <a-button type="primary" @click="handleSave(record.menu_guid)">保存</a-button>
              <a-button type="primary" @click="handleCancel(record.menu_guid)">取消</a-button>
            </div>
            <a-button v-else type="primary" @click="handleEdit(record.menu_guid)">编辑</a-button>
          </template>
        </template>
      </a-table>
      <!-- 卡片视图 -->
      <div class="card-view" v-if="userInfo().getUserConfig.user_config_view_model == VIEW_MODEL_CARD">
        <cardView :data="data" :handleClick="handleCardClick" :handleEdit="handleCardEdit" :handleDelete="handleCardDelete">
          <template #title="{ item }">
            <p>{{ item.menu_name }}</p>
          </template>
          <template #extra="{ item }">
            <component :is="$antIcons[item.menu_icon]" />
          </template>
          <template #content="{ item }">
            <a-tag type="primary" color="blue">{{ item.menu_to }}</a-tag>
          </template>
        </cardView>
      </div>
    </a-spin>
  </main>
  <addDialog v-model:open="addDialogModel" @HandleAddSuccess="HandleAddSuccess" />
  <editDialog v-model:open="editDialogModel"  :item="editItem" />
</template>
<script setup lang="ts">
import { ref, reactive, onMounted, inject } from "vue";
import type { UnwrapRef } from "vue";
import {
  getMenuTree,
  tableColumns,
  editColumns,
  EditMenu,
  DeleteMenu,
} from "~/server/menu/index";
import { isRequestSuccess } from "~/server";
import router from "@/router";
import * as icons from "@ant-design/icons-vue";
import addDialog from "./component/addDialog.vue";
import editDialog from "./component/editDialog.vue";
import type { getUserInfo } from "~/views";
import { VIEW_MODEL_TABLE, VIEW_MODEL_CARD } from "~/views";
import type { changeViewModelType } from "~/views";
import cardView from "~/components/cardView.vue";

const addDialogModel = ref<boolean>(false);
const editDialogModel = ref<boolean>(false);
const editItem = ref<getMenuTree.Data>({});
const Spinning = ref<boolean>(true);
const formState = reactive<{
  menu_name: string;
}>({
  menu_name: "",
});
const activeKey = ref<string>("where");
const editableData: UnwrapRef<Record<string, getMenuTree.Data>> = reactive({});
const data = ref<getMenuTree.Data[]>([]);
const selectedRowKeysData = ref<getMenuTree.Data[]>([]);
const IconData: Record<string, any> = icons;
const menuAllList = ref<getMenuTree.Data[]>([]);

const userInfo = inject<getUserInfo>("getUserInfo")!;
const changeViewModel = inject<changeViewModelType>("changeViewModel")!;


onMounted(() => {
  MenuTree();
});

/**
 * 获取菜单树
 * 
 * @returns void
 */
const MenuTree = (): void => {
  Spinning.value = true;
  getMenuTree.fetch(formState).then((res: getMenuTree.returnResponse) => {
    if (isRequestSuccess(res)) {
      data.value = res.data;
      Spinning.value = false;

      //获取转列表菜单
      const ChangeMenuToList = (data: getMenuTree.Data[]): void => {
        data.forEach((item: getMenuTree.Data) => {
          menuAllList.value.push({
            menu_guid: item.menu_guid,
            menu_name: item.menu_name,
            menu_to: item.menu_to,
            menu_icon: item.menu_icon,
            menu_father_guid: item.menu_father_guid,
          });
          if (item.children.length != 0) {
            ChangeMenuToList(item.children);
          }
        });
      };
      ChangeMenuToList(res.data);
    }
  });
};

/**
 * 编辑菜单
 * 
 * @param key string 菜单guid
 * @returns void
 */
const handleEdit = (key: string): void => {
  editableData[key] = menuAllList.value.filter(
    (item: getMenuTree.Data) => key === item.menu_guid
  )[0];
};

/**
 * 保存编辑
 * 
 * @param key string 菜单guid
 * @returns void
 */
const handleSave = (key: string): void => {
  EditMenu.fetch(editableData[key]).then(
    async (res: EditMenu.returnResponse): Promise<void> => {
      if (isRequestSuccess(res)) {
        delete editableData[key];
        await MenuTree();
      }
    }
  );
};

/**
 * 取消编辑
 * 
 * @param key string 菜单guid
 * @returns void
 */
const handleCancel = async (key: string): Promise<void> => {
  delete editableData[key];
  await MenuTree();
};

const rowSelection = ref({
  checkStrictly: false,
  onChange: (
    _: (string | number)[],
    selectedRows: getMenuTree.Data[]
  ) => {
    selectedRowKeysData.value = selectedRows;
  },
});

/**
 * 添加菜单成功
 * 
 * @param status 状态
 * @returns void
 */
const HandleAddSuccess = (status: boolean): void => {
  if (status) {
    addDialogModel.value = false;
    MenuTree();
  }
};

/**
 * 删除菜单
 * 
 * @returns void
 */
const HandleDeleteMenu = (): void => {
  DeleteMenu.fetch({
    menu_guid: selectedRowKeysData.value.map(
      (v: getMenuTree.Data) => v.menu_guid
    ),
  }).then((res: DeleteMenu.returnResponse) => {
    if (isRequestSuccess(res)) {
      MenuTree();
      selectedRowKeysData.value = [];
    }
  });
};

/**
 * 输入事件
 * 
 * @returns void
 */
const handleInput = (): void => {
  MenuTree()
};

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
const handleCardDelete = (item: any): void => {
  Spinning.value = true;
  DeleteMenu.fetch({
    menu_guid: [item.menu_guid],
  }).then((res: DeleteMenu.returnResponse) => {
    if (isRequestSuccess(res)) {
      MenuTree();
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
  editItem.value = item;
  await (editDialogModel.value = true);
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