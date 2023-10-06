import { useState } from "react";
import React, { useEffect } from "react";
import Table from "../common/Table";
import _ from "lodash";
import { Header } from "../../views/common";
import {
    deleteInvoice,
    editInvoice,
    getInvoices,
} from "../../services/invoice";
import { useUIContext } from "../../context/UIContext";
import { PDFDownloadLink } from "@react-pdf/renderer";
import { CSVLink } from "react-csv";
import { FaFilePdf } from "react-icons/fa";
import { FaFileCsv } from "react-icons/fa";
import { PDF } from "./PDF";
import RemoveModal from "../common/Modals/RemoveModal";
import Alert from "../common/Alert";
import TableModal from "../common/Modals/TableModal";
import { useAuthContext } from "../../context/AuthContext";
import { useTranslation } from "react-i18next";

const ManageInvoice = () => {
    const { currentColor } = useUIContext();
    const [data, setData] = useState([]);
    const [searchTerm, setSearchTerm] = useState("");
    const [selectedColumn, setSelectedColumn] = useState("");
    const [message, setMessage] = useState("");
    const { user } = useAuthContext();
    const { t } = useTranslation();
    useEffect(() => {
        fetchInvoices();
    }, []);
    const fetchInvoices = async () => {
        try {
            const { data: resData } = await getInvoices();
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

            const { data: resData } = await deleteInvoice(id);
            const msg = resData.message;
            setMessage({
                type: "success",
                body: msg || `Invoice with id ${id} deleted successfully`,
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
    const handleInvoicePaid = async (id, totalNet) => {
        try {
            const { data: resData } = await editInvoice(id, { paid: totalNet });
            fetchInvoices();
            const msg = resData.message;
            setMessage({
                type: "success",
                body: msg || `Invoice with id ${id} paided completely`,
            });
        } catch (ex) {
            console.log(ex);
        }
    };
    const columns = [
        {
            key: "id",
            name: t("invoice.id"),
            selector: (row) => row.id,
            sortable: true,
        },
        {
            key: "customer.name",
            name: t("invoice.customer"),
            selector: (row) => row.customer.name,
            sortable: true,
            format: (row) => (row.customer ? row.customer.name : "No Customer"),
        },
        {
            key: "total",
            name: t("invoice.total"),
            selector: (row) => row.total,
            sortable: true,
        },
        {
            key: "totalDiscount",
            name: t("invoice.discount"),
            selector: (row) => row.totalDiscount,
            sortable: true,
        },
        {
            key: "totalNet",
            name: t("invoice.totalNet"),
            selector: (row) => row.totalNet,
            sortable: true,
        },
        {
            key: "paid",
            name: t("invoice.paid"),
            selector: (row) => row.paid,
            sortable: true,
        },
        {
            key: "totalProfit",
            name: t("invoice.totalProfit"),
            selector: (row) => row.totalProfit,
            sortable: true,
        },
        {
            key: "date",
            name: t("invoice.date"),
            selector: (row) => row.date,
            sortable: true,
        },
        {
            key: "action",
            name: t("invoice.action"),
            sortable: false,
            cell: (row) => renderTableButtons(row),
        },
    ];
    const itemsColumns = [
        {
            key: "id",
            name: t("invoice.id"),
            selector: (row) => row.id,
            sortable: true,
        },
        {
            key: "drug",
            name: t("invoice.drug"),
            selector: (row) => row.drug.name,
            sortable: true,
        },
        {
            key: "qty",
            name: t("invoice.quantity"),
            selector: (row) => row.qty,
            sortable: true,
        },
        {
            key: "exp",
            name: t("invoice.expire"),
            selector: (row) => row.exp,
            sortable: true,
        },
        {
            key: "price",
            name: t("invoice.price"),
            selector: (row) => row.price,
            sortable: true,
        },
        {
            key: "discount",
            name: t("invoice.discount"),
            selector: (row) => row.discount,
            sortable: true,
        },
        {
            key: "cost",
            name: t("invoice.cost"),
            selector: (row) => row.cost,
            sortable: true,
        },
    ];
    const csvData = filteredData().map((row) => ({
        Id: row.id,
        Customer: row.customer,
        Total: row.total,
        "Total Discount": row.totalDiscount,
        "Total Net": row.totalNet,
        "Total Profit": row.totalProfit,
        Date: new Date(row.date).toLocaleDateString(),
    }));
    const renderTableButtons = (row) => {
        return (
            <div className="flex items-center justify-center">
                {row.paid != row.totalNet && (
                    <button
                        className="text-white font-extrabold w-max active:bg-yellow-600 bg-green-500  hover:bg-green-400 uppercase text-xs px-4 py-2 rounded shadow hover:shadow-md outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150"
                        onClick={() => handleInvoicePaid(row.id, row.totalNet)}
                    >
                        $
                    </button>
                )}
                <TableModal data={row.items} columns={itemsColumns} />
                <RemoveModal id={row.id} onConfirm={() => handleDelete(row)} />
            </div>
        );
    };
    return (
        <div className="main-container mt-24 md:m-10 p-12 md:p-10 bg-white rounded-3xl drop-shadow-2xl dark:text-gray-200 dark:bg-secondary-dark-bg dark:[#484B52]">
            <Header title={t("invoice.manageInvoices")} />
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
            .sc-cwHptR.brFloq * {
                // margin-left: 15px;
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
                            className="rounded-lg border border-gray-300 py-1 px-4 bg-white text-gray-700 font-semibold focus:outline-none focus:shadow-outline"
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
                        {
                            <PDFDownloadLink
                                document={
                                    <PDF
                                        user={user}
                                        columns={columns}
                                        data={filteredData()}
                                        name={"Invoices Report"}
                                    />
                                }
                                fileName={`invoices-${new Date().toLocaleDateString()}.pdf`}
                                className="text-xl hover:bg-blue-500 bg-blue-600 text-white active:bg-yellow-600 font-bold uppercase  px-3 py-2 rounded shadow hover:shadow-md outline-none focus:outline-none  ease-linear transition-all duration-150"
                            >
                                <FaFilePdf />
                            </PDFDownloadLink>
                        }
                        <CSVLink
                            data={csvData}
                            filename={`invoices-${new Date().toLocaleDateString()}.csv`}
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

export default ManageInvoice;
