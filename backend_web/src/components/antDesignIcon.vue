<template>
     <a-modal
      title="AntDesign-Icon"
      width="100%"
      wrap-class-name="full-modal"
    >
    <iframe :src="antData.icon_page_url" frameborder="0" style="width: 100%;height: 70vh;"></iframe>
    <template #footer>
    </template>
</a-modal>
</template>
<script setup lang="ts">
import {antData} from '~/AntDesign.ts'
</script>
<style scoped lang="less">
.full-modal {
  .ant-modal {
    max-width: 100%;
    top: 0;
    padding-bottom: 0;
    margin: 0;
  }
  .ant-modal-content {
    display: flex;
    flex-direction: column;
    height: calc(100vh);
  }
  .ant-modal-body {
    flex: 1;
  }
}

</style>