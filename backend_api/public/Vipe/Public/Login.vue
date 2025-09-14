<template>
        <div class="Container" :class="{ Show: showIndex }">
            
                <div class="Title"><b>
                                <p id="Title">{{ Title }}</p>
                        </b></div>
                <div class="User_input">
                        <div class="User_input_item">
                                <p>账号</p>
                                <input type="text" placeholder="请输入账号" v-model="user">
                        </div>
                </div>
                <div class="User_input">
                        <div class="User_input_item">
                                <p>密码</p>
                                <input type="text" placeholder="请输入密码" v-model="password">
                        </div>
                </div>
                <button class="Login" @click="Login()">登录</button>
        </div>
</template>
<script setup lang="ts">
import { ref, reactive, onMounted } from 'vue'
const user = ref("")
const password = ref("")
const showIndex = ref(true)
const messageIndex =ref(false)
const Title = ref("LOGIN_TITLE")
onMounted(() => {
        setTimeout(() => {
                showIndex.value = false
        }, 500)
})

// 登录
function Login()
{
}
</script>
<style>
* {
        margin: 0;
        padding: 0;
}
.Show {
        transition: all 1s;
        opacity: 0;
}
.ShowMessage{
        transition: all 1.5s;
        top: -500px;
}
body {
        text-decoration: none;
        list-style: none;
        overflow: hidden;
        width: 100vw;
        height: 100vh;
}

:root {
        --all-color: skyblue;
        --font-family: "楷体";
        --login-background-first: white;
        --login-background-second: skyblue;
}

input {
        outline: none;
}

.Container {
        border: 0px solid blue;
        width: 100vw;
        transition: all 1.5s;
        height: 100vh;
        background: linear-gradient(to bottom, var(--login-background-first), var(--login-background-second));
        display: flex;
        justify-content: center;
        justify-items: center;
        align-items: center;
        align-content: center;
        flex-direction: column;
}

#Title {
        text-align: center;
        font-size: 35px;
        color: skyblue;
}

.User_input {
        display: flex;
        justify-content: center;
        justify-items: center;
        align-items: center;
        align-content: center;
        margin-top: 30px;
}

.User_input_item {
        width: 300px;
        display: flex;
        justify-content: center;
        justify-items: center;
        align-items: center;
        align-content: center;
        flex-direction: column;

}

.User_input_item p {
        font-size: 18px;
        color: rgb(87, 205, 252);
}

.User_input_item input {
        margin-top: 15px;
        width: 90%;
        height: 30px;
        border-radius: 5px;
        border: 1px solid var(--all-color);
        padding-left: 10px;
}

.User_input_item input:focus {
        transition: all 0.8s;
        background-color: var(--all-color);
        color: white;
        box-shadow: 0px 0px 10px 0px var(--all-color);
}

.Login {
        border: none;
        outline: none;
        width: 65px;
        height: 40px;
        margin-top: 50px;
        border-radius: 10px;
        color: white;
        background-color: var(--all-color);
}

.Login:hover {
        transition: all 0.6s;
        background-color: #86f1ff;

}

.Message {
        position: absolute;
        transition: all 1.5s;
        z-index: 100000;
        right: 0;
        border-radius: 5px;
        left: 10px;
        margin: auto;
        width: 230px;
        height: 60px;
        display: flex;
        justify-content: center;
        justify-items: center;
        align-items: center;
        align-content: center;
        color: white;
        font-family: var(--font-family);
        font-size: 18px;
}
</style>