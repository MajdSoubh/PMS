import { apiEndpoints } from "../config";
import http from "./httpClient";

export function getPurchases() {
    return http.get(apiEndpoints.purchase);
}
export function deletePurchase(id) {
    return http.delete(`${apiEndpoints.purchase}/${id}`);
}
export const addPurchase = ({ id, ...data }) => {
    return http.post(apiEndpoints.purchase, data);
};
export const editPurchase = (id, data) => {
    return http.put(`${apiEndpoints.purchase}/${id}`, data);
};
