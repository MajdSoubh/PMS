import DataTable from "react-data-table-component";
import { useTranslation } from "react-i18next";
const customStyles = {
    table: {
        style: {
            color: "white",
            backgroundColor: "",
            width: "100%",
        },
    },
    tableWrapper: {
        style: {
            display: "table",
        },
    },
    responsiveWrapper: {
        style: {},
    },
    header: {
        style: {
            fontSize: "22px",
            color: "",
            backgroundColor: "",
            minHeight: "56px",
            paddingLeft: "16px",
            paddingRight: "8px",
        },
    },
    subHeader: {
        style: {
            backgroundColor: "",
            minHeight: "52px",
        },
    },
    head: {
        style: {
            color: "black",
            fontSize: "12px",
            fontWeight: 1000,
        },
    },
    headRow: {
        style: {
            backgroundColor: "",
            minHeight: "52px",
            borderBottomWidth: "1px",
            borderBottomColor: "",
            borderBottomStyle: "solid",
            width: "1000px",
        },
        denseStyle: {
            minHeight: "32px",
        },
    },
    headCells: {
        style: {
            /*             paddingLeft: "16px",
            paddingRight: "16px",
            marginLeft: "20px",
            marginRight: "20px", */
        },
        draggingStyle: {
            cursor: "move",
        },
    },
    contextMenu: {
        style: {
            backgroundColor: "",
            fontSize: "18px",
            fontWeight: 400,
            color: "",
            paddingLeft: "16px",
            paddingRight: "8px",
            transform: "translate3d(0, -100%, 0)",
            transitionDuration: "125ms",
            transitionTimingFunction: "cubic-bezier(0, 0, 0.2, 1)",
            willChange: "transform",
        },
        activeStyle: {
            transform: "translate3d(0, 0, 0)",
        },
    },
    cells: {
        style: {
            paddingLeft: "10px",
            paddingRight: "10px",
            wordBreak: "break-word",
            textOverflow: "initial",
            minHeight: "max-content",
            // marginLeft: "30px",
            // marginRight: "30px",
        },
        draggingStyle: {},
    },
    rows: {
        style: {
            fontSize: ".9rem",
            fontWeight: 400,
            color: "black",
            backgroundColor: "",
            width: "1000px",
            minHeight: "max-content",
            "&:not(:last-of-type)": {
                borderBottomStyle: "solid",
                borderBottomWidth: "1px",
                borderBottomColor: "",
            },
        },
        denseStyle: {
            minHeight: "32px",
        },
        selectedHighlightStyle: {
            // use nth-of-type(n) to override other nth selectors
            "&:nth-of-type(n)": {
                color: "",
                backgroundColor: "",
                borderBottomColor: "",
            },
        },
        highlightOnHoverStyle: {
            color: "",
            backgroundColor: "#F5F5F5",
            transitionDuration: "0.15s",
            transitionProperty: "background-color",
            borderBottomColor: "",
            outlineStyle: "solid",
            outlineWidth: "1px",
            outlineColor: "#D5D5D5",
        },
        stripedStyle: {
            color: "",
            backgroundColor: "",
        },
    },

    pagination: {
        style: {
            color: "",
            fontSize: "13px",
            minHeight: "56px",
            backgroundColor: "",

            marginTop: "1rem",
            borderTopStyle: "none",
            borderTopWidth: "1px",
            borderTopColor: "",
        },
        pageButtonsStyle: {
            borderRadius: "50%",
            height: "40px",
            width: "40px",
            padding: "8px",
            margin: "px",
            cursor: "pointer",
            transition: "0.4s",
            color: "",
            fill: "",
            backgroundColor: "transparent",
            "&:disabled": {
                cursor: "unset",
                color: "",
                fill: "",
            },
            "&:hover:not(:disabled)": {
                backgroundColor: "",
            },
            "&:focus": {
                outline: "none",
                backgroundColor: "",
            },
        },
    },
    noData: {
        style: {
            display: "flex",
            alignItems: "center",
            justifyContent: "center",
            color: "",
            backgroundColor: "",
        },
    },
    progress: {
        style: {
            display: "flex",
            alignItems: "center",
            justifyContent: "center",
            color: "",
            backgroundColor: "",
        },
    },
};

const Table = ({ columns, data, ...tableProps }) => {
    const { t } = useTranslation();
    return (
        <div className="p-2">
            <DataTable
                {...tableProps}
                columns={columns}
                data={data}
                pagination
                paginationPerPage={7}
                paginationRowsPerPageOptions={[5, 7, 10, 15, 20]}
                paginationComponentOptions={{
                    rowsPerPageText: t("table.pageRows"),
                    rangeSeparatorText: t("table.of"),
                    noRowsPerPage: false,
                    selectAllRowsItem: false,
                    selectPerPageItem: false,
                }}
                noHeader
                striped
                highlightOnHover
                customStyles={customStyles}
                pointerOnHover
                responsive
                noDataComponent={
                    <div className="text-gray-500 text-center">
                        There are no records to display
                    </div>
                }
            />
        </div>
    );
};

export default Table;
