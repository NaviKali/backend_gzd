<template>
  <el-drawer title="添加轮播图" direction="rtl" size="50%" v-on:close="Load()">
    <el-form :model="form" label-width="120px">
      demo_form
    </el-form>
    <el-button type="success" v-on:click="Add()">添加</el-button>
  </el-drawer>
</template>
<script setup lang="ts">
import { ref } from 'vue'
import { Load, Upload, getBase64, CloseUpload } from '/src/style.js'
import { Plus } from '@element-plus/icons-vue'
import type { UploadProps, UploadUserFile } from 'element-plus'
import { AddBanner } from '../../../../service/Banner.js'
import { ReadImageSrc } from '/src/config.js'


//*上传文件
const dialogVisible = ref(false)
const UploadFile = ref()
const UploadName = ref()
const CloseUploadName = ref()
const UploadSrc = ref('')
const form = ref()

demo_bind





//*文件上传成功
const handlePictureCardPreview: UploadProps['onPreview'] = (uploadFile, fileList) => {
  getBase64(uploadFile.raw).then(res => {
    const params = res.split(',') //*分割类型和base64内容
    UploadFile.value = params[1]
    console.log(uploadFile)
    UploadName.value = uploadFile.name
    UploadNamePictureCardPreview
    const data = { name: uploadFile.name, size: uploadFile.size, file: params[1], type: uploadFile.raw.type }
    Upload(data).then((res) => {
      UploadSrc.value = ReadImageSrc + UploadName.value
    })
  })
}
//*文件移除时
const handleRemove = (uploadFile) => {
  CloseUploadName.value = uploadFile.name
  const data = { name: CloseUploadName.value, type: uploadFile.raw.type }
  CloseUpload(data)
}
//*预览图片
const handlePreview = () => {
  dialogVisible.value = true
}





const Add = () => {
  const data = { interface_data }
  AddBanner(data).then((res) => {
    console.log(res)
  })
}


</script>
     
     
<style></style>