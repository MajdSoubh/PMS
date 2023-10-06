import { Outlet } from "react-router-dom";
import { useAuthContext } from "../context/AuthContext";
import { Navigate } from "react-router-dom";
import { FiSettings } from "react-icons/fi";
import { TooltipComponent } from "@syncfusion/ej2-react-popups";
import { Navbar, Sidebar, ThemeSettings } from "../views/common";
import i18n from "../i18n";
import { useUIContext } from "../context/UIContext";

const DefaultLayout = () => {
    const { user, token } = useAuthContext();

    if (!token) {
        return <Navigate to="/login" />;
    }

    const {
        activeMenu,
        themeSettings,
        setThemeSettings,
        currentColor,
        currentMode,
    } = useUIContext();
    return (
        <div className={currentMode === "Dark" ? "dark" : ""}>
            <div className="flex relative dark:bg-main-dark-bg">
                {/* <div className="flex relative dark:bg-main-dark-bg"> */}
                {/* Rendering React Setting Icon */}
                <div
                    className="fixed right-4 bottom-4"
                    style={{ zIndex: "1000" }}
                >
                    <TooltipComponent content="Settings" position="Top">
                        <button
                            type="button"
                            className="text-3xl text-white p-3 hover:drop-shadow-xl hover:bg-light-gray"
                            style={{
                                background: currentColor,
                                borderRadius: "50%",
                            }}
                            onClick={() => setThemeSettings(true)}
                        >
                            <FiSettings />
                        </button>
                    </TooltipComponent>
                </div>

                {/* Sidebar */}
                {activeMenu ? (
                    <div className="z-10 w-72 fixed sidebar dark:bg-secondary-dark-bg bg-white">
                        <Sidebar />
                    </div>
                ) : (
                    <div className="w-0 dark:bg-secondary-dark-bg">
                        <Sidebar />
                    </div>
                )}

                {/* Navbar */}
                <div
                    className={`dark:bg-main-dark-bg  bg-slate-50 min-h-screen w-full ${
                        activeMenu
                            ? `${i18n.dir() == "ltr" ? "md:ml-72" : "md:mr-72"}`
                            : "flex-2"
                    }`}
                >
                    <div className="fixed md:static bg-white dark:bg-main-dark-bg navbar w-full">
                        {/* focus */}
                        <Navbar />
                        {/* focus */}
                    </div>

                    {/* Routes */}
                    <div className="">
                        {themeSettings && <ThemeSettings />}

                        <Outlet />
                    </div>
                </div>
            </div>
        </div>
    );
};

export default DefaultLayout;
