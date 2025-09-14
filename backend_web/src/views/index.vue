<template>
    <main>
        asd
    </main>
</template>
<script lang="ts" setup>
import { onMounted } from 'vue'
import { RouteHashType, RouteHash } from '@/ClassLibrary/RouteHash'


onMounted(() => {
    init()
})

const init = () =>{
    const routeHash = new RouteHash()
    routeHash.listen()//*开启监听    
}

</script>
<style scoped></style>