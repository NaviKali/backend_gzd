<template>
    <main>
        <header>
            <a-breadcrumb>
                <a-breadcrumb-item>功能模块</a-breadcrumb-item>
                <a-breadcrumb-item>记分</a-breadcrumb-item>
            </a-breadcrumb>
        </header>
        <header>
            <a-button type="primary" @click="startKeepScoreModel = true">开始记分</a-button>
            <a-button type="primary" @click="keepScoreTypeModel = true">记分类型管理</a-button>
        </header>
    </main>
    <keepScoreTypeView v-model:open="keepScoreTypeModel"/> 
    <startKeepScoreView v-model:open="startKeepScoreModel" />
</template>
<script setup lang="ts">
import {ref} from 'vue'
import keepScoreTypeView from './component/keepScoreType.vue';
import startKeepScoreView from './component/startKeepScore.vue';


const keepScoreTypeModel = ref<boolean>(false);
const startKeepScoreModel= ref<boolean>(false);

</script>
<style scoped>
header {
    margin-bottom: 20px;
}

.ant-btn:nth-child(2n) {
    margin-left: 5px;
}
</style>