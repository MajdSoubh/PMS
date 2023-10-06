import { Outlet } from "react-router-dom";
import { useAuthContext } from "../context/AuthContext";
import { Navigate } from "react-router-dom";

const GuestLayout = () => {
    const { user, token } = useAuthContext();
    if (token) {
        return <Navigate to="/" />;
    }

    return <Outlet />;
};

export default GuestLayout;
