import { apiEndpoints } from "../config";
import http from "./httpClient";

export const getSuppliers = (id) => {
    return http.get(apiEndpoints.supplier);
};

export const deleteSupplier = (id) => {
    return http.delete(apiEndpoints.supplier + "/" + id);
};
export const editSupplier = ({ id, ...data }) => {
    return http.put(apiEndpoints.supplier + "/" + id, data);
};
export const addSupplier = ({ id, ...data }) => {
    return http.post(apiEndpoints.supplier, data);
};
