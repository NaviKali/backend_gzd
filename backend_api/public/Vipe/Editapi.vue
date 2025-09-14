<template>
  <el-drawer v-model="table" title="编辑轮播图" direction="rtl" size="50%" v-on:close="Load()">
    <el-form :model="form" label-width="120px">
      Eidt_demo_form
    </el-form>
    <el-button type="success" v-on:click="Edit()">编辑</el-button>
  </el-drawer>
</template>
<script setup lang="ts">
import { ref } from 'vue'
import { Load, Upload, getBase64, CloseUpload } from '/src/style.js'
import { Plus } from '@element-plus/icons-vue'
import type { UploadProps, UploadUserFile } from 'element-plus'
import { EditBanner } from '../../../../service/Banner.js'
import { ReadImageSrc } from '/src/config.js'



const props = defineProps({
  data: Object,
})



const table = ref(props)


const form = ref()
//*上传文件
const dialogVisible = ref(false)
const UploadFile = ref()
const UploadName = ref()
const CloseUploadName = ref()
const UploadSrc = ref()


//*文件上传成功
const handlePictureCardPreview: UploadProps['onPreview'] = (uploadFile, fileList) => {
  getBase64(uploadFile.raw).then(res => {
    const params = res.split(',') //*分割类型和base64内容
    UploadFile.value = params[1]
    console.log(uploadFile)
    UploadName.value = uploadFile.name
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


const Edit = (index) => {
  const data = { demo_test_guid: props.data.demo_test_guid, interface_data }
  EditBanner(data).then((res) => {
    console.log(res)
  })
}


</script>
     
     
<style></style>