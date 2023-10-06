import { apiEndpoints } from "../config";
import http from "./httpClient";

export const getStocks = (id) => {
    return http.get(apiEndpoints.stock);
};
export const getStock = (data) => {
    return http.put(apiEndpoints.stock, data);
};

export const deleteStock = (id) => {
    return http.delete(apiEndpoints.stock + "/" + id);
};
export const editStock = ({ id, ...data }) => {
    return http.put(apiEndpoints.stock + "/" + id, data);
};
export const addStock = ({ id, ...data }) => {
    return http.post(apiEndpoints.stock, data);
};
