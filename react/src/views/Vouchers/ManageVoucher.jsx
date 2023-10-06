import { useState } from "react";
import React, { useEffect } from "react";
import Table from "../common/Table";
import _ from "lodash";
import { Header } from "../../views/common";
import { deleteVoucher, getVouchers } from "../../services/voucher";
import { useUIContext } from "../../context/UIContext";
import { PDFDownloadLink } from "@react-pdf/renderer";
import { CSVLink } from "react-csv";
import { FaFilePdf } from "react-icons/fa";
import { FaFileCsv } from "react-icons/fa";
import { PDF } from "../common/PDF";
import RemoveModal from "../common/Modals/RemoveModal";
import EditVoucherModal from "../common/Modals/EditVoucherModal";
import AddVoucherModal from "../common/Modals/AddVoucherModal";
import Alert from "../common/Alert";
import { useAuthContext } from "../../context/AuthContext";
import { useTranslation } from "react-i18next";
const ManageVoucher = () => {
    const { currentColor } = useUIContext();
    const [data, setData] = useState([]);
    const [searchTerm, setSearchTerm] = useState("");
    const [selectedColumn, setSelectedColumn] = useState("");
    const [message, setMessage] = useState("");
    const { user } = useAuthContext();
    const { t } = useTranslation();
    useEffect(() => {
        fetchVouchers();
    }, []);
    const fetchVouchers = async () => {
        try {
            const { data: resData } = await getVouchers();
            setData(resData.data);
        } catch (ex) {
            console.log(ex);
        }
    };
    const handleDelete = async ({ id }) => {
        try {
            let dataCopy = [...data];

            dataCopy = dataCopy.filter((item) => item.id != id);
            setData(dataCopy);

            const { data: resData } = await deleteVoucher(id);
            const msg = resData.message;
            setMessage({
                type: "success",
                body: msg || `Voucher with id ${id} deleted successfully`,
            });
        } catch (ex) {
            console.log(ex);
        }
    };
    const filteredData = () => {
        if (!selectedColumn || !searchTerm) return data;
        const filtered = data.filter((item) =>
            _.get(item, selectedColumn)
                ? _.get(item, selectedColumn)
                      .toString()
                      .toLowerCase()
                      .includes(searchTerm.toLowerCase())
                : false
        );
        return filtered;
    };
    const columns = [
        {
            key: "id",
            name: t("voucher.id"),
            selector: (row) => row.id,
            sortable: true,
        },

        {
            key: "type",
            name: t("voucher.type"),
            selector: (row) => row.type,
            sortable: true,
        },
        {
            key: "amount",
            name: t("voucher.amount"),
            selector: (row) => row.amount,
            sortable: true,
        },
        {
            key: "description",
            name: t("voucher.description"),
            selector: (row) => row.description,
            sortable: true,
            format: (row) => (row.description ? row.description : "--"),
        },
        {
            key: "date",
            name: t("voucher.date"),
            selector: (row) => row.date,
            sortable: true,
        },
        {
            key: "action",
            name: t("voucher.action"),
            sortable: false,
            cell: (row) => renderTableButtons(row),
        },
    ];

    const csvData = filteredData().map((row) => ({
        Id: row.id,
        Name: row.name,
        Generic: row.generic,
        Description: row.description,
        Price: row.price,
    }));
    const notify = (message, type) => {
        setMessage({
            type: type,
            body: message,
        });
        fetchVouchers();
    };
    const renderTableButtons = (row) => {
        return (
            <div className="flex items-center justify-center">
                <RemoveModal id={row.id} onConfirm={() => handleDelete(row)} />
                <EditVoucherModal
                    onSuccess={(message) => notify(message)}
                    data={row}
                />
            </div>
        );
    };
    return (
        <div className="main-container mt-24 md:m-10 p-12 bg-white rounded-3xl shadow-md dark:text-gray-200 dark:bg-secondary-dark-bg dark:[#484B52]">
            <Header title={t("voucher.manageVouchers")} />
            <style>
                {`
            .sc-aXZVg.bvmOqp.rdt_Table * {
                font-size: 18px;
                align-items: center;
                justify-content: center;
            }

            .dark .sc-aXZVg.bvmOqp.rdt_Table * {
                color:white;
            }

            .dark .sc-dAbbOL.kdgCYE.rdt_TableBody :hover {
                background-color: rgb(30 32 30 / var(--tw-bg-opacity));
            }

            .sc-gEvEer.ckuglD.rdt_TableHead {
                font-weight: bold;
                width: 100%;
            }
            .sc-cwHptR.brFloq {
                margin-left: 15px;
            }
            .dark .sc-bmzYkS.fimDOL {
                background-color: white;
            }

            // .dark .sc-koXPp.cfOttd {
            //     background-color: white;
            // }

            .sc-eeDRCY.oJgsB.rdt_Pagination {
                font-size: 15px;
                font-weight: bold;
                font-family: sans-serif;

            }
          `}
            </style>
            {message && <Alert message={message.body} type={message.type} />}
            <div className="p-4">
                <div className="flex flex-wrap justify-between mb-4 gap-5">
                    <div className="w-full md:w-1/3">
                        <select
                            className="rounded-lg border border-gray-300 py-1 px-4  bg-white text-gray-700 font-semibold focus:outline-none focus:shadow-outline  "
                            onChange={(e) => setSelectedColumn(e.target.value)}
                            value={selectedColumn}
                        >
                            <option value="" disabled>
                                {t("placeHolders.search")}
                            </option>
                            {columns.map((column) => {
                                if (column.key !== "action") {
                                    return (
                                        <option
                                            key={column.key}
                                            value={column.key}
                                        >
                                            {column.name}
                                        </option>
                                    );
                                }
                                return null;
                            })}
                        </select>
                        {selectedColumn && (
                            <input
                                className="mt-5 lg:ml-4  md:ml-0 sm:ml-4 rounded-lg border  border-gray-300 py-1 px-4 w-10/5 text-gray-700 focus:outline-none focus:shadow-outline"
                                type="text"
                                placeholder={`Search by ${selectedColumn}`}
                                value={searchTerm}
                                onChange={(e) => setSearchTerm(e.target.value)}
                            />
                        )}
                    </div>
                    <div className="flex justify-center items-center gap-1">
                        <AddVoucherModal
                            onSuccess={(message) => notify(message, "success")}
                        />
                        <PDFDownloadLink
                            document={
                                <PDF
                                    columns={columns}
                                    data={filteredData()}
                                    user={user}
                                    name={"Vouchers Report"}
                                />
                            }
                            fileName={`voucher-${new Date().toLocaleDateString()}.pdf`}
                            className="text-xl hover:bg-blue-500 bg-blue-600 text-white active:bg-yellow-600 font-bold uppercase  px-3 py-2 rounded shadow hover:shadow-md outline-none focus:outline-none  ease-linear transition-all duration-150"
                        >
                            <FaFilePdf />
                        </PDFDownloadLink>

                        <CSVLink
                            data={csvData}
                            filename={`voucher-${new Date().toLocaleDateString()}.csv`}
                            className="text-xl hover:bg-blue-500 bg-blue-600 text-white active:bg-yellow-600 font-bold uppercase  px-3 py-2 rounded shadow hover:shadow-md outline-none focus:outline-none  ease-linear transition-all duration-150"
                        >
                            <FaFileCsv />
                        </CSVLink>
                    </div>
                </div>
                <Table data={filteredData()} columns={columns} />
            </div>
        </div>
    );
};

export default ManageVoucher;
