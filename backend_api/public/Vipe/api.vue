<template>
   <div class="view-container">
      <!--面包屑 -->
      <el-breadcrumb :separator-icon="ArrowRight">
         <el-breadcrumb-item :to="{ path: '/' }">首页</el-breadcrumb-item>
         <el-breadcrumb-item>title_headers</el-breadcrumb-item>
      </el-breadcrumb>
      <!--搜索项 -->
      <div class="Where">
         add_search

      </div>

      <!--选择项 -->
      <div class="Select">
         add_view

         <!-- 批量操作 -->
         <el-dropdown class="DropDown">
            <el-button type="primary">
               更多操作
               <el-icon class="el-icon--right"><arrow-down /></el-icon>
            </el-button>
            <template #dropdown>
               <el-dropdown-menu>
                  <el-dropdown-item @click="Batch_Delete()">批量删除</el-dropdown-item>
                  <el-dropdown-item @click="HistoryBannerView = true">历史记录</el-dropdown-item>

               </el-dropdown-menu>
            </template>
         </el-dropdown>

      </div>
      <!-- 表格 -->
      <el-table :data="tableData" border style="width: 100%" empty-text="没有数据!" #default="scope"
         @select-all="handleSelectionChange" @select="handleSelection">
         <el-table-column type="selection" width="55" />
         <el-table-column v-for="item, index in column" v-model="column" :prop="item.prop" :label="item.label"
            :width="item.width">

         </el-table-column>
         <el-table-column label="操作" show-overflow-tooltip #default="scope">
            <main>
               demo_view
            </main>
         </el-table-column>

      </el-table>

   </div>
   demos_views
   <HistoryBanner v-model="HistoryBannerView" />
</template>
<script setup lang="ts">
import { ref, reactive, onMounted } from 'vue'
import { getBannerList, SearchBanner, BatchDeleteBanner } from '../../../service/Banner'
import { ElTable } from 'element-plus'
import { ElMessage } from 'element-plus'

import HistoryBanner from './component/HistoryBanner.vue'
demo_import



demo_status
const HistoryBannerView = ref(false)

const multipleSelection = ref<User[]>([])
const UploadName = ref('')


demo_font_bind




// 列表内容
const tableData = ref()
// 获取当行数据
const Row_List = ref()
const Row = reactive(Row_List)
// 列表List渲染
const column = reactive([

   demo_List

   {
      label: '创建时间',
      prop: 'demo_order_create_time',
      width: 200,
   },
])

onMounted(() => {
   getBannerList().then((res) => {
      tableData.value = res.data
   })
})


/**
 * 搜索功能
 * @author L
 */
//*搜索
const Search = () => {
   const data = { Search_interface }
   SearchBanner(data).then((res) => {
      tableData.value = res.data
   })
}
/**
 * 获取当前行数据
 * @author L
 */
function handleUpdate(index) {
   Row_List.value = index
}


/**
 * 批量操作
 * @author L
 */
//*全选获取内容
const handleSelectionChange = (val: User[]) => {
   multipleSelection.value = val
}
//*选取一条或多条行数内容
const handleSelection = (select) => {
   multipleSelection.value = select
}
//*批量删除
const Batch_Delete = () => {
   //*没有对象则扔出异常
   if (multipleSelection.value.length == 0) {
      ElMessage.error('请选择对象!')
      console.error("没有选择需求对象!");
   } else {
      const data = { select_Banner: multipleSelection.value };
      BatchDeleteBanner(data).then((res) => {
         console.log(res);
      })

   }
}








</script>
<style>
.DropDown {
   position: relative;
   top: -20px;
   left: 50px;
}
</style>