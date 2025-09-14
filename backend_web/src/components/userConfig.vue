<template>
  <a-modal centered title="配置信息" :footer="null" style="width: 50%">
    <a-tabs v-model:activeKey="activeKey">
      <template #rightExtra>
        <a-tag color="red">修改配置信息需要刷新</a-tag>  
      </template>
      <a-tab-pane key="1" tab="基本设置">
        <a-form :model="formState" name="basic" autocomplete="off">
          <a-form-item label="菜单开启折叠" name="user_config_menu_collapsed">
            <a-switch
              @change="handleChange"
              v-model:checked="formState.user_config_menu_collapsed"
            />
          </a-form-item>
          <a-form-item label="是否开启脚注" name="user_config_footnote">
            <a-switch
              @change="handleChange"
              v-model:checked="formState.user_config_footnote"
            />
          </a-form-item>
          <a-form-item label="是否开启水印" name="user_config_watermark">
            <a-switch
              @change="handleChange"
              v-model:checked="formState.user_config_watermark"
            />
          </a-form-item>
            <a-form-item
              label="通知位置"
              name="user_config_notification_position	"
            >
              <a-radio-group
                v-model:value="formState.user_config_notification_position"
                @change="handleChange"
              >
                <a-radio-button
                  v-for="(item, index) in positionArr"
                  :key="index"
                  :value="item"
                  >{{ item }}</a-radio-button
                >
              </a-radio-group>
            </a-form-item>
            <a-form-item label="视图模式" name="user_config_view_model">
              <a-radio-group
                v-model:value="formState.user_config_view_model"
                @change="handleChange"
              >
                <a-radio-button
                  v-for="(item, index) in viewModelArr"
                  :key="index"
                  :value="item"
                  >{{ item }}</a-radio-button
                >
              </a-radio-group>
            </a-form-item>
        </a-form>
      </a-tab-pane>
    </a-tabs>
  </a-modal>
</template>
<script lang="ts" setup>
import { ref, reactive, inject, onUpdated } from "vue";
import { setUserConfig } from "~/server/user/user_config/index";
import { getUserInfo } from "~/views/index";
import { VIEW_MODEL_CARD, VIEW_MODEL_TABLE } from "~/views";
import type { viewModelType } from "/views";

const activeKey = ref<string>("1");

const positionArr = ref<string[]>([
  "top",
  "bottom",
  "topLeft",
  "topRight",
  "bottomLeft",
  "bottomRight",
]);

const viewModelArr = ref<viewModelType[]>([
  VIEW_MODEL_TABLE,
  VIEW_MODEL_CARD,
]);

const formState = reactive<{
  user_config_menu_collapsed: boolean,
  user_config_footnote: boolean,
  user_config_watermark: boolean,
  user_config_notification_position: string,
  user_config_view_model: string,
}>({
  user_config_menu_collapsed: false,
  user_config_footnote: false,
  user_config_watermark: false,
  user_config_notification_position: "",
  user_config_view_model: "",
});

const userInfo: getUserInfo = inject("getUserInfo")!;

onUpdated(() => {
  [
    formState.user_config_footnote,
    formState.user_config_menu_collapsed,
    formState.user_config_watermark,
    formState.user_config_notification_position,
    formState.user_config_view_model,
  ] = [
    Boolean(userInfo().getUserConfig.user_config_footnote),
    Boolean(userInfo().getUserConfig.user_config_menu_collapsed),
    Boolean(userInfo().getUserConfig.user_config_watermark),
    userInfo().getUserConfig.user_config_notification_position,
    userInfo().getUserConfig.user_config_view_model,
  ];
});

const handleChange = (): void => {
  setUserConfig.fetch({
    user_config_footnote: Number(formState.user_config_footnote),
    user_config_menu_collapsed: Number(formState.user_config_menu_collapsed),
    user_config_watermark: Number(formState.user_config_watermark),
    user_config_notification_position: formState.user_config_notification_position,
    user_config_view_model: formState.user_config_view_model,
  });
};
</script>
<style scoped>
</style>