/// <reference types="vite/client" />
// 声明环境变量结构
interface ImportMetaEnv {
    readonly VITE_API_BASE_URL: string;
    // 添加其他自定义环境变量...
  }
  
  // 扩展 ImportMeta 接口
  interface ImportMeta {
    readonly env: ImportMetaEnv;
  }
  