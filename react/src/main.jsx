import React from "react";
import ReactDOM from "react-dom/client";
import { RouterProvider } from "react-router-dom";
import AuthProvider from "./context/AuthContext";
import { UIProvider } from "./context/UIContext";

import router from "./router";
import "./assets/styles/index.css";

/* Transaltion configuration */
import i18n from "./i18n";
document.body.dir = i18n.dir();

const root = document.getElementById("root");

ReactDOM.createRoot(root).render(
    <AuthProvider>
        <UIProvider>
            <RouterProvider router={router} />
        </UIProvider>
    </AuthProvider>
);
