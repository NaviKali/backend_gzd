<template>
    <a-select ref="select" style="width: 250px" show-search>
        <a-select-option v-for="(item, index) in Object.keys(IconData)" :key="index" :value="item">
            <component :is="$antIcons[item]" /> <span>{{ item }}</span>
        </a-select-option>
    </a-select>
</template>
<script setup lang="ts">
import * as icons from '@ant-design/icons-vue';
const IconData: Record<string, any> = icons

</script>
<style scoped></style>