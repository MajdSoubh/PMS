import React from "react";

const TextArea = ({ label, error, hint, name, className, ...rest }) => {
    const styles = {
        inputValidation: {
            borderColor: "#dc3545",
            paddingRight: "calc(1.5em + 0.75rem)",
            backgroundImage: `url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e")`,
            backgroundRepeat: "no-repeat",
            backgroundPosition: "right calc(0.375em + 0.1875rem) center",
            backgroundSize: "calc(0.75em + 0.375rem) calc(0.75em + 0.375rem)",
        },
    };
    return (
        <div className="mb-3">
            <div>
                {label && (
                    <label
                        htmlFor={name}
                        className={"block mb-2 text-gray-700 dark:text-white"}
                    >
                        {label}
                    </label>
                )}
                <textarea
                    name={name}
                    id={name}
                    {...rest}
                    style={error && styles.inputValidation}
                    className={
                        className ||
                        "block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500  dark:text-gray-900 sm:text-lg"
                    }
                />
            </div>
            {hint && (
                <span
                    style={{ fontSize: ".89rem" }}
                    className="block text-gray-500 mt-2"
                >
                    {hint}
                </span>
            )}
            {error && (
                <div
                    style={{
                        width: "100%",
                        marginTop: "0.25rem",
                        fontSize: ".875em",
                        color: "#dc3545",
                    }}
                    className="block text-sm text-red-600"
                >
                    {error}
                </div>
            )}
        </div>
    );
};

export default TextArea;
