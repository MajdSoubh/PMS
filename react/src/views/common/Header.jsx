import React from "react";

const Header = ({ category, title }) => {
    return (
        <div className="mb-10">
            {category && (
                <p className="text-lg font-bold text-gray-400">{category}</p>
            )}
            <p className="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-gray-200">
                {title}
            </p>
        </div>
    );
};

export default Header;
