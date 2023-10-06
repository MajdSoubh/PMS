import React from "react";
import { useTranslation, withTranslation } from "react-i18next";
import { GoAlert } from "react-icons/go";
import { useState } from "react";
import { MdDelete } from "react-icons/md";

const RemoveModal = ({ onConfirm, id }) => {
    const [showModal, setShowModal] = useState(false);
    const { t } = useTranslation();
    return (
        <>
            <button
                style={{
                    fontSize: "1.2rem",
                }}
                className="hover:bg-red-500 bg-red-600 text-white active:bg-yellow-500 font-bold uppercase text-xs px-4 py-2 rounded shadow hover:shadow-md outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150"
                onClick={() => setShowModal(true)}
            >
                <MdDelete />
            </button>
            {showModal ? (
                <>
                    <div
                        style={{ zIndex: "400" }}
                        className="justify-center items-center flex overflow-x-hidden overflow-y-auto fixed inset-0 z-400 outline-none focus:outline-none"
                    >
                        <div className="relative w-auto my-6 mx-auto max-w-sm">
                            {/*content*/}
                            <div className="dark:bg-secondary-dark-bg border-0 rounded-lg shadow-lg relative flex flex-col w-full bg-white outline-none focus:outline-none">
                                <div className="relative px-7 my-5 flex-auto">
                                    <GoAlert className="text-red-600 m-auto text-6xl" />
                                    <p className="mt-4 text-slate-500 text-lg leading-relaxed">
                                        {t("remove.title")} {id} ?
                                    </p>
                                </div>
                                {/*footer*/}
                                <div className="flex items-center justify-end my-4 px-4 rounded-b">
                                    <button
                                        className="text-red-600 hover:text-red-500  font-bold uppercase px-6 py-2 text-sm "
                                        type="button"
                                        onClick={() => {
                                            setShowModal(false);
                                            onConfirm();
                                        }}
                                    >
                                        {t("remove.confirm")}
                                    </button>
                                    <button
                                        className="hover:bg-red-500 bg-red-600 text-white  font-bold uppercase text-sm px-6 py-2 rounded "
                                        type="button"
                                        onClick={() => setShowModal(false)}
                                    >
                                        {t("remove.close")}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div
                        style={{ zIndex: "300" }}
                        className="opacity-25 fixed inset-0  bg-black"
                    ></div>
                </>
            ) : null}
        </>
    );
};

export default withTranslation()(RemoveModal);
