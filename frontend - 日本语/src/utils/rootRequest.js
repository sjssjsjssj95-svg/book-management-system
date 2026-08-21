import axios from "axios";
import { ElMessage } from 'element-plus'

const request = axios.create({
  baseURL: "http://127.0.0.1:8000/api",
  timeout: 10000
});


// ===================== 请求拦截器 =====================
request.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem("root_token");

    config.headers = config.headers || {};

    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }

    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);


// ===================== 响应拦截器 =====================
request.interceptors.response.use(
  (response) => {
    return response.data;
  },
  (error) => {
    console.log("error", error);

    if (error.response?.status === 401) {
      localStorage.removeItem("token");
      ElMessage.error('请先登录。')
      window.setTimeout(()=>{
        window.location.href = "/";
      },1000)
    }

    return Promise.reject(error);
  }
);

export default request;