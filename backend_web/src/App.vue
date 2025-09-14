<template>
  <!-- LoginPage -->
  <router-view v-if="isLoginPage"></router-view>
  <!-- OtherPage  -->
  <div v-else>
    <a-layout style="min-height: 100vh">
      <a-layout-sider v-model:collapsed="menuCollapsed" collapsible>
        <a-spin v-if="menuList.length == 0" tip="菜单加载中" style="margin: auto;width: 100%;margin-top: 50px;" />
        <a-menu v-else v-model:selectedKeys="selectedKeys" theme="dark" mode="inline">
          <MenuComponent :data="menuList" @HandleMenuRouterTo="HandleMenuRouterTo" />
        </a-menu>
      </a-layout-sider>
      <a-layout>
        <a-layout-header style="background: #fff; padding: 0">
          <div class="Header" style="float: right; margin-right: 40px">
            <div class="Header" style="margin-right: 20px; width: 110px">
              <ExpandOutlined @click="handleFullScreen" style="font-size: 20px" />
              <!-- Ant-Design Icon图表 -->
              <div class="AntDesign-icons">
                <ItalicOutlined @click="showAntDesignIconModel" style="font-size: 20px" />
              </div>
              <!-- QR Code -->
              <a-popover placement="bottom">
                <template #content>
                  <h3>当前页面QR码</h3>
                  <a-qrcode :value="currentPageQRValue" />
                  <h3>首页QR码</h3>
                  <a-qrcode :value="homePageQRValue" />
                </template>
                <QrcodeOutlined style="font-size: 20px" />
              </a-popover>
            </div>
            <!-- 头像 -->
            <a-dropdown placement="bottomRight">
              <a-avatar v-if="userInfo.user_image != undefined" :src="getUploadsUrl(userInfo.user_image)" shape="square"
                :size="48">
                <template #icon>
                  <UserOutlined />
                </template>
              </a-avatar>
              <template #overlay>
                <a-menu>
                  <a-menu-item @click="routerName = '/userInfo'">
                    <span>个人信息</span>
                  </a-menu-item>
                  <a-menu-item @click="handleExit">
                    <span>退出</span>
                  </a-menu-item>
                </a-menu>
              </template>
            </a-dropdown>
          </div>
        </a-layout-header>
        <a-layout-content style="margin: 0 16px">
          <!-- 这里对接产品水印 -->
          <a-watermark :content="openWaterMark ? 'asd' : null">
            <div :style="{
              padding: '10px',
              background: '#fff',
              minHeight: '200px',
            }" class="Shadow">
              <router-view></router-view>
            </div>
          </a-watermark>
        </a-layout-content>
        <a-layout-footer style="text-align: center" v-if="openFooter">
          Ant Design ©2018 Created by Ant UED
        </a-layout-footer>
      </a-layout>
    </a-layout>
    <a-float-button-group shape="circle" :style="{ right: '24px' }">
      <a-float-button @click="userConfigComponentModel = true" shape="square">
        <template #icon>
          <SettingOutlined />
        </template>
      </a-float-button>
      <a-float-button @click="handleReload">
        <template #icon>
          <SyncOutlined />
        </template>
      </a-float-button>
      <a-back-top :visibility-height="0" />
    </a-float-button-group>
  </div>
  <userConfigComponent v-model:open="userConfigComponentModel" />
  <antDesignIcon v-model:open="antDesignIconModel" />
</template>
<script setup lang="ts">
import { onMounted, ref, watch } from "vue";
import { Login } from "@/ClassLibrary/Login";
import router from "@/router";
import { useRouter, type Router } from "vue-router";
import { NOT_PAGE_URL } from "./views/error/index";
import { defineProvideArr } from "./index";
import { getMenuTree } from "~/server/menu/index";
import { isRequestSuccess } from "./server";
import { message } from "ant-design-vue";
import MenuComponent from "~/components/menuTree.vue";
import { notification } from "ant-design-vue";
import { getCurrentUserInformation } from "~/server/user/index";
import userConfigComponent from "~/components/userConfig.vue";
import { getUploadsUrl } from "~/server/index";
import { VIEW_MODEL_CARD, VIEW_MODEL_TABLE, type viewModelType } from "./views";
import antDesignIcon from "./components/antDesignIcon.vue";



const userConfigComponentModel = ref<boolean>(false);

const login = new Login();
const userouter: Router = useRouter();
const routerName = ref<string>(location.pathname);

const menuCollapsed = ref<boolean>(false);
const openFooter = ref<boolean>(false);
const openWaterMark = ref<boolean>(false);
const viewModel = ref<viewModelType>(VIEW_MODEL_TABLE);
const notificationPosition = ref<any>();

const selectedKeys = ref<string[]>(["/home"]);

const isLoginPage = ref<boolean>();

const pathArr = ref<string[]>([]);

const Domain = ref<string>("http://127.0.0.1:886");
const currentPageQRValue = ref<string>("");
const homePageQRValue = ref<string>("");

const userInfo = ref<getCurrentUserInformation.Data>({});

const menuList = ref<getMenuTree.Data[]>([]);
const menuToAllList = ref<{ menu_to: string; menu_name: string }[]>([]);

const antDesignIconModel = ref<boolean | Boolean>(false);

onMounted(() => {
  init();
  console.log(navigator.onLine);
  
});

const init = async (): Promise<void> => {
  //?验证当前是否为登录页
  isLoginPage.value = login.isCurrentLoginPage();

  await router.getRoutes().forEach((item: { path: string }) => {
    pathArr.value.push(item.path);
  });
  //没有找到对应页面，进入"没有页面"
  if (!pathArr.value.includes(routerName.value)) {
    await userouter.push(NOT_PAGE_URL);
  }
  selectedKeys.value = [routerName.value];

  await handleConfig();
};

const HandleMenuRouterTo = (to: string): void => {
  routerName.value = to;
};

const handleConfig = (): void => {
  homePageQRValue.value = Domain.value + "/home";
  changeCurrentPageQR();
};



/**
 * 显示AntDesignIcon弹窗
 * 
 * @returns void
 */
const showAntDesignIconModel = (): void => {
  if (navigator.onLine)
    antDesignIconModel.value = true
  else {
    notification["error"]({
      message: "网络状态",
      description: '网络处于断开状态',
      placement: notificationPosition.value,
      duration: 0,
    });
  }
}

/**
 * 修改当前页面QR码
 *
 * @returns void
 */
const changeCurrentPageQR = (): void => {
  currentPageQRValue.value = Domain.value + routerName.value;
};

/**
 * 全屏模式
 *
 * @returns void
 */
const handleFullScreen = (): void => {
  document.documentElement.requestFullscreen();
};
/**
 * 刷新页面
 *
 * @return void
 */
const handleReload = (): void => {
  window.location.reload();
};

/**
 *退出登录
 *
 * @returns void
 */
const handleExit = (): void => {
  isLoginPage.value = true;
  routerName.value = "/login";
  sessionStorage.clear();
  message.success("退出成功!");
};

/**
 * 验证无用功的菜单跳转
 *
 * @author liulei
 * @returns void
 */
const UselessMenu = (): void => {
  const ChangeMenuToList = (data: getMenuTree.Data[]): void => {
    data.forEach((item: getMenuTree.Data) => {
      if (item.children.length != 0) {
        ChangeMenuToList(item.children);
      } else {
        menuToAllList.value.push({
          menu_to: item.menu_to,
          menu_name: item.menu_name,
        });
      }
    });
  };
  ChangeMenuToList(menuList.value);
  let difArr: string[] = menuToAllList.value
    .filter((v) => pathArr.value.indexOf(v.menu_to) == -1)
    .map((v) => v.menu_name);

  if (difArr.length != 0)
    notification["error"]({
      message: "无作用菜单",
      description: `[\t${difArr}\t]\n 没有对应模块`,
      placement: notificationPosition.value,
      duration: 0,
    });
};

//*监听路由名称
watch(routerName, (n: string): void => {
  //没有找到对应页面，进入"没有页面"
  if (!pathArr.value.includes(n)) {
    userouter.push(NOT_PAGE_URL);
  } else {
    userouter.push(n);
    selectedKeys.value = [routerName.value];
  }
  changeCurrentPageQR();
});

//*监听是否是登录页
watch(isLoginPage, async (n: any): Promise<void> => {
  if (!n) {
    //进入登录页
    //*获取用户信息
    await getCurrentUserInformation
      .fetch()
      .then((res: getCurrentUserInformation.returnResponse): void => {
        if (isRequestSuccess(res)) {
          menuCollapsed.value = Boolean(
            res.data.getUserConfig.user_config_menu_collapsed
          );
          openFooter.value = res.data.getUserConfig.user_config_footnote;
          openWaterMark.value = res.data.getUserConfig.user_config_watermark;
          notificationPosition.value = res.data.getUserConfig.user_config_notification_position;
          viewModel.value = res.data.getUserConfig.user_config_view_model;
          userInfo.value = res.data;
        }
      });
    //*获取菜单
    getMenuTree.fetch({}).then((res: getMenuTree.returnResponse): void => {
      if (isRequestSuccess(res)) {
        menuList.value = res.data;
        UselessMenu();
      }
    });
  } else {
    //返回登录页面
  }
});

/**
 * 依赖注入
 */
defineProvideArr([
  {
    name: "changeIsLoginPage",
    define: (is: boolean): void => {
      isLoginPage.value = Boolean(is);
    },
  },
  {
    name: "subjectInfo",
    define: {
      name: "Victus",
    },
  },
  {
    name: "userConfigInfo",
    define: {
      menuCollapsed: true,
    },
  },
  {
    name: "changeRouter",
    define: (name: string): void => {
      routerName.value = name.toString().trim();
    },
  },
  {
    name: "getUserInfo",
    define: (): getCurrentUserInformation.Data => {
      return userInfo.value;
    },
  },
  {
    name: "gobaNotificationPositionl",
    define: notificationPosition.value,
  },
  {
    name: "changeViewModel",
    define: (): void => {
      viewModel.value = viewModel.value == VIEW_MODEL_TABLE ? VIEW_MODEL_CARD : VIEW_MODEL_TABLE;
      userInfo.value.getUserConfig.user_config_view_model = viewModel.value;
    },
  },
]);
</script>
<style scoped>
.MenuLoading {
  padding: 30px 0px 30px 0px;
}

.Header {
  display: flex;
  justify-content: space-around;
  align-content: center;
  justify-items: center;
  height: 100%;
  align-items: center;
}

#components-layout-demo-side .logo {
  height: 32px;
  margin: 16px;
  background: rgba(255, 255, 255, 0.3);
}

.site-layout .site-layout-background {
  background: #fff;
}

[data-theme="dark"] .site-layout .site-layout-background {
  background: #141414;
}
</style>
