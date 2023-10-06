import _ from "lodash";
import React, { useState } from "react";
import { useEffect } from "react";
import uplodIcon from "../../assets/images/upload.png";
const ImagesUploaders = ({
    className,
    onChange,
    onUnSelect,
    value,
    name,
    label,
    error,
    ...rest
}) => {
    const [preview, setPreview] = useState([]);

    function handlePreviewChange() {
        const files = [...value];

        const updatedPreview = [];
        _.forEach(files, (img) => {
            updatedPreview.push(URL.createObjectURL(img));
        });
        setPreview(updatedPreview);
    }
    useEffect(() => {
        handlePreviewChange();
        return function () {
            _.forEach(preview, (img) => {
                const s = URL.revokeObjectURL(img);
            });
        };
    }, [value]);

    return (
        <div className="form-group my-4">
            <label className="form-label">{label}</label>
            <div
                style={{
                    display: "flex",
                    flexDirection: "row",
                    flexWrap: "wrap",
                    gap: "1rem",
                }}
                className={className || "file-input-container"}
            >
                {preview.map((img, ind) => {
                    return (
                        <div
                            key={ind}
                            style={{ position: "relative" }}
                            className="preview-container"
                        >
                            <span
                                key={ind}
                                data-ff={ind}
                                onClick={() => onUnSelect(name, ind)}
                                style={{
                                    position: "absolute",
                                    right: "0",
                                    top: "0",
                                    width: "20px",
                                    height: "20px",
                                    zIndex: "1",
                                    cursor: "pointer",
                                    color: "#d82626",
                                }}
                            >
                                <i className="bi bi-x-circle-fill"></i>
                            </span>
                            <img
                                src={img}
                                alt=""
                                style={{
                                    width: "100px",
                                    height: "100px",
                                    objectFit: "cover",
                                }}
                            />
                        </div>
                    );
                })}
                <div
                    className=" mb-3 "
                    style={{
                        position: "relative",
                        width: "100px",
                        height: "100px",
                        border: "2px dashed #ccc",
                    }}
                >
                    <img
                        className="file-uploader-icon"
                        src={uplodIcon}
                        alt="Upload-Icon"
                        style={{
                            width: "35px",
                            height: "35px",
                            position: "absolute",
                            top: "50%",
                            left: "50%",
                            transform: "translate(-50%,-50%)",
                        }}
                    />
                    <input
                        {...rest}
                        name={name}
                        encType="multipart/form-data"
                        onChange={onChange}
                        accept="*/*"
                        style={{
                            width: "100px",
                            height: "100px",
                            opacity: "0",
                        }}
                        className="file-input"
                        type="file"
                    />
                </div>
            </div>
            {error && <div className="alert alert-danger ">{error}</div>}
        </div>
    );
};

export default ImagesUploaders;
