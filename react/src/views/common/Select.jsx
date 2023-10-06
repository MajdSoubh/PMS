import React from "react";
import SelectI from "react-select";

const Select = ({ name, label, style, onChange, error, ...rest }) => {
    const customStyles = {
        control: (state) =>
            ` bg-white dark:bg-white border-2 border-neutral-300 dark:border-neutral-700 hover:border-neutral-400 dark:hover:border-neutral-500 ${style} ${
                state.isFocused
                    ? "border-neutral-500 hover:border-neutral-500 dark:border-neutral-400 dark:hover:border-neutral-400 shadow-none"
                    : ""
            }`,

        option: (state) =>
            `text-neutral-600 dark:text-neutral-700 bg-neutral-100 hover:bg-neutral-200 dark:bg-neutral-700 dark:hover:bg-neutral-800 ${style} ${
                state.isFocused ? "bg-neutral-300 dark:bg-neutral-800" : ""
            }`,
        menu: () =>
            `bg-white-100 dark:bg-white-700 border-2 border-neutral-300 dark:border-neutral-600 ${style}`,
        singleValue: () => `text-gray-700 dark:text-gray-900  ${style}`,
        placeholder: () => `text-neutral-400 ${style} `,
        "indicator-separator": () => " bg-neutral-400",
        input: () => `text-neutral-600 dark:text-neutral-200  ${style}`,
    };

    return (
        <div className=" mb-3">
            <label
                htmlFor={"select-" + name}
                className="block mb-2 text-slate-900 dark:text-gray-200"
            >
                {label}
            </label>
            <SelectI
                id={"select-" + name}
                className={" text-gray-700 dark:text-gray-900 "}
                {...rest}
                onChange={(selected) => {
                    let value;
                    if (Array.isArray(selected)) {
                        value = selected.map((item) => {
                            return item.value;
                        });
                    } else {
                        value = selected.value;
                    }
                    const obj = {
                        target: {
                            name: name,
                            value: value,
                            type: "select",
                        },
                    };

                    onChange(obj);
                }}
                classNames={customStyles}
                isSearchable
            />
            {error && (
                <div
                    style={{
                        width: "100%",
                        marginTop: "0.25rem",
                        fontSize: ".875em",
                        color: "#dc3545",
                    }}
                    className="custom-invalid-feedback"
                >
                    {error}
                </div>
            )}
        </div>
    );
};

export default Select;
