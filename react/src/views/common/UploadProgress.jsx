import React from "react";
import { useEffect } from "react";

const UploadProgress = ({ progress }) => {
    useEffect(() => {}, [progress]);
    return (
        <React.Fragment>
            {progress != 0 && (
                <div
                    style={{
                        position: "relative",
                        width: "100%",
                        height: "1.2rem",
                        border: "1px solid #ced4da",
                        borderRadius: "3px",
                        marginBottom: "1rem",
                    }}
                >
                    <span
                        style={{
                            position: "absolute",
                            left: "50%",
                            top: "0",
                            fontSize: "0.8rem",
                        }}
                        className="text-black-50"
                    >
                        {progress}%
                    </span>
                    <div
                        style={{
                            width: `${progress}%`,
                            height: "100%",
                            backgroundColor: "#4dcff7",
                            borderRadius: "3px",
                        }}
                    ></div>
                </div>
            )}
        </React.Fragment>
    );
};

export default UploadProgress;
