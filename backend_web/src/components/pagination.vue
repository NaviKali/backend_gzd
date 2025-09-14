<template>
  <a-pagination v-model:current="current" v-model:page-size="pageSizeRef" :page-size-options="pageSizeOptions"
    :total="total" show-size-changer @showSizeChange="onShowSizeChange" @change="onChange">
    <template #buildOptionText="props">
      <span v-if="props.value !== '120'">{{ props.value }}条/页</span>
      <span v-else>全部</span>
    </template>
  </a-pagination>
</template>
<script setup lang="ts">
import { onUpdated, ref } from 'vue';

const props = defineProps({
  count: {
    type: Number,
    required: true,
    default: 0
  }
})

const pageSizeOptions = ref<string[]>(['10', '20', '30', '40', '50', '100']);//分页多选器
const current = ref<number>(1);
const pageSizeRef = ref<number>(10);
const total = ref<number>(0);
const emits = defineEmits(["handlePageChange"])

onUpdated(() => {
  total.value = props.count
})

const onShowSizeChange = (_: number, pageSize: number): void => {
  pageSizeRef.value = pageSize;
};
const onChange = (page: number, pageSize: number): void => {
  emits("handlePageChange", {
    page: page <= 0 ? 1 : page,
    limit: pageSize
  })
}

</script>
<style scoped></style>
