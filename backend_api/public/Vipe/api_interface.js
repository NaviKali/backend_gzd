/**
 * 生成代码-L
 */
import axios from "axios";
import { ElMessage } from 'element-plus'
import { useRouter } from "vue-router";
import router from '../router';
import { Load } from "../style";


/**
 * 获取title_headers列表
 */
export async function getMenuList(data) {
     return await axios.get('/api/Menu.Menu/getMenuList', data).then((res) => {
          return res.data
     })
}
/**
 * 搜索title_headers
 */
export async function SearchMenu(data) {
     return await axios.post('/api/Menu.Menu/SearchMenu', data).then((res) => {
          return res.data
     })
}
/**
 * 批量删除title_headers
 * @author L
 */
export async function BatchDeleteMenu(data) {
     return await axios.post("/api/Menu.Menu/BatchDeleteMenu", data).then((res) => {
          if (res.data.code == 200) {
               ElMessage({
                    message: res.data.msg,
                    type: "success",
               })
               setTimeout(() => {
                    Load()
               }, 1000);

          } else {
               ElMessage({
                    message: res.data.msg,
                    type: "error",
               })
          }
          return res.data
     })
}
/**
 * 获取title_headers历史记录
 * @author L
 */
export async function getTrasheMenu(data) {
     return await axios.get('/api/Menu.Menu/getTrasheMenu', data).then((res) => {
          return res.data
     })
}


/**
 * 恢复title_headers历史记录
 * @author L
 */
export async function RestoreTrasheMenu(data) {
     return await axios.post("/api/Menu.Menu/RestoreTrasheMenu", data).then((res) => {
          if (res.data.code == 200) {
               ElMessage({
                    message: res.data.msg,
                    type: "success",
               })
               setTimeout(() => {
                    Load()
               }, 1000);

          } else {
               ElMessage({
                    message: res.data.msg,
                    type: "error",
               })
          }
          return res.data
     })
}
/**
 * 彻底删除title_headers历史记录
 * @author L
 */
export async function ForceTrasheMenu(data) {
     return await axios.post("/api/Menu.Menu/ForceTrasheMenu", data).then((res) => {
          if (res.data.code == 200) {
               ElMessage({
                    message: res.data.msg,
                    type: "success",
               })
               setTimeout(() => {
                    Load()
               }, 1000);

          } else {
               ElMessage({
                    message: res.data.msg,
                    type: "error",
               })
          }
          return res.data
     })
}


test