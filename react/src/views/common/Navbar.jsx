import { AiOutlineAccountBook, AiOutlineMenu } from "react-icons/ai";
import { RiNotification3Line } from "react-icons/ri";
import { MdKeyboardArrowDown } from "react-icons/md";

import { GrLanguage } from "react-icons/gr";
import { MdLanguage } from "react-icons/md";
import { SiGoogletranslate } from "react-icons/si";



import { TooltipComponent } from "@syncfusion/ej2-react-popups";

import avatar from "../../assets/images/avatar.jpg";

import { Notification, UserProfile } from "./";
import React, { useEffect } from "react";

import { useUIContext } from "../../context/UIContext";

const NavButton = ({ title, customFunc, icon, color, dotColor }) => (
    <TooltipComponent content={title} position="BottomCenter">
        <button
            type="button"
            onClick={customFunc}
            style={{ color }}
            className="relative text-xl rounded-full p-3 hover:bg-light-gray"
        >
            <span
                style={{ background: dotColor }}
                className="absolute inline-flex rounded-full h-2 w-2 righ-2 top-2"
            />
            {icon}
        </button>
    </TooltipComponent>
);

const Navbar = () => {
    const {
        activeMenu,
        setActiveMenu,
        isClicked,
        setIsClicked,
        handleClick,
        screenSize,
        setScreenSize,
        currentColor,
    } = useUIContext();

    useEffect(() => {
        const handleResize = () => setScreenSize(window.innerWidth);
        window.addEventListener("resize", handleResize);
        handleResize();
        return () => window.removeEventListener("resize", handleResize);
    }, []);

    useEffect(() => {
        if (screenSize <= 900) {
            setActiveMenu(false);
        } else {
            setActiveMenu(true);
        }
    }, [screenSize]);

    return (
        <div className="flex  justify-between p-2 md:mx-4 relative">
            {/* Toggling The Side Bar */}
            <NavButton
                title="Menu"
                customFunc={() =>
                    setActiveMenu((prevActiveMenu) => !prevActiveMenu)
                }
                color={currentColor}
                icon={<AiOutlineMenu />}
            />
            {/* Notifications Button */}
            <div className="flex">
                <NavButton
                    title="Language"
                    // dotColor="#03C9D7"
                    customFunc={() => handleClick("notification")}
                    color={currentColor}
                    icon={<SiGoogletranslate />}
                    className="ml-8"
                />
                {/* UserProfile */}
                <TooltipComponent content="Profile" position="BottomCenter">
                    <div
                        className="flex items-center gap-2 cursor-pointer p-1 hover:bg-light-gray rounded-lg"
                        onClick={() => handleClick("userProfile")}
                    >
                        <img
                            src={avatar}
                            alt="Profile-Image"
                            className="rounded-full w-9 h-9"
                        />
                        <p>
                            <span className="text-gray-400 text-14">Hi, </span>{" "}
                            <span className="text-gray-400  font-bold ml-1 text-14">
                                Majd
                            </span>
                        </p>
                        <MdKeyboardArrowDown className="text-gray-400 text-14" />
                    </div>
                </TooltipComponent>
                {isClicked.notification && <Notification />}
                {isClicked.userProfile && <UserProfile />}
            </div>
        </div>
    );
};

export default Navbar;
