import { apiEndpoints } from "../config";
import http from "./httpClient";

export function getInvoices() {
    return http.get(apiEndpoints.invoice);
}
export function deleteInvoice(id) {
    return http.delete(`${apiEndpoints.invoice}/${id}`);
}
export const addInvoice = async (data) => {
    try {
        const response = await http.post(apiEndpoints.invoice, data);
        return response;
    } catch (ex) {
        const response = ex.response;
        if (response) {
            return response;
        }
        return { data: { message: "Unexpected error", status: 0 } };
    }
};
export const editInvoice = (id, data) => {
    return http.put(`${apiEndpoints.invoice}/${id}`, data);
};
