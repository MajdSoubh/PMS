import { apiEndpoints } from "../config";
import http from "./httpClient";

export const getEmployees = () => {
    return http.get(apiEndpoints.employee);
};

export const getEmployee = (id) => {
    return http.get(`${apiEndpoints.employee}/${id}`);
};

export const deleteEmployee = (id) => {
    return http.delete(apiEndpoints.employee + "/" + id);
};
export const editEmployee = ({ id, ...data }) => {
    return http.put(apiEndpoints.employee + "/" + id, data);
};
export const addEmployee = ({ id, ...data }) => {
    return http.post(apiEndpoints.employee, data);
};

export const getRoles = () => {
    return http.get(apiEndpoints.role);
};
