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
                        <a-form-item label="学生姓名">
                            <a-input v-model:value="formState.student_name" placeholder="请输入学生姓名" @input="handleInput"
                                allowClear />
                        </a-form-item>
                        <a-form-item label="学生性别">
                            <a-select ref="select" v-model:value="formState.student_sex" style="width: 200px"
                                @change="handleInput" placeholder="请选择学生性别" allowClear>
                                <a-select-option v-for="(item, index) in StudentSexArr" :key="index"
                                    :value="item.key">{{ item.value
                                    }}</a-select-option>
                            </a-select>
                        </a-form-item>
                        <a-form-item label="学生学号">
                            <a-input v-model:value="formState.student_number" placeholder="请输入学生学号" @input="handleInput"
                                allowClear />
                        </a-form-item>
                    </a-form>
                </a-collapse-panel>
                <a-collapse-panel key="where" header="操作集合" :show-arrow="false">
                    <a-button type="primary" @click="addDialogModel = true">添加学生</a-button>
                    <a-button type="primary" danger v-if="selectedRowKeysData.length != 0"
                        @click="HandleDeleteStudent">删除学生</a-button>
                </a-collapse-panel>
                <a-collapse-panel key="do" header="功能" :show-arrow="false">
                    <a-button @click="handleChangeView">切换视图</a-button>
                    <a-button @click="echarttDialogModel = true">图表</a-button>
                </a-collapse-panel>
            </a-collapse>
        </header>
        <a-spin tip="加载中..." :spinning="Spinning" v-if="userInfo().getUserConfig">
            <!-- 表格视图 -->
            <a-table :row-selection="rowSelection" :columns="tableColumns" :data-source="data" class="Shadow"
                :scroll="{ x: 'calc(700px + 50%)', y: 500 }"
                v-if="userInfo().getUserConfig.user_config_view_model == VIEW_MODEL_TABLE" :pagination="false">
                <template #bodyCell="{ column, text, record }">
                    <template v-if="column.dataIndex == 'student_name'">
                        <a-tag>
                            <template #icon>
                                <UserOutlined />
                            </template>
                            {{ text }}</a-tag>
                    </template>
                    <template v-if="column.dataIndex == 'student_sex'">
                        <a-tag :color="text.value == STUDENT_SEX_MAN ? 'cyan' : 'red'">{{ text.text }}</a-tag>
                    </template>
                    <template v-if="column.dataIndex == 'student_number'">
                        <a-tag color="#55acee">
                            <template #icon>
                                <PaperClipOutlined />
                            </template>
                            {{ text }}
                        </a-tag>
                    </template>
                    <template v-if="column.dataIndex === 'create_datetime'">
                        <a-tag color="processing">{{ text }}</a-tag>
                    </template>
                    <template v-if="column.dataIndex == 'operation'">
                        <a-button type="primary" @click="showItem(record)">详情</a-button>
                    </template>
                </template>
            </a-table>
            <!-- 卡片视图 -->
            <div class="card-view" v-if="userInfo().getUserConfig.user_config_view_model == VIEW_MODEL_CARD">
                <cardView :defaultActions="true" :data="data" :handleClick="handleCardClick"
                    :handleEdit="handleCardEdit" :handleDelete="handleCardDelete">
                    <template #title="{ item }">
                        <p>{{ item.student_name }}
                            <a-tag color="#55acee">
                                <PaperClipOutlined />
                                {{ item.student_number }}
                            </a-tag>
                        </p>
                    </template>
                    <template #content="{ item }">
                        <a-form :model="item">
                            <a-form-item label="用户性别">
                                <a-tag :color="item.student_sex.value == STUDENT_SEX_MAN ? 'cyan' : 'red'">{{
                                    item.student_sex.text }}</a-tag>
                            </a-form-item>
                            <a-form-item label="学生得分总分">
                                {{ item.student_score_count }}
                            </a-form-item>
                        </a-form>
                    </template>
                </cardView>
            </div>
            <!-- 分页 -->
            <a-card class="PagePacka Shadow">
                <pagination @handlePageChange="handlePageChange" :count="dataCount" />
            </a-card>
        </a-spin>
        <addDialogView v-model:open="addDialogModel" @HandleAddSuccess="HandleSuccess" />
        <itemDialogView v-model:open="itemDialogModel" :guid="itemGuid" @handleSuccess="HandleSuccess" />
        <echarttDialogView v-model:open="echarttDialogModel" />
    </main>
</template>
<script setup lang="ts">
import { ref, reactive, onMounted, inject } from 'vue';
import { getStudentList, tableColumns, StudentSex, STUDENT_SEX_MAN, STUDENT_SEX_WOMAN, deleteStudent } from '~/server/student/index'
import type { getUserInfo, changeViewModelType } from '~/views';
import { VIEW_MODEL_TABLE, VIEW_MODEL_CARD } from "~/views";
import { isRequestSuccess } from '~/server';
import cardView from "~/components/cardView.vue";
import pagination from '~/components/pagination.vue';
import addDialogView from './component/addDialog.vue';
import itemDialogView from './component/itemDialog.vue';
import echarttDialogView from './component/echarttDialog.vue';
import { debounce } from 'lodash-es'


const addDialogModel = ref<boolean>(false);
const itemDialogModel = ref<boolean>(false);
const echarttDialogModel = ref<boolean>(false);
const itemGuid = ref<string>('');

const activeKey = ref<string>("where");
const Spinning = ref<boolean>(false);
const formState = reactive<{
    student_name: string
    student_number: number | null
    student_sex: number | null
}>({
    student_name: String(),
    student_number: null,
    student_sex: null
});
const selectedRowKeysData = ref<getStudentList.Data[]>([])
const data = ref<getStudentList.Data[]>([])
const dataCount = ref<number>(0);
const userInfo = inject<getUserInfo>("getUserInfo")!;
const changeViewModel = inject<changeViewModelType>("changeViewModel")!;
const StudentSexArr = ref<{ key: string, value: string }[]>([
    {
        key: STUDENT_SEX_MAN,
        value: StudentSex.STUDENT_SEX_MAN
    },
    {
        key: STUDENT_SEX_WOMAN,
        value: StudentSex.STUDENT_SEX_WOMAN
    }
])

onMounted((): void => {
    getStudentListFetch()
})


const HandleSuccess = (status: boolean): void => {
    if (status)
        getStudentListFetch()
}


const showItem = (record: getStudentList.Data) => {
    itemGuid.value = record.student_guid
    itemDialogModel.value = true
}



/**
 * 分页操作
 * 
 * @returns void
 */
const handlePageChange = (page: object): void => {
    getStudentListFetch({ ...formState, ...page })
}

/**
 * 获取学生列表
 * 
 * @returns void
 */
const getStudentListFetch = (params: getStudentList.params = null): void => {
    Spinning.value = true
    getStudentList.fetch(!params ? formState : params).then(async (res: getStudentList.returnResponse): Promise<void> => {
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
        selectedRows: getStudentList.Data[]
    ) => {
        selectedRowKeysData.value = selectedRows;
    },
});


/**
 * 搜索
 * 
 * @debounce
 * @returns void
 */
const handleInput = debounce((): void => {
    getStudentListFetch()
}, 200)

/**
 * 切换试图
 * 
 * @returns void
 */
const handleChangeView = (): void => {
    changeViewModel()
};



/**
 * 删除
 * 
 * @returns void
 */
const HandleDeleteStudent = (_: any, guid: string | null = null): void => {
    console.log(guid);

    Spinning.value = true
    deleteStudent.fetch({
        student_guid: guid ? Array(guid) : selectedRowKeysData.value.map(
            (v: getStudentList.Data) => v.student_guid
        ),
    }).then((res: deleteStudent.returnResponse) => {
        if (isRequestSuccess(res)) {
            getStudentListFetch()
            selectedRowKeysData.value = [];
            Spinning.value = false
        }
    });
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
const handleCardDelete = async (item: getStudentList.Data): Promise<void> => {
    HandleDeleteStudent(null, item.student_guid)
};

/**
 * 卡片编辑
 * 
 * @param item any 菜单
 * @returns void
 */
const handleCardEdit = async (_: any): Promise<void> => { };


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