import axios from "axios";
import { apiEndpoints } from "../config";
import i18n from "../i18n";

const http = axios.create({
    baseURL: apiEndpoints.api,
});
http.interceptors.request.use((config) => {
    const token = sessionStorage.getItem("ACCESS_TOKEN");
    config.headers.Authorization = `Bearer ${token}`;
    config.headers["Accept-Language"] = i18n.language;
    return config;
});

http.interceptors.response.use(null, (err) => {
    const expectedError =
        err.response &&
        err.response.status >= 400 &&
        err.response.status <= 500;
    const unauthorized = err.response && err.response.status === 401;
    if (unauthorized) {
        sessionStorage.removeItem("ACCESS_TOKEN");
        window.location.reload();
    }
    if (!expectedError) {
        console.log("An unexpected error occurred");
    }

    return Promise.reject(err);
});
export default http;
