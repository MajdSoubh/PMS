import React, { useContext } from "react";
import { Link, NavLink, Navigate } from "react-router-dom";

// ReactIconsImport
import { SiShopware } from "react-icons/si";
import { FaSignOutAlt } from "react-icons/fa";
import { MdOutlineCancel } from "react-icons/md";
import { TooltipComponent } from "@syncfusion/ej2-react-popups";

import { links } from "../../utils/UIUtils";

import { useUIContext } from "../../context/UIContext";
import { useAuthContext } from "../../context/AuthContext";
import { can } from "../../utils/UIUtils";
import { withTranslation } from "react-i18next";
import { useTranslation } from "react-i18next";

const Sidebar = ({ t, i18n }) => {
    const currentLanguage = i18n.language;
    const getClassNameBasedOnLanguage = () => {
        if (currentLanguage === "en") {
            return "ml-3 h-screen md:overflow-hidden overflow-auto md:hover:overflow-auto pb-10";
        } else if (currentLanguage === "ar") {
            return "mr-3 h-screen md:overflow-hidden overflow-auto md:hover:overflow-auto pb-10";
        } else {
            return "ml-3 h-screen md:overflow-hidden overflow-auto md:hover:overflow-auto pb-10";
        }
    };
    const className = getClassNameBasedOnLanguage();
    const { activeMenu, setActiveMenu, screenSize, currentColor } =
        useUIContext();
    const { signout } = useAuthContext();
    const handleCloseSideBar = () => {
        if (activeMenu && screenSize <= 900) {
            setActiveMenu(false);
        }
    };
    const handleSignOut = (e) => {
        e.preventDefault();
        signout();
    };
    const { user, permissions } = useAuthContext();
    const activeLink =
        "flex items-center gap-5 px-4 pt-3 pb-2.5 rounded-lg text-white text-md m-2";
    const normalLink =
        "flex items-center gap-5 px-4 pt-3 pb-2.5 rounded-lg text-md text-gray-700 dark:text-gray-200 dark:hover:text-black hover:bg-light-gray m-2";
    return (
        <div className={className}>
            {activeMenu && (
                <>
                    <div className="flex justify-between items-center">
                        <Link
                            to="/"
                            onClick={handleCloseSideBar}
                            className="items-center gap-3 mx-2 mt-4 flex text-xl font-extrabold tracking-tight dark:text-white text-slate-900"
                        >
                            <SiShopware style={{ color: currentColor }} />{" "}
                            <span style={{ color: currentColor }}>PMS</span>
                        </Link>
                        <TooltipComponent
                            content="Menu"
                            position="BottomCenter"
                        >
                            <button
                                type="button"
                                style={{
                                    color: currentColor,
                                }}
                                // onClick={() => setActiveMenu(!activeMenu)}
                                onClick={() =>
                                    setActiveMenu(
                                        (prevActiveMenu) => !prevActiveMenu
                                    )
                                }
                                className="text-2xl rounded-full p-3 hover:bg-light-gray mt-4 md:mt-1.5 block lg:hidden"
                            >
                                <MdOutlineCancel />
                            </button>
                        </TooltipComponent>
                    </div>
                    <div className="mt-10">
                        {links.map((item) => {
                            if (
                                !item.permissions ||
                                can(user, item.permissions)
                            ) {
                                return (
                                    <div key={item.title}>
                                        <p className="text-gray-400 font-semibold m-3 mt-4 uppercase">
                                            {item.title}
                                        </p>
                                        {item.links.map((link) => {
                                            if (
                                                !link.permissions ||
                                                can(user, link.permissions)
                                            )
                                                return (
                                                    <NavLink
                                                        style={({
                                                            isActive,
                                                        }) => ({
                                                            backgroundColor:
                                                                isActive
                                                                    ? currentColor
                                                                    : "",
                                                        })}
                                                        to={`/${link.address}`}
                                                        key={link.name}
                                                        onClick={
                                                            handleCloseSideBar
                                                        }
                                                        className={({
                                                            isActive,
                                                        }) =>
                                                            isActive
                                                                ? activeLink
                                                                : normalLink
                                                        }
                                                    >
                                                        {link.icon}
                                                        <span className="capitalize">
                                                            {link.name}
                                                        </span>
                                                    </NavLink>
                                                );
                                        })}
                                    </div>
                                );
                            }
                        })}
                        <div>
                            <p className="text-gray-400 font-semibold m-3 mt-4 uppercase">
                                {t("links.signout")}
                            </p>
                            <NavLink
                                style={({ isActive }) => ({
                                    backgroundColor: isActive
                                        ? currentColor
                                        : "",
                                })}
                                to="/login"
                                onClick={handleSignOut}
                                className={({ isActive }) =>
                                    isActive ? activeLink : normalLink
                                }
                            >
                                <FaSignOutAlt className="text-xl" />
                                <span className="capitalize">
                                    {" "}
                                    {t("links.signout")}
                                </span>
                            </NavLink>
                        </div>
                    </div>
                </>
            )}
        </div>
    );
};

export default withTranslation()(Sidebar);
