import { defineConfig, loadEnv } from 'vite'
import vue from '@vitejs/plugin-vue'
import { resolve } from 'path'
import VueRouter from 'unplugin-vue-router/vite'

// https://vite.dev/config/
export default defineConfig(({ mode }) => {
  const env = loadEnv(mode, process.cwd())

  
  return {
    envPrefix: ['VITE_', 'WEB_'],
    plugins: [
      VueRouter({
        routesFolder: 'src/views',
        exclude: ['**/components/*.vue'],
        extensions: ['.vue'],
      }),
      vue()],
    resolve: {
      alias: [
        {
          find: '~',
          replacement: resolve(__dirname, 'src')
        },
        {
          find: '@',
          replacement: resolve(__dirname, 'src')
        },
      ],
    },
    // ServerConfig
    server: {
      host: "0.0.0.0",
      port: 5173,
      cors: true,
      open: true,

      proxy: {
        "/api": {
          target: env.VITE_WEB_URL,
          changeOrigin: true,
          rewrite: (path) => path.replace(/^\/api/, '')
        }

      }
    }
  }
})
