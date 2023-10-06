import { HiUserGroup } from "react-icons/hi";

import { GiMedicinePills } from "react-icons/gi";

import { GiOpenChest, GiProfit } from "react-icons/gi";

import { MdRunningWithErrors, MdLocalShipping } from "react-icons/md";

import { BsSafe2Fill } from "react-icons/bs";
import { MdSpaceDashboard, MdMedication } from "react-icons/md";
import {
    FaFileInvoiceDollar,
    FaFileInvoice,
    FaNotesMedical,
} from "react-icons/fa";
import {
    BsPersonFillAdd,
    BsPersonFill,
    BsFillPersonVcardFill,
} from "react-icons/bs";
import { RiStockFill } from "react-icons/ri";
import { GiLockedChest } from "react-icons/gi";
import { ImUsers } from "react-icons/im";

import { RiStockLine } from "react-icons/ri";
import i18n from "../i18n";

export const links = [
    {
        title: i18n.t("links.dashboard"),
        links: [
            {
                name: i18n.t("links.dashboard"),
                address: "",
                icon: <MdSpaceDashboard className="text-xl" />,
            },
        ],
    },

    {
        title: i18n.t("links.invoices"),
        links: [
            {
                name: i18n.t("links.addInvoice"),
                address: "invoice/add",
                icon: <FaFileInvoiceDollar className="text-xl" />,
                permissions: ["invoice-create"],
            },
            {
                name: i18n.t("links.manageInvoices"),
                address: "invoices",
                icon: <FaFileInvoice className="text-xl" />,
                permissions: ["invoice-list"],
            },
        ],
        permissions: [
            "invoice-list",
            "invoice-create",
            "invoice-edit",
            "invoice-delete",
        ],
    },
    {
        title: i18n.t("links.customers"),
        links: [
            {
                name: i18n.t("links.manageCustomers"),
                address: "customers",
                icon: <BsFillPersonVcardFill className="text-xl" />,
                permissions: ["customer-list"],
            },
        ],
        permissions: [
            "customer-list",
            "customer-create",
            "customer-edit",
            "customer-delete",
        ],
    },
    {
        title: i18n.t("links.drugs"),
        links: [
            {
                name: i18n.t("links.manageDrugs"),
                address: "drugs",
                icon: <FaNotesMedical className="text-xl" />,
            },
            {
                name: i18n.t("links.manageStock"),
                address: "stocks",
                icon: <RiStockFill className="text-xl" />,
                permissions: ["stock-list"],
            },
        ],
        permissions: [
            "drug-list",
            "drug-create",
            "drug-edit",
            "drug-delete",
            "stock-list",
            "stock-create",
            "stock-edit",
            "stock-delete",
        ],
    },
    {
        title: i18n.t("links.suppliers"),
        links: [
            {
                name: i18n.t("links.manageSupplier"),
                address: "suppliers",
                icon: <BsFillPersonVcardFill className="text-xl" />,
            },
        ],
        permissions: [
            "supplier-list",
            "supplier-create",
            "supplier-edit",
            "supplier-delete",
        ],
    },
    {
        title: i18n.t("links.purchases"),
        links: [
            {
                name: i18n.t("links.addPurchase"),
                address: "purchase/add",
                icon: <FaFileInvoiceDollar className="text-xl" />,
            },
            {
                name: i18n.t("links.managePurchase"),
                address: "purchases",
                icon: <FaFileInvoice className="text-xl" />,
            },
        ],
        permissions: [
            "purchase-list",
            "purchase-create",
            "purchase-edit",
            "purchase-delete",
        ],
    },
    {
        title: i18n.t("links.vouchers"),
        links: [
            {
                name: i18n.t("links.manageVoucher"),
                address: "vouchers",
                icon: <GiOpenChest className="text-xl" />,
            },
        ],
        permissions: [
            "voucher-list",
            "voucher-create",
            "voucher-edit",
            "voucher-delete",
        ],
    },
    {
        title: i18n.t("links.employees"),
        links: [
            {
                name: i18n.t("links.manageEmployee"),
                address: "employees",
                icon: <ImUsers className="text-xl" />,
            },
        ],
        permissions: [
            "employee-list",
            "employee-create",
            "employee-edit",
            "employee-delete",
        ],
    },
];

export const dashboardCards = [
    {
        icon: <HiUserGroup />,
        key: "customers",
        permissions: [
            "customer-list",
            "customer-create",
            "customer-edit",
            "customer-delete",
        ],
        default: "0",
        percentage: "-4%",
        title: i18n.t("cards.totalCustomers"),
        iconColor: "#03C9D7",
        iconBg: "#E5FAFB",
        pcColor: "red",
    },
    {
        icon: <MdLocalShipping />,
        key: "suppliers",
        permissions: [
            "supplier-list",
            "supplier-create",
            "supplier-edit",
            "supplier-delete",
        ],
        default: "0",
        percentage: "+23%",
        title: i18n.t("cards.totalSuppliers"),
        iconColor: "rgb(255, 244, 229)",
        iconBg: "rgb(254, 201, 15)",
        pcColor: "green",
    },
    {
        icon: <GiMedicinePills />,
        key: "drugs",
        permissions: ["drug-list", "drug-create", "drug-edit", "drug-delete"],
        default: "0",
        percentage: "+38%",
        title: i18n.t("cards.totalDrugs"),
        iconColor: "rgb(228, 106, 118)",
        iconBg: "rgb(255, 244, 229)",
        pcColor: "green",
    },
    {
        icon: <GiOpenChest />,
        key: "chest",
        permissions: [
            [
                "voucher-list",
                "voucher-create",
                "voucher-edit",
                "voucher-delete",
            ],
            [
                "purchase-list",
                "purchase-create",
                "purchase-edit",
                "purchase-delete",
            ],
            [
                "invoice-list",
                "invoice-create",
                "invoice-edit",
                "invoice-delete",
            ],
        ],
        default: "0",
        percentage: "-12%",
        title: i18n.t("cards.chest"),
        iconColor: "rgb(0, 194, 146)",
        iconBg: "rgb(235, 250, 242)",
        pcColor: "red",
    },
    {
        icon: <GiProfit />,
        key: "earnings",
        permissions: [
            [
                "voucher-list",
                "voucher-create",
                "voucher-edit",
                "voucher-delete",
            ],
            [
                "purchase-list",
                "purchase-create",
                "purchase-edit",
                "purchase-delete",
            ],
            [
                "invoice-list",
                "invoice-create",
                "invoice-edit",
                "invoice-delete",
            ],
        ],
        default: "0",
        percentage: "-12%",
        title: i18n.t("cards.earnings"),
        iconColor: "rgb(0, 194, 146)",
        iconBg: "rgb(235, 250, 242)",
        pcColor: "red",
    },
    {
        icon: <RiStockLine />,
        key: "outOfStock",
        permissions: [
            ["drug-list", "drug-create", "drug-edit", "drug-delete"],
            [
                "invoice-list",
                "invoice-create",
                "invoice-edit",
                "invoice-delete",
            ],
            [
                "purchase-list",
                "purchase-create",
                "purchase-edit",
                "purchase-delete",
            ],
        ],
        default: "0",
        percentage: "-4%",
        title: i18n.t("cards.outOfStock"),
        iconColor: "#03C9D7",
        iconBg: "#E5FAFB",
        pcColor: "red",
    },
    {
        icon: <MdRunningWithErrors />,
        key: "expiredDrugs",
        permissions: [
            ["drug-list", "drug-create", "drug-edit", "drug-delete"],
            [
                "invoice-list",
                "invoice-create",
                "invoice-edit",
                "invoice-delete",
            ],
            [
                "purchase-list",
                "purchase-create",
                "purchase-edit",
                "purchase-delete",
            ],
        ],
        default: "0",
        percentage: "+23%",
        title: i18n.t("cards.expiredDrugs"),
        iconColor: "rgb(255, 244, 229)",
        iconBg: "rgb(254, 201, 15)",
        pcColor: "green",
    },
    {
        icon: <BsSafe2Fill />,
        key: "safe",
        permissions: [
            [
                "voucher-list",
                "voucher-create",
                "voucher-edit",
                "voucher-delete",
            ],
            [
                "purchase-list",
                "purchase-create",
                "purchase-edit",
                "purchase-delete",
            ],
            [
                "invoice-list",
                "invoice-create",
                "invoice-edit",
                "invoice-delete",
            ],
        ],
        default: "0",
        percentage: "-12%",
        title: i18n.t("cards.safe"),
        iconColor: "rgb(0, 194, 146)",
        iconBg: "rgb(235, 250, 242)",
        pcColor: "red",
    },

    {
        icon: <BsPersonFill />,
        key: "bestCustomer",
        default: "No Customer",
        permissions: [
            [
                "customer-list",
                "customer-create",
                "customer-edit",
                "customer-delete",
            ],
            [
                "invoice-list",
                "invoice-create",
                "invoice-edit",
                "invoice-delete",
            ],
        ],
        percentage: "+38%",
        title: i18n.t("cards.bestCustomer"),
        iconColor: "rgb(228, 106, 118)",
        iconBg: "rgb(255, 244, 229)",
        pcColor: "green",
    },
    {
        icon: <MdLocalShipping />,
        key: "bestSupplier",
        permissions: [
            [
                "supplier-list",
                "supplier-create",
                "supplier-edit",
                "supplier-delete",
            ],
            [
                "purchase-list",
                "purchase-create",
                "purchase-edit",
                "purchase-delete",
            ],
        ],
        default: "No Supplier",
        percentage: "-12%",
        title: i18n.t("cards.bestSupplier"),
        iconColor: "rgb(0, 194, 146)",
        iconBg: "rgb(235, 250, 242)",
        pcColor: "red",
    },
];
/* Line Chart */

export const LinePrimaryXAxis = {
    valueType: "DateTime",
    labelFormat: "y",
    intervalType: "Years",
    edgeLabelPlacement: "Shift",
    majorGridLines: { width: 0 },
    background: "white",
};

export const LinePrimaryYAxis = {
    labelFormat: "{value}",
    rangePadding: "None",
    minimum: 0,
    maximum: 70000,
    interval: 10000,
    lineStyle: { width: 0 },
    majorTickLines: { width: 0 },
    minorTickLines: { width: 0 },
};
export const themeColors = [
    {
        name: "blue-theme",
        color: "#1A97F5",
    },
    {
        name: "green-theme",
        color: "#03C9D7",
    },
    {
        name: "purple-theme",
        color: "#7352FF",
    },
    {
        name: "red-theme",
        color: "#FF5C8E",
    },
    {
        name: "indigo-theme",
        color: "#1E4DB7",
    },
    {
        color: "#FB9678",
        name: "orange-theme",
    },
];

export const mapDataToLineChart = (data) => {
    const _data = data.map(({ total, year }) => {
        return {
            x: new Date(year),
            y: total,
        };
    });
    return _data;
};
/* Pie Chart */
export const mapDataToPieChart = (names, data, count) => {
    data = data.map((item) => {
        const newItem = {};
        for (const key of Object.keys(item)) {
            newItem[names[key]] = item[key];
        }
        newItem.text = ((newItem.y * 100) / count).toFixed(1) + "%";

        return newItem;
    });
    return data;
};

/**
 *
 * @param {string} user
 * @param {*} permissions
 * @returns boolean
 * check if user has this permission
 */
export function can(user, permissions) {
    /* Check if user is owner of the pharmacy */
    if (user.isOwner) return true;

    /* Check if permissions is Array Object */
    if (permissions instanceof Array) {
        let authorize = true;
        for (let permission of permissions) {
            /* If array 2-dimensional check if user can access at least all 1-d array of permissions */
            if (permission instanceof Array) {
                authorize = can(user, permission);
                if (authorize) break;
            } /* If array 1-dimensional check if user can access all array permissions  */ else {
                if (!can(user, permission)) authorize = false;
                break;
            }
        }

        return authorize;
    }

    /* if permission is string check if user has this ability */
    if (user.permissions.find((permission) => permission.name == permissions))
        return true;
    return false;
}
