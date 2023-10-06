import { apiEndpoints } from "../config";
import http from "./httpClient";

export const getDrugs = () => {
    return http.get(apiEndpoints.drug);
};
export const getDrug = (id) => {
    return http.get(`${apiEndpoints.drug}/${id}`);
};

export const deleteDrug = (id) => {
    return http.delete(apiEndpoints.drug + "/" + id);
};
export const editDrug = ({ id, ...data }) => {
    return http.put(apiEndpoints.drug + "/" + id, data);
};
export const addDrug = ({ id, ...data }) => {
    return http.post(apiEndpoints.drug, data);
};
