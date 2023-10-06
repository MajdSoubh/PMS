import { createContext, useContext, useState } from "react";
import http from "../services/httpClient";
import { apiEndpoints } from "../config";

const AuthContext = createContext();

const useAuthContext = () => useContext(AuthContext);

const AuthProvider = ({ children }) => {
    const [user, setUser] = useState(() => {
        const storedUser = sessionStorage.getItem("user");
        if (storedUser) {
            return JSON.parse(storedUser);
        } else return {};
    });
    const [token, _setToken] = useState(sessionStorage.getItem("ACCESS_TOKEN"));
    const [permissions, setPermissions] = useState(() => {
        const storedPermissions = sessionStorage.getItem("permissions");
        if (storedPermissions) {
            return JSON.parse(storedPermissions || "[]");
        } else return {};
    });
    const setToken = (token) => {
        _setToken(token);
        if (token) sessionStorage.setItem("ACCESS_TOKEN", token);
        else sessionStorage.removeItem("ACCESS_TOKEN");
    };

    const login = async (data) => {
        try {
            const response = await http.post(apiEndpoints.login, data);
            const { data: resData } = response;
            sessionStorage.setItem("user", JSON.stringify(resData.data.user));
            sessionStorage.setItem(
                "permissions",
                JSON.stringify(resData.data.user.permissions || [])
            );
            setToken(resData.data.token);
            setUser(resData.data.user);
            setPermissions(resData.data.user.permissions);
            return response;
        } catch (ex) {
            const response = ex.response;
            if (response) {
                return response;
            }
            return { message: "Unexpected error" };
        }
    };
    const forgetPassword = async (data) => {
        try {
            const response = await http.post(apiEndpoints.forgetPassword, data);
            return response;
        } catch (ex) {
            const response = ex.response;
            if (response) {
                return response;
            }
            return { message: "Unexpected error" };
        }
    };
    const signup = async (data) => {
        try {
            const response = await http.post(apiEndpoints.signup, data);
            const { data: resData } = response;
            sessionStorage.setItem("user", JSON.stringify(resData.data.user));
            sessionStorage.setItem(
                "permissions",
                JSON.stringify(resData.data.user.permissions || [])
            );
            setToken(resData.data.token);
            setUser(resData.data.user);
            setPermissions(resData.data.user.permissions || []);
            return response;
        } catch (ex) {
            const response = ex.response;
            if (response) {
                return response;
            }
            return { message: "Unexpected error" };
        }
    };
    const signout = async () => {
        try {
            const response = await http.delete(apiEndpoints.signout);
            setToken(null);
            setPermissions(null);

            return response;
        } catch (ex) {
            const response = ex.response;
            if (response) {
                return response;
            }
            return { message: "Unexpected error" };
        }
    };
    const resetPassword = async (data) => {
        try {
            const response = await http.post(apiEndpoints.resetPassword, data);
            return response;
        } catch (ex) {
            const response = ex.response;
            if (response) {
                return response;
            }
            return { message: "Unexpected error" };
        }
    };
    return (
        <AuthContext.Provider
            value={{
                signup,
                signout,
                login,
                forgetPassword,
                resetPassword,
                user,
                token,
                permissions,
                setToken,
            }}
        >
            {children}
        </AuthContext.Provider>
    );
};

export default AuthProvider;
export { useAuthContext, AuthContext };
