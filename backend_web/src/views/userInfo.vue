<template>
  <a-descriptions title="个人信息" layout="vertical" bordered>
    <a-descriptions-item label="姓名">{{
      userInfo().user_name
    }}</a-descriptions-item>
    <a-descriptions-item label="手机号">
      <a-tag color="default">
        <template #icon>
          <PhoneOutlined />
        </template>
        {{ userInfo().user_phone }}
      </a-tag>
    </a-descriptions-item>
    <a-descriptions-item label="电子邮箱">
      <a-tag color="default">
        <template #icon>
          <PaperClipOutlined />
        </template>
        {{ userInfo().user_email }}
      </a-tag>
    </a-descriptions-item>
    <a-descriptions-item label="状态" :span="1">
      <a-badge status="processing" :text="userInfo().user_status_name" />
    </a-descriptions-item>
    <a-descriptions-item label="注册时间" :span="2">{{
      userInfo().create_datetime
    }}</a-descriptions-item>
    <a-descriptions-item label="个人头像" :span="3">
      <a-upload v-if="userInfo().user_image != undefined" :file-list="fileList"  :maxCount="1"
        list-type="picture-card" supportServerRender 
        @preview="handlePreview" 
        @remove="RemoveImage"
        :before-upload="beforeUpload">
        <div>
          <plus-outlined />
          <div style="margin-top: 8px">重新上传头像</div>
        </div>
      </a-upload>
      <a-modal :open="previewVisible" :title="previewTitle" :footer="null" @cancel="handleCancel">
        <img alt="example" style="width: 100%" :src="previewImage" />
      </a-modal>
    </a-descriptions-item>
    <a-descriptions-item label="个人介绍">
      {{
        userInfo().user_information == "" ||
          userInfo().user_information == undefined
          ? "暂无介绍"
          : userInfo().user_information
      }}
    </a-descriptions-item>
  </a-descriptions>
</template>
<script lang="ts" setup>
import { inject, reactive, ref } from "vue";
import { getUserInfo } from "~";
import { getUploadsUrl } from "~/server/index";
import type { UploadProps } from "ant-design-vue";
import { uploadsImage } from "~/server/user/index";
import { isRequestSuccess } from "../server";

const userInfo: getUserInfo = inject("getUserInfo")!;


const fileList = ref<{
  name: string | String,
  url: string | String,
}[]>([{
  name: userInfo().user_image,
  url: getUploadsUrl(userInfo().user_image),
}]);


const previewVisible = ref<boolean>(false);
const previewImage = ref<string>("");
const previewTitle = ref<string>("");

const handleCancel = (): void => {
  previewVisible.value = false;
  previewTitle.value = "";
};

const beforeUpload: UploadProps['beforeUpload'] = async (file):Promise<boolean> => {
 const formData = new FormData();
  formData.append('file', file);
  await uploadsImage.fetch(formData).then((res: uploadsImage.returnResponse) => {
    if(isRequestSuccess(res)){
      userInfo().user_image = res.data.fileName;
      fileList.value.push({
        name: res.data.fileName,
        url: getUploadsUrl(res.data.fileName),
      });
    }
  })
  return false;
};

function getBase64(file: File): any {
  return new Promise((resolve, reject) => {
    const reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = () => resolve(reader.result);
    reader.onerror = (error) => reject(error);
  });
}

const RemoveImage = async (file: any): Promise<void> => {
  fileList.value = [];
  await uploadsImage.fetch({
    filename: file.name,
  })
};

const handlePreview = async (
  file: UploadProps["fileList"][number]
): Promise<void> => {
  if (!file.url && !file.preview) {
    file.preview = (await getBase64(file.originFileObj)) as string;
  }
  previewImage.value = file.url || file.preview;
  previewVisible.value = true;
  previewTitle.value =
    file.name || file.url.substring(file.url.lastIndexOf("/") + 1);
};
</script>
<style scoped></style>