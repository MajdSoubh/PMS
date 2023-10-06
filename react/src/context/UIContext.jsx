// import React, { createContext, useContext, useState } from "react";

// const StateContext = createContext();
// // InitialState
// const initialState = {
//   notification: false,
//   userProfile: false,
// };

// export const ContextProvider = ({ children }) => {
//   const [activeMenu, setActiveMenu] = useState(true);
//   const [isClicked, setIsClicked] = useState(initialState);
//   //At the start we don't know what the screen size is,so we set it to undefined
//   const [screenSize, setScreenSize] = useState(undefined);
//   const handleClick = (clicked) => {
//     //Only change the value that has been clicked and set it to true
//     setIsClicked({ ...initialState, [clicked]: true });
//   };

//   return (
//     //These values will be passed through all the components inside of our entire Application
//     <StateContext.Provider
//       value={{
//         activeMenu,
//         setActiveMenu,
//         isClicked,
//         setIsClicked,
//         handleClick,
//         screenSize,
//         setScreenSize,
//       }}
//     >
//       {/* What ever inside of that Context wrapper which are children will be rendered   */}
//       {children}
//     </StateContext.Provider>
//   );
// };

// export const useStateContext = () => useContext(StateContext);

import React, { createContext, useContext, useState, useEffect } from "react";

export const UIContext = createContext();

const initialState = {
    chat: false,
    userProfile: false,
    notification: false,
};

export const UIProvider = ({ children }) => {
    const [screenSize, setScreenSize] = useState(undefined);
    const [activeMenu, setActiveMenu] = useState(true);
    const [isClicked, setIsClicked] = useState(initialState);
    const [currentColor, setCurrentColor] = useState("#03C9D7");
    let [currentMode, setCurrentMode] = useState("Light");
    // Is the right side bar initially opened or closed
    const [themeSettings, setThemeSettings] = useState(false);

    const setMode = (e) => {
        const mode = e.target.value;
        setCurrentMode(mode);
        localStorage.setItem("themeMode", mode);
        setThemeSettings(false);
    };

    useEffect(() => {
        const savedMode = localStorage.getItem("themeMode");
        if (savedMode) {
            setCurrentMode(savedMode);
        }
    }, []);

    const setColor = (color) => {
        setCurrentColor(color);
        localStorage.setItem("colorMode", color);
        setThemeSettings(false);
    };

    useEffect(() => {
        const savedColor = localStorage.getItem("colorMode");
        if (savedColor) {
            setCurrentColor(savedColor);
        }
    }, []);

    const handleClick = (clicked) =>
        setIsClicked({ ...initialState, [clicked]: true });

    return (
        // eslint-disable-next-line react/jsx-no-constructed-context-values
        <UIContext.Provider
            value={{
                activeMenu,
                screenSize,
                setScreenSize,
                handleClick,
                isClicked,
                initialState,
                setIsClicked,
                setActiveMenu,
                currentColor,
                currentMode,
                setColor,
                setMode,
                themeSettings,
                setThemeSettings,
            }}
        >
            {children}
        </UIContext.Provider>
    );
};

export const useUIContext = () => useContext(UIContext);
