import React from "react";

import { useUIContext } from "../../context/UIContext";

const Button = ({
    icon,
    color,
    bgColor,
    text,
    borderRadius,
    size,
    styles,
    onClick,
}) => {
    const { setIsClicked, initialState } = useUIContext();
    return (
        <button
            type="button"
            style={{ color, backgroundColor: bgColor, borderRadius }}
            className={`text-${size} p-3 hover:drop-shadow-xl font-semibold ${styles}`}
            onClick={() => setIsClicked(initialState)}
        >
            {icon} {text}
        </button>
    );
};

export default Button;
