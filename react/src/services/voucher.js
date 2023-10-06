import { apiEndpoints } from "../config";
import http from "./httpClient";

export const getVouchers = (id) => {
    return http.get(apiEndpoints.voucher);
};

export const deleteVoucher = (id) => {
    return http.delete(apiEndpoints.voucher + "/" + id);
};
export const editVoucher = ({ id, ...data }) => {
    return http.put(apiEndpoints.voucher + "/" + id, data);
};
export const addVoucher = ({ id, ...data }) => {
    return http.post(apiEndpoints.voucher, data);
};
