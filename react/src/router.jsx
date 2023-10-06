import { createBrowserRouter, Navigate } from "react-router-dom";

/* Layouts  */
import GuestLayout from "./layouts/GuestLayout";
import DefaultLayout from "./layouts/DefaultLayout";

/* Views */
import Login from "./views/Login";
import Signup from "./views/Signup";
import NotFound from "./views/NotFound";
import ForgetPassword from "./views/ForgetPassword";
import ResetPassword from "./views/ResetPassword";
import Dashboard from "./views/Dashboard";

import ManageDrug from "./views/Drugs/ManageDrug";
import ManageStock from "./views/Drugs/ManageStock";
import InvoiceAdd from "./views/Invoices/AddInvoice";
import ManageInvoice from "./views/Invoices/ManageInvoice";

import ManageCustomer from "./views/Customers/ManageCustomer";
import AddPurchase from "./views/Purchases/AddPurchase";
import ManagePurchase from "./views/Purchases/ManagePurchase";

import ManageSupplier from "./views/Suppliers/ManageSupplier";

import ManageVoucher from "./views/Vouchers/ManageVoucher";
import ManageEmployee from "./views/Employees/ManageEmployee";
const router = createBrowserRouter([
    {
        path: "/",
        element: <DefaultLayout />,
        children: [
            { path: "/", element: <Dashboard /> },
            { path: "/invoice/add", element: <InvoiceAdd /> },
            { path: "/invoices", element: <ManageInvoice /> },

            { path: "/customers", element: <ManageCustomer /> },
            { path: "/drugs", element: <ManageDrug /> },
            { path: "/stocks", element: <ManageStock /> },

            { path: "/suppliers", element: <ManageSupplier /> },
            { path: "/purchase/add", element: <AddPurchase /> },
            { path: "/purchases", element: <ManagePurchase /> },

            { path: "/vouchers", element: <ManageVoucher /> },
            { path: "/employees", element: <ManageEmployee /> },
        ],
    },
    {
        path: "/",
        element: <GuestLayout />,
        children: [
            { path: "/login", element: <Login /> },
            { path: "/signup", element: <Signup /> },
            { path: "/forget-password", element: <ForgetPassword /> },
            { path: "/reset-password/:token", element: <ResetPassword /> },
        ],
    },

    { path: "*", element: <NotFound /> },
]);

export default router;
