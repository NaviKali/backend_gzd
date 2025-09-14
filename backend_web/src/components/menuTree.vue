<template>
  <div v-for="item in props.data" :key="item.menu_to">
    <a-menu-item
      v-if="item.children.length == 0"
      @click="menuClick(item)"
      :key="item.menu_to"
    >
      <component :is="$antIcons[item.menu_icon]" />
      <span>{{ item.menu_name }}</span>
    </a-menu-item>
    <a-sub-menu :key="item.menu_guid" v-else>
      <template #title>
        <component :is="$antIcons[item.menu_icon]" />
        <span>{{ item.menu_name }}</span>
      </template>
      <MenuComponent
        :data="item.children"
        @HandleMenuRouterTo="menuClick"
      />
    </a-sub-menu>
  </div>
</template>
<script setup lang="ts">
import MenuComponent from "~/components/menuTree.vue";

const props = defineProps({
  data: Object,
});

const emits = defineEmits(["HandleMenuRouterTo"]);

const menuClick = (item: { menu_to: string } | string) => {
  emits("HandleMenuRouterTo", typeof item == "object" ? item.menu_to : item);
};
</script>
<style scoped>
</style>