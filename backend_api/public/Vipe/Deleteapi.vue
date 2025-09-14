<template>
     <el-drawer v-model="table" title="删除菜单" direction="rtl"
     size="50%" v-on:close="Load()">
     <el-form :model="form" label-width="120px">
         <p>你确定要删除吗?</p>
         <el-button type="danger" @click="Delete()">删除</el-button>
         </el-form>
     
     </el-drawer>
 </template>
 <script setup lang="ts">    
 import {ref} from 'vue'
 import { Load } from '/src/style.js'
 import {DeleteMenu} from '../../../../service/Menu.js'
 const form = ref()
 const props = defineProps({
     data: Object,
   })
 const table = props
 const Delete =() =>{
     const data = {demo_test_guid:props.data.demo_test_guid}
     DeleteMenu(data)
 }
 </script>
 <style></style>