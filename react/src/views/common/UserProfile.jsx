import React from "react";
import { MdOutlineCancel } from "react-icons/md";
import { Button } from "../common/index";
import avatar from "../../assets/images/avatar.jpg";
import { MdAccountBox } from "react-icons/md";
import { FaUsers } from "react-icons/fa";
import { FaUsersCog } from "react-icons/fa";
import { NavLink } from "react-router-dom";
import { useUIContext } from "../../context/UIContext";
import { useAuthContext } from "../../context/AuthContext";
import { withTranslation } from "react-i18next";

import { useTranslation } from "react-i18next";

const UserProfile = ({ t, i18n }) => {
    // const currentLanguage = i18n.language || "en";
    const currentLanguage = i18n.language;

    const getClassNameBasedOnLanguage = () => {
        if (currentLanguage === "en") {
            return "nav-item absolute right-4 top-16 bg-white dark:bg-[#42464D] p-8 rounded-lg w-80";
        } else if (currentLanguage === "ar") {
            return "nav-item absolute left-4 top-16 bg-white dark:bg-[#42464D] p-8 rounded-lg w-80";
        } else {
            return "nav-item absolute right-4 top-16 bg-white dark:bg-[#42464D] p-8 rounded-lg w-80";
        }
    };
    const className = getClassNameBasedOnLanguage();

    const { currentColor } = useUIContext();
    const { signout, user } = useAuthContext();

    const userProfileData = [
        {
            icon: <MdAccountBox />,
            title: t("profile.settingsTitle"),
            desc: t("profile.settingsDesc"),
            iconColor: "#03C9D7",
            iconBg: "#E5FAFB",
        },
        // {
        //     icon: <FaUsers />,
        //     title: "Users",
        //     desc: "Connected Users",
        //     iconColor: "rgb(0, 194, 146)",
        //     iconBg: "rgb(235, 250, 242)",
        // },
    ];
    const handleSignOut = (e) => {
        e.preventDefault();

        try {
            signout();
        } catch (ex) {
            console.log(ex);
        }
    };

    return (
        <div className={className}>
            <div className="flex justify-between items-center">
                <p className="font-semibold text-lg dark:text-gray-200">
                    {t("profile.title")}
                </p>
                <Button
                    icon={<MdOutlineCancel />}
                    color="rgb(153, 171, 180)"
                    bgHoverColor="light-gray"
                    size="2xl"
                    borderRadius="50%"
                />
            </div>
            <div className="flex gap-5 items-center mt-6 border-color border-b-1 pb-6">
                <img
                    className="rounded-full h-24 w-24"
                    src={avatar}
                    alt="user-profile"
                />
                <div>
                    <p className="font-semibold text-xl dark:text-gray-200">
                        {" "}
                        {user.firstName + " " + user.lastName}{" "}
                    </p>
                    <p className="text-gray-500 text-sm dark:text-gray-400">
                        {" "}
                        {user.isOwner
                            ? t("profile.admin")
                            : t("profile.employee")}{" "}
                    </p>
                    <p className="text-gray-500 text-sm font-semibold dark:text-gray-400">
                        {" "}
                        {user.email}{" "}
                    </p>
                </div>
            </div>
            <div>
                {userProfileData.map((item, index) => (
                    <div
                        key={index}
                        className="flex gap-5 border-b-1 border-color p-4 hover:bg-light-gray cursor-pointer  dark:hover:bg-[#42464D]"
                    >
                        <button
                            type="button"
                            style={{
                                color: item.iconColor,
                                backgroundColor: item.iconBg,
                            }}
                            className=" text-xl rounded-lg p-3 hover:bg-light-gray"
                        >
                            {item.icon}
                        </button>

                        <div>
                            <p className="font-semibold dark:text-gray-200 ">
                                {item.title}
                            </p>
                            <p className="text-gray-500 text-sm dark:text-gray-400">
                                {" "}
                                {item.desc}{" "}
                            </p>
                        </div>
                    </div>
                ))}
            </div>
            <div className="mt-5">
                <NavLink to="/" onClick={handleSignOut}>
                    <Button
                        color="white"
                        bgColor={currentColor}
                        text={t("links.signout")}
                        borderRadius="10px"
                        width="full"
                    />
                </NavLink>
            </div>
        </div>
    );
};

export default withTranslation()(UserProfile);
