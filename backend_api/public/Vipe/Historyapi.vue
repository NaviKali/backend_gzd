<template>
    <el-drawer title="历史记录" direction="rtl" size="65%" v-on:close="Load()">
        <el-form :model="form" label-width="120px">
        <!-- 表格 -->
      <el-table :data="tableData" border style="width: 100%" empty-text="没有数据!" #default="scope"
      @select-all="handleSelectionChange" @select="handleSelection">
      <el-table-column type="selection" width="55" />
      <el-table-column v-for="item, index in column" v-model="column" :prop="item.prop" :label="item.label"
         :width="item.width">

      </el-table-column>
      <el-table-column  fixed="right"  width="280" label="操作" show-overflow-tooltip #default="scope">
         <main>
            <el-button type='success' @click='Restore(scope.row)'>恢复</el-button>
            <el-button type='success' @click='Force(scope.row)'>彻底删除</el-button>

         </main>
      </el-table-column>

   </el-table>
        </el-form>
      </el-drawer>
</template>
<script setup lang="ts">
import {ref,reactive,onMounted} from 'vue'
import {getTrasheBanner,RestoreTrasheBanner,ForceTrasheBanner} from '../../../../service/Banner'
import { Load } from '../../../../style'; 


// 列表内容
const tableData = ref()
// 获取当行数据
const Row_List = ref()
const Row = reactive(Row_List)
// 列表List渲染
const column = reactive([

demo_List

{
   label: '删除时间',
   prop: 'demo_order_delete_time',
   width: 200,
},


])

onMounted(()=>
{
    getTrasheBanner().then((res)=>
    {
        tableData.value = res.data
    })
})


//*恢复历史记录
const Restore =(index) =>
{
    const data = {demo_order_guid:index.demo_order_guid}
    RestoreTrasheBanner(data)
}

//*彻底删除
const Force = (index)=>
{
    const data =  {demo_order_guid:index.demo_order_guid}
    ForceTrasheBanner(data)
}

</script>
<style></style>