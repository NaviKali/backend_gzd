<template>
    <main>
        <a-result status="404" title="404" sub-title="没有找到对应页面">
            <template #extra>
                <a-button class="Shadow" @click="handleBack">返回上级</a-button>
                <a-button type="primary" @click="handleGoHome">返回首页</a-button>
            </template>
        </a-result>
    </main>
</template>
<script setup lang="ts">
import { inject } from 'vue'
import { useRouter, type Router } from 'vue-router'
import type { changeRouterType } from '@/views/index'

const router: Router = useRouter()
const changeRouterVoid: changeRouterType = inject("changeRouter")!

const handleBack = (): void => {
    changeRouterVoid("#")
    router.back()
}


const handleGoHome = (): void => {
    changeRouterVoid("/home")
}

</script>
<style scoped>
main {
    display: flex;
    justify-content: center;
    align-content: center;
    align-items: center;
}
</style>