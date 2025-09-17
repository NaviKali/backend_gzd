<template>
    <img src="../assets//wallpaper/Messy-Room.jpg" alt="" class="wallpaper">
    <div class="MaxStyle LoginBlock">
        <a-card class="Shadow CardStyle" :title="subjectTitle + `后台登录`" :tab-list="tabList" :active-tab-key="key"
            @tabChange="key => onTabChange(key, 'key')">
            <template #customTab="item">
                <span v-if="item.key === tabList[0].key">
                    {{ item.tab }}
                    <LoginOutlined />
                </span>
                <span v-if="item.key == tabList[1].key">
                    {{ item.tab }}
                    <SelectOutlined />
                </span>
            </template>
            <div>
                <a-form v-if="key == tabList[0].key" :model="formState" name="basic" :label-col="{ span: 5 }"
                    :wrapper-col="{ span: 16 }" autocomplete="off" @finish="onFinish">
                    <a-form-item label="账号" name="account" :rules="[{ required: true, message: '请输入账号!' }]">
                        <a-input v-model:value="formState.account" placeholder="请输入账号" />
                    </a-form-item>

                    <a-form-item label="密码" name="password" :rules="[{ required: true, message: '请输入密码!' }]">
                        <a-input-password v-model:value="formState.password" placeholder="请输入密码" />
                    </a-form-item>

                    <a-form-item label="验证码" name="vcode" :rules="[{ required: true, message: '请输入验证码!' }]">
                        <a-input v-model:value="formState.vcode" placeholder="请输入验证码" />
                        <img class="Shadow" :src="codeUrl" @click="getVerCode" alt=""
                            style="width: 60%;margin-top: 20px;">
                    </a-form-item>

                    <a-form-item :wrapper-col="{ offset: 18, span: 16 }">
                        <a-button type="primary" html-type="submit">登录</a-button>
                    </a-form-item>
                </a-form>
            </div>
        </a-card>
        <div class="LoginBlockBlur"></div>
        <a-float-button @click="handleOpenDocument" />
        <a-modal v-model:open="DocumentStatus" width="1000px" title="文档手册" cancelText="取消" @ok="handleOk">
            <p>如果需要请注册用户账号</p>
        </a-modal>
    </div>

</template>
<script lang="ts" setup>
import { onMounted, ref, reactive, inject } from 'vue'
import type { changeIsLoginPageType, changeRouterType, subjectInfoType } from './index'
import { AccountLogin } from '~/server/login/index'
import { Login } from '~/ClassLibrary/Login'
import { isRequestSuccess } from '~/server/index'
import { CreateWebUrl } from '~/server'

interface FormState {
    account: string;
    password: string;
    vcode: string;
}

const newLogin = new Login()
const subjectTitle = ref<string>('');
const changeIsLoginPage: changeIsLoginPageType = inject("changeIsLoginPage")!;
const changeRouter: changeRouterType = inject("changeRouter")!;
const DocumentStatus = ref<boolean>(false);

const codeUrl = ref<string>('');

const tabList = ref<{
    key: string,
    tab: string
}[]>([
    {
        key: 'Login',
        tab: '登录',
    },
    {
        key: 'Register',
        tab: '注册',
    },
]);

const key = ref<string>(tabList.value[0].key);

const formState = reactive<FormState>({
    account: '',
    password: '',
    vcode: ''
});

onMounted(() => {
    init()
})

const init = (): void => {
    const subjectInfo: subjectInfoType = inject("subjectInfo")!

    subjectTitle.value = subjectInfo.name

    //*获取验证码
    getVerCode()
}

const onTabChange = (value: string, type: string): void => {
    if (type === 'key') key.value = value;
};

const onFinish = (values: any): void => {
    AccountLogin.fetch(values).then(async (res: AccountLogin.returnResponse): Promise<void> => {
        if (isRequestSuccess(res)) {
            newLogin.LoginSuccess(res.data)
            await changeIsLoginPage(false)
            await changeRouter("/home")
        }
    })
};

const handleOpenDocument = (): void => {
    DocumentStatus.value = true
}
const handleOk = (): void => {
    DocumentStatus.value = false
}

const getVerCode = (): void => {
    codeUrl.value = CreateWebUrl('Login.Login/getVerCode?id=' + new Date().getTime())
}

</script>
<style scoped>

.wallpaper {
    width: 100vw;
    height: 100vh;
    position: absolute;
    object-fit: cover;
}

.ant-card {
    transform: all 1s;
    position: relative;
    width: 25%;
    height: 99%;
    /* opacity: 0.8; */
    padding-top: 20px;
    background-color: unset;
    z-index: 10;
    margin-right: 10px;
    border: unset;
    border: 4px solid white;
}

.LoginBlockBlur {
    margin-right: 10px;
    width: 25%;
    height: 100%;
    position: absolute;
    backdrop-filter: blur(20px);
    z-index: 5;
    padding-top: 20px;
    background-color: unset;

}

.LoginBlock {
    display: flex;
    justify-content: right;
    align-content: center;
    align-items: center;
}

.CardStyle {
    display: flex;
    flex-direction: column;
    justify-content: top;
    align-items: center;
}
</style>