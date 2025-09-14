<template>
  <main>
    <header>
      <a-breadcrumb>
        <a-breadcrumb-item>用户管理</a-breadcrumb-item>
        <a-breadcrumb-item>用户列表</a-breadcrumb-item>
      </a-breadcrumb>
    </header>
    <header>
      <a-collapse v-model:activeKey="activeKey" expand-icon-position="end">
        <a-collapse-panel key="where" header="筛选查询">
          <a-form layout="inline" :model="formState">
            <a-form-item label="用户名称">
              <a-input v-model:value="formState.user_name" placeholder="请输入用户姓名" @input="handleInput" />
            </a-form-item>
            <a-form-item label="用户性别">
              <a-select ref="select" v-model:value="formState.user_sex" style="width: 120px" @change="handleInput"
                placeholder="请选择用户性别">
                <a-select-option v-for="(item, index) in userSexSelect" :key="index" :value="item.key">{{ item.value
                }}</a-select-option>
              </a-select>
            </a-form-item>
            <a-form-item label="手机号">
              <a-input v-model:value="formState.user_phone" placeholder="请输入手机号" @input="handleInput" />
            </a-form-item>
          </a-form>
        </a-collapse-panel>
        <a-collapse-panel key="do" header="功能" :show-arrow="false">
          <a-button @click="handleChangeView">切换视图</a-button>
        </a-collapse-panel>
      </a-collapse>
    </header>
    <a-spin tip="加载中..." :spinning="Spinning" v-if="userInfo().getUserConfig">
      <!-- 表格视图 -->
      <a-table :row-selection="rowSelection" :columns="tableColumns" :data-source="data" class="Shadow"
        :scroll="{ x: 'calc(700px + 50%)', y: 500 }"
        v-if="userInfo().getUserConfig.user_config_view_model == VIEW_MODEL_TABLE" :pagination="false">
        <template #bodyCell="{ column, text }">
          <template v-if="column.dataIndex == 'user_name'">
            <a-tag>
              <template #icon>
                <UserOutlined />
              </template>
              {{ text }}</a-tag>
          </template>
          <template v-if="column.dataIndex == 'user_sex'">
            <a-tag :color="text.value == USER_SEX_MAN ? 'cyan' : 'red'">{{ text.text }}</a-tag>
          </template>
          <template v-if="column.dataIndex == 'user_role_name'">
            <a-tag color="#55acee">
              <template #icon>
                <IdcardOutlined />
              </template>
              {{ text  ?? "暂无职务"}}
            </a-tag>
          </template>
          <template v-if="column.dataIndex == 'user_image'">
            <a-upload :file-list="[{
              name: text,
              url: getUploadsUrl(text)
            }]" :max="1" list-type="picture-card">
            </a-upload>
          </template>
          <template v-if="column.dataIndex == 'user_phone'">
            <a-tag color="#55acee">
              <template #icon>
                <PhoneOutlined />
              </template>
              {{ text }}
            </a-tag>
          </template>
          <template v-if="column.dataIndex == 'user_email'">
            <a-tag color="#3b5999">
              <template #icon>
                <AliwangwangOutlined />
              </template>
              {{ text }}
            </a-tag>
          </template>
          <template v-if="column.dataIndex === 'create_datetime'">
            <a-tag color="processing">{{ text }}</a-tag>
          </template>
          <template v-if="column.dataIndex == 'operation'">
            <a-button type="primary">详情</a-button>
          </template>
        </template>
        <template #footer>
          <pagination @handlePageChange="handlePageChange" :count="dataCount" />
        </template>
      </a-table>
      <!-- 卡片视图 -->
      <div class="card-view" v-if="userInfo().getUserConfig.user_config_view_model == VIEW_MODEL_CARD">
        <cardView :defaultActions="false" :data="data" :handleClick="handleCardClick" :handleEdit="handleCardEdit"
          :handleDelete="handleCardDelete">
          <template #title="{ item }">
            <p>{{ item.user_name }}</p>
          </template>
          <template #content="{ item }">
            <a-form layout="inline" :model="item">
              <a-form-item label="用户性别">
                <a-tag :color="item.user_sex.value == USER_SEX_MAN ? 'cyan' : 'red'">{{ item.user_sex.text }}</a-tag>
              </a-form-item>
              <a-form-item label="手机号">
                <a-tag color="#55acee">
                  <template #icon>
                    <PhoneOutlined />
                  </template>
                  {{ item.user_phone }}
                </a-tag>
              </a-form-item>
              <a-form-item label="用户职务">
                <a-tag color="#55acee">
              <template #icon>
                <IdcardOutlined />
              </template>
              {{ item.user_role_name  ?? "暂无职务"}}
            </a-tag>
              </a-form-item>
              <a-form-item label="邮箱">
                <a-tag color="#3b5999">
                  <template #icon>
                    <AliwangwangOutlined />
                  </template>
                  {{ item.user_email }}
                </a-tag>
              </a-form-item>
              <a-form-item label="用户头像">
                <a-upload :file-list="[{
                  name: item.user_image,
                  url: getUploadsUrl(item.user_image)
                }]" :max="1" list-type="picture-card">
                </a-upload>
              </a-form-item>
              <a-form-item label="用户信息">
                <a-tag>{{ item.user_information ?? "无" }}</a-tag>
              </a-form-item>
            </a-form>
          </template>
        </cardView>
      </div>
    </a-spin>
  </main>
</template>
<script setup lang="ts">
import { ref, reactive, onMounted, inject } from 'vue';
import { getUserList, tableColumns, USER_SEX_MAN, USER_SEX_WOMAN, UserSex } from '~/server/user/index'
import type { getUserInfo, changeViewModelType } from '~/views';
import { VIEW_MODEL_TABLE, VIEW_MODEL_CARD } from "~/views";
import { isRequestSuccess } from '~/server';
import { getUploadsUrl } from '~/server'
import cardView from "~/components/cardView.vue";
import pagination from '~/components/pagination.vue';


const activeKey = ref<string>("where");
const Spinning = ref<boolean>(false);
const formState = reactive<{
  user_name: string
  user_phone: string
  user_sex: number | null
}>({
  user_name: "",
  user_phone: "",
  user_sex: null
});
const selectedRowKeysData = ref<getUserList[]>([])
const data = ref<getUserList[]>([])
const dataCount = ref<number>(0);
const userInfo = inject<getUserInfo>("getUserInfo")!;
const changeViewModel = inject<changeViewModelType>("changeViewModel")!;
const userSexSelect = ref<{ key: string, value: string }[]>([
  {
    key: USER_SEX_MAN,
    value: UserSex.USER_SEX_MAN
  },
  {
    key: USER_SEX_WOMAN,
    value: UserSex.USER_SEX_WOMAN
  }
])

onMounted((): void => {
  getUserListFetch()
})


/**
 * 分页操作
 * 
 * @returns void
 */
const handlePageChange = (page: object): void => {
  getUserListFetch({ ...formState, ...page })
}

/**
 * 获取用户列表
 * 
 * @returns void
 */
const getUserListFetch = (params: getUserList.params = null): void => {
  Spinning.value = true
  getUserList.fetch(!params ? formState : params).then(async (res: getUserInfo.returnResponse): Promise<void> => {
    if (isRequestSuccess(res)) {
      data.value = res.data.list
      dataCount.value = res.data.count
      await (Spinning.value = false)
    }
  })
}

/**
 * 多选
 */
const rowSelection = ref({
  checkStrictly: false,
  onChange: (
    _: (string | number)[],
    selectedRows: getUserList.Data[]
  ) => {
    selectedRowKeysData.value = selectedRows;
  },
});


/**
 * 搜索
 * 
 * @returns void
 */
const handleInput = (): void => {
  getUserListFetch()
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
const handleCardClick = (_: any): void => { };

/**
 * 卡片删除
 * 
 * @param item any 菜单
 * @returns void
 */
const handleCardDelete = async (_: any): Promise<void> => {
};

/**
 * 卡片编辑
 * 
 * @param item any 菜单
 * @returns void
 */
const handleCardEdit = async (_: any): Promise<void> => {
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