<template>
     <div class="view-container">
          <!--面包屑 -->
          <el-breadcrumb :separator-icon="ArrowRight">
               <el-breadcrumb-item :to="{ path: '/' }">首页</el-breadcrumb-item>
               <el-breadcrumb-item>部署通知</el-breadcrumb-item>
          </el-breadcrumb>
          <el-form :model="form">
               <el-form-item label="部署通知标题">
                    <el-col :span="6" :offset="0">
                         <el-input v-model="title" disabled placeholder="" size="normal" clearable></el-input>
                    </el-col>
               </el-form-item>
               <el-form-item label="部署通知作者">
                    <el-col :span="6" :offset="0">
                         <el-input v-model="by" disabled placeholder="" size="normal" clearable></el-input>
                    </el-col>
               </el-form-item>
               <el-form-item label="部署通知内容">
                    <el-col :span="12" :offset="0">
                         <el-input v-model="content" disabled placeholder=""
                         :autosize="{ minRows: 12, maxRows: 12 }"
                         size="normal"  type="textarea" clearable></el-input>
                    </el-col>
               </el-form-item>
          </el-form>
          
     </div>
</template>
<script setup lang="ts">
import { ref, reactive, onMounted } from 'vue'

const form = ref()
const title = ref("title_data")
const by = ref("by_data")
const content =ref("content_data")



</script>
<style></style>