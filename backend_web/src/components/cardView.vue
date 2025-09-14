<template>
    <a-card style="width: 300px" v-for="(item, index) in props.data" :key="index" @click="handleClick(item)" class="CardViewItem">
        <template #title>
            <slot name="title" :item="item"></slot>
        </template>
        <template #extra>
            <slot name="extra" :item="item"></slot>
        </template>
        <template #actions>
            <edit-outlined key="edit" @click="handleEdit(item)" v-if="props.defaultActions" />
            <DeleteOutlined key="delete" @click="handleDelete(item)" v-if="props.defaultActions" />
            <slot v-else name="actions" :item="item"></slot>
        </template>
        <slot name="content" :item="item"></slot>
    </a-card>
</template>
<script setup lang="ts">
import { defineProps } from 'vue'

const props = defineProps({
    data: {
        type: Array,
        required: true,
        default: () => []
    },
    handleClick: {
        type: Function,
        required: false,
        default: () => {}
    },
    defaultActions: {
        type: Boolean,
        required: false,
        default: true
    },
    handleEdit:{
        type: Function,
        required: false,
        default:()=>{}
    },
    handleDelete:{
        type: Function,
        required: false,
        default:()=>{}
    },
})

</script>
<style scoped>
.CardViewItem:hover{
    transition: all 0.3s;
    box-shadow: 0 0 10px 1px rgba(0, 0, 0, 0.1);
}
</style>