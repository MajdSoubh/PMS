import React from "react";
import "../../assets/styles/entry.css";
import { withTranslation } from "react-i18next";
import { MdOutlineCancel } from "react-icons/md";
import { Button } from "../common/index";

const Notification = ({ t, i18n }) => {
    const currentLanguage = i18n.language;
    const getClassNameBasedOnLanguage = () => {
        if (currentLanguage === "en") {
            return "nav-item absolute right-8 top-16 bg-white dark:bg-[#42464D] p-8 rounded-lg w-56 drop-shadow-2xl";
        } else if (currentLanguage === "ar") {
            return "nav-item absolute left-8 top-16 bg-white dark:bg-[#42464D] p-8 rounded-lg w-56 drop-shadow-2xl";
        } else {
            return "nav-item absolute right-8 top-16 bg-white dark:bg-[#42464D] p-8 rounded-lg w-56 drop-shadow-2xl";
        }
    };
    const className = getClassNameBasedOnLanguage();
    return (
        <div>
            <div className={className}>
                <div className="flex justify-between items-center">
                    <p className="font-extrabold text-lg dark:text-gray-200">
                        {t("additional.changeLang")}
                    </p>
                    <Button
                        icon={<MdOutlineCancel />}
                        color="rgb(153, 171, 180)"
                        bgHoverColor="light-gray"
                        size="2xl"
                        borderRadius="50%"
                    />
                </div>
                <div
                    className="font-extrabold text-lg dark:text-gray-200 flex items-center justify-center gap-2 mx-auto mt-6"
                    style={{ maxWidth: "max-content" }}
                >
                    <select
                        className="font-extrabold text-lg dark:text-gray-700 form-select block w-24 py-2 px-3 rounded-md shadow-sm bg-white text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                        onChange={({ target }) => {
                            i18n.changeLanguage(target.value);
                            document.body.dir = i18n.dir();
                            window.location.reload();
                        }}
                        value={i18n.language}
                    >
                        <option value="en">EN</option>
                        <option value="ar">AR</option>
                    </select>
                </div>
            </div>
        </div>
    );
};

export default withTranslation()(Notification);
