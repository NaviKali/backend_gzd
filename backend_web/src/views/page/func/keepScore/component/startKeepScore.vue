<template>
   <a-modal title="开始记分" width="80%">
      <a-card title="学生记分">
         <a-form :model="formState" name="basic" :wrapper-col="{ span: 5 }" autocomplete="off" @finish="onFinish">
            <a-form-item label="学生学号" name="student_number" @input="handleInput" allowClear
               :rules="[{ required: true, message: '请输入学生学号!' }]">
               <a-input v-model:value="formState.student_number" placeholder="请输入学生学号" />
            </a-form-item>
            <a-form-item label="记分类型名称" name="student_keepscore_type_guid"
               :rules="[{ required: true, message: '请选择记分类型名称!' }]">
               <a-select allowClear ref="select" v-model:value="formState.student_keepscore_type_guid"
                  placeholder="请选择记分类型名称" style="width: 200px">
                  <a-select-option v-for="(item, index) in studentKeepScoreTypeListData" :key="index"
                     :value="item.student_keepscore_type_guid">
                     <a-tag>{{ item.student_keepscore_type_name }} </a-tag>
                     <a-tag color="green">
                        <template #icon>
                           <PlusOutlined />
                        </template>
                        {{ item.keepscore_num }}
                     </a-tag></a-select-option>
               </a-select>
            </a-form-item>
            <a-form-item label="次数" name="count">
               <a-input-number id="inputNumber" v-model:value="formState.count" :min="1" :max="20" />
            </a-form-item>
            <a-form-item label="日期" name="student_keepscore_date"    :rules="[{ required: true, message: '请选择日期!' }]">
               <a-date-picker v-model:value="formState.student_keepscore_date" placeholder="请选择日期" />
            </a-form-item>
            <a-form-item>
               <a-button type="primary" html-type="submit">提交</a-button>
            </a-form-item>
         </a-form>
      </a-card>
      <a-spin tip="加载中..." :spinning="Spinning">
         <a-card style="margin-top: 10px;">
            <div style="background-color: #ececec; padding: 20px;border-radius: 12px;">
               <a-row :gutter="16">
                  <a-col :span="8" v-for="(item, index) in studentListData" :key="index" style="margin-top: 15px;"
                     @click="selectStudentNumber(item.student_number)">
                     <a-card :bordered="false">
                        <template #title>
                           <p>{{ item.student_name }}
                              <a-tag color="#55acee">
                                 <PaperClipOutlined />
                                 {{ item.student_number }}
                              </a-tag>
                           </p>
                        </template>
                        <a-form :model="item">
                           <a-form-item label="用户性别">
                              <a-tag :color="item.student_sex.value == STUDENT_SEX_MAN ? 'cyan' : 'red'">{{
                                 item.student_sex.text }}</a-tag>
                           </a-form-item>
                           <a-form-item label="学生得分总分">
                              {{ item.student_score_count }}
                           </a-form-item>
                        </a-form>
                     </a-card>
                  </a-col>
               </a-row>
            </div>
         </a-card>
      </a-spin>
      <template #footer>
      </template>
   </a-modal>
</template>
<script setup lang="ts">
import { ref, onUpdated, reactive } from 'vue'
import { isRequestSuccess } from '~/server'
import { debounce } from 'lodash-es'
import { getStudentList, STUDENT_SEX_MAN, STUDENT_SEX_WOMAN } from '~/server/student/index'
import { getStudentKeepscoreTypeList, addStudentKeepscore } from '~/server/student_keepscore/index.ts'
import dayjs from 'dayjs';


const date = new Date()
const Spinning = ref<boolean>(false);
const studentListData = ref<getStudentList.Data[]>([]);
const studentKeepScoreTypeListData = ref<getStudentKeepscoreTypeList.Data[]>([]);
const formState = reactive<addStudentKeepscore.params & { count: number }>({
   count: 1,
   student_keepscore_date: dayjs(`${date.getFullYear()}-${date.getMonth() + 1}-${date.getDate()}`, "YYYY-M-D"),
});



onUpdated(() => {
   getStudentListFetch({ isAll: true })
   getStudentKeepscoreTypeListFetch()
})


const getStudentKeepscoreTypeListFetch = (): void => {
   getStudentKeepscoreTypeList.fetch().then((res: getStudentKeepscoreTypeList.returnResponse): void => {
      if (isRequestSuccess(res)) studentKeepScoreTypeListData.value = res.data.list
   })
}

/**
 * 获取学生列表
 * 
 * @returns void
 */
const getStudentListFetch = (params: getStudentList.params = null): void => {
   if (!params)
      formState.isAll = true
   Spinning.value = true
   getStudentList.fetch(!params ? formState : params).then(async (res: getStudentList.returnResponse): Promise<void> => {
      if (isRequestSuccess(res)) {
         studentListData.value = res.data.list
         await (Spinning.value = false)
      }
   })
}

/**
 * 提交记分
 * 
 * @returns void
 */
const onFinish = debounce(async (values: any): Promise<void> => {
   let date = new Date(formState.student_keepscore_date).toLocaleDateString('zh-CN')
   await addStudentKeepscore.fetch({
      student_number: formState.student_number,
      student_keepscore_type_guid: formState.student_keepscore_type_guid,
      student_keepscore_date: date,
      count:formState.count,
   }).then((res: addStudentKeepscore.returnResponse): void => {
      if (isRequestSuccess(res)) {
         getStudentListFetch({isAll:true})
      }
   })
}, 200);

/**
 * 选择学生学号
 * 
 * @returns void
 */
const selectStudentNumber = (num: number): void => {
   formState.student_number = num
}

const handleInput = debounce((): void => {
   getStudentListFetch()
}, 200)

</script>
<style scoped lang="less"></style>
