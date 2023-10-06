import { apiEndpoints } from "../config";
import http from "./httpClient";

export const getCustomers = (id) => {
    return http.get(apiEndpoints.customer);
};

export const deleteCustomer = (id) => {
    return http.delete(apiEndpoints.customer + "/" + id);
};
export const editCustomer = ({ id, ...data }) => {
    return http.put(apiEndpoints.customer + "/" + id, data);
};
export const addCustomer = ({ id, ...data }) => {
    return http.post(apiEndpoints.customer, data);
};
