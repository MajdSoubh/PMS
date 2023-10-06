// import React from "react";
// import { withTranslation } from "react-i18next";
// import { GoAlert } from "react-icons/go";
// import { useState } from "react";
// import { BiShow } from "react-icons/bi";
// import Table from "../Table";

// const TableModal = ({ data, columns }) => {
//     const [showModal, setShowModal] = useState(false);

//     return (
//         <>
//             <button
//                 style={{
//                     fontSize: "1.2rem",
//                 }}
//                 className="text-white  active:bg-yellow-600 bg-blue-500  hover:bg-blue-400 font-bold uppercase text-xs px-4 py-2 rounded shadow hover:shadow-md outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150"
//                 onClick={() => setShowModal(true)}
//             >
//                 <BiShow />
//             </button>
//             {showModal ? (
//                 <>
//                     <div
//                         style={{ zIndex: "400" }}
//                         className="flex justify-center items-center   overflow-x-visible overflow-y-auto fixed inset-0 z-400 outline-none focus:outline-none"
//                     >
//                         <div className="relative w-auto my-6 mx-auto max-w-3lg">
//                             {/*content*/}
//                             <div className="border-2 rounded-2xl shadow-lg relative flex flex-col w-full bg-white dark:bg-secondary-dark-bg outline-none focus:outline-none">
//                                 <div className="">
//                                     {" "}
//                                     <Table data={data} columns={columns} />
//                                 </div>
//                                 {/*footer*/}
//                                 <div className="flex items-center justify-end px-4 rounded-lg">
//                                     <button
//                                         className="text-white rounded-lg bg-red-600 background-transparent font-bold uppercase px-6 py-2 text-sm outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150"
//                                         type="button"
//                                         onClick={() => setShowModal(false)}
//                                     >
//                                         Close
//                                     </button>
//                                 </div>
//                             </div>
//                         </div>
//                     </div>
//                     <div
//                         style={{ zIndex: "300" }}
//                         className="opacity-25 fixed inset-0  bg-black"
//                     ></div>
//                 </>
//             ) : null}
//         </>
//     );
// };

// export default withTranslation()(TableModal);
import React from "react";
import { withTranslation } from "react-i18next";
import { GoAlert } from "react-icons/go";
import { useState } from "react";
import { BiShow } from "react-icons/bi";
import Table from "../Table";

const TableModal = ({ data, columns }) => {
    const [showModal, setShowModal] = useState(false);

    return (
        <>
            <button
                style={{
                    fontSize: "1.2rem",
                }}
                className="text-white  active:bg-yellow-600 bg-blue-500  hover:bg-blue-400 font-bold uppercase text-xs px-4 py-2 rounded shadow hover:shadow-md outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150"
                onClick={() => setShowModal(true)}
            >
                <BiShow />
            </button>
            {showModal ? (
                <>
                    <div
                        style={{ zIndex: "400" }}
                        className="justify-center items-center  flex overflow-x-hidden overflow-y-auto fixed inset-0 z-400 outline-none focus:outline-none"
                    >
                        <div className="relative w-auto my-6 mx-auto max-w-3lg">
                            {/*content*/}
                            <div className="border-0 rounded-lg shadow-lg relative flex flex-col w-full bg-white outline-none focus:outline-none">
                                <div className="relative px-7  flex-auto">
                                    {" "}
                                    <Table data={data} columns={columns} />
                                </div>
                                {/*footer*/}
                                <div className="flex items-center justify-end px-4 rounded-b">
                                    <button
                                        className="text-red-600 background-transparent font-bold uppercase px-6 py-2 text-sm outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150"
                                        type="button"
                                        onClick={() => setShowModal(false)}
                                    >
                                        Close
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

export default withTranslation()(TableModal);
