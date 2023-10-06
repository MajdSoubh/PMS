import { useState } from "react";
import React, { useEffect } from "react";
import Table from "../common/Table";
import _ from "lodash";
import { Header } from "../../views/common";
import { deleteStock, getStocks } from "../../services/stock";
import { getDrugs } from "../../services/drug";
import { useUIContext } from "../../context/UIContext";
import { PDFDownloadLink } from "@react-pdf/renderer";
import { CSVLink } from "react-csv";
import { FaFilePdf } from "react-icons/fa";
import { FaFileCsv } from "react-icons/fa";
import { PDF } from "../common/PDF";
import RemoveModal from "../common/Modals/RemoveModal";
import EditStockModal from "../common/Modals/EditStockModal";
import Alert from "../common/Alert";
import AddStockModal from "../common/Modals/AddStockModal";
import { useAuthContext } from "../../context/AuthContext";
import { useTranslation } from "react-i18next";

const ManageStock = () => {
    const { currentColor } = useUIContext();
    const [data, setData] = useState([]);
    const [searchTerm, setSearchTerm] = useState("");
    const [selectedColumn, setSelectedColumn] = useState("");
    const [message, setMessage] = useState("");
    const [drugs, setDrugs] = useState([]);
    const [expired, setExpired] = useState(false);
    const [outOfStock, setOutOfStock] = useState(false);
    const { user } = useAuthContext();
    const { t } = useTranslation();
    useEffect(() => {
        fetchStocks();
        fetchDrugs();
    }, []);
    useEffect(() => {}, [drugs]);
    const fetchStocks = async () => {
        try {
            const { data: resData } = await getStocks();
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

            const { data: resData } = await deleteStock(id);
            const msg = resData.message;
            setMessage({
                type: "success",
                body: msg || `Entry with id ${id} deleted successfully`,
            });
        } catch (ex) {
            console.log(ex);
        }
    };
    const fetchDrugs = async () => {
        try {
            const response = await getDrugs();
            const { data: resData } = response;
            const _drugs = Array.from(resData.data, ({ id, name }) => ({
                value: id,
                label: name,
            }));
            setDrugs(_drugs);
        } catch (ex) {
            console.log(ex);
        }
    };
    const filteredData = () => {
        let filtered = expired
            ? data.filter((item) => {
                  return new Date(item.exp) <= new Date();
              })
            : outOfStock
            ? data.filter((item) => {
                  return item.qty == 0;
              })
            : data;
        if (!selectedColumn || !searchTerm) return filtered;
        filtered = filtered.filter((item) =>
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
            name: t("stock.id"),
            selector: (row) => row.id,
            sortable: true,
        },

        {
            key: "drug.name",
            name: t("stock.drug"),
            selector: (row) => row.drug.name,
            sortable: true,
        },
        {
            key: "cost",
            name: t("stock.cost"),
            selector: (row) => row.cost,
            sortable: true,
        },
        {
            key: "qty",
            name: t("stock.qty"),
            selector: (row) => row.qty,
            sortable: true,
        },
        {
            key: "mfd",
            name: t("stock.mfd"),
            selector: (row) => row.mfd,
            sortable: true,
        },
        {
            key: "exp",
            name: t("stock.exp"),
            selector: (row) => row.exp,
            sortable: true,
        },

        {
            key: "action",
            name: t("stock.action"),
            sortable: false,
            cell: (row) => renderTableButtons(row),
        },
    ];
    const toggelExpired = () => {
        setExpired(!expired);
    };
    const toggelOutOfStock = () => {
        setOutOfStock(!outOfStock);
    };

    const csvData = filteredData().map((row) => ({
        Id: row.id,
        "Drug Name": row.drug.name,
        MFD: row.mfd,
        EXP: row.exp,
        Qty: row.qty,
        Cost: row.cost,
        Price: row.drug.price,
    }));
    const notify = (message, type) => {
        setMessage({
            type: type,
            body: message,
        });
        fetchStocks();
    };
    const renderTableButtons = (row) => {
        const { drug, ...editStockData } = row;
        editStockData.drug = row.drug.id;

        return (
            <div className="flex items-center justify-center">
                <RemoveModal id={row.id} onConfirm={() => handleDelete(row)} />
                <EditStockModal
                    onSuccess={(message) => notify(message)}
                    data={editStockData}
                    drugs={drugs}
                />
            </div>
        );
    };
    return (
        <div className="main-container mt-24 md:m-10 p-12 m-8 md:p-10 bg-white rounded-3xl shadow-md dark:text-gray-200 dark:bg-secondary-dark-bg dark:[#484B52]">
            <Header title={t("stock.manageStock")} />
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
                    <div className="flex justify-center items-center gap-1  ">
                        <button
                            onClick={toggelExpired}
                            className="hover:bg-red-500 bg-red-600 text-white active:bg-yellow-600 font-bold uppercase text-xs px-4 py-2 rounded shadow hover:shadow-md outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150"
                            style={{
                                backgroundColor: expired
                                    ? "#dc2626"
                                    : "#2563eb",
                            }}
                        >
                            {t("stock.expired")}
                        </button>
                    </div>
                    {/* <div className="flex justify-center items-center gap-1  ">
                        <button
                            onClick={toggelOutOfStock}
                            className="hover:bg-red-500 bg-orange-400 text-white active:bg-yellow-600 font-bold uppercase text-xs px-4 py-2 rounded shadow hover:shadow-md outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150"
                            style={{
                                backgroundColor: outOfStock
                                    ? "rgb(251 146 60)"
                                    : "#2563eb",
                            }}
                        >
                            {t("cards.outOfStock")}
                        </button>
                    </div> */}
                    <div className="flex justify-center items-center gap-1">
                        <AddStockModal
                            drugs={drugs}
                            onSuccess={(message) => notify(message, "success")}
                        />
                        <PDFDownloadLink
                            document={
                                <PDF
                                    columns={columns}
                                    data={filteredData()}
                                    user={user}
                                    name={"Stocks Report"}
                                />
                            }
                            fileName={`stock-${new Date().toLocaleDateString()}.pdf`}
                            className="text-xl hover:bg-blue-500 bg-blue-600 text-white active:bg-yellow-600 font-bold uppercase  px-3 py-2 rounded shadow hover:shadow-md outline-none focus:outline-none  ease-linear transition-all duration-150"
                        >
                            <FaFilePdf />
                        </PDFDownloadLink>

                        <CSVLink
                            data={csvData}
                            filename={`stock-${new Date().toLocaleDateString()}.csv`}
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

export default ManageStock;