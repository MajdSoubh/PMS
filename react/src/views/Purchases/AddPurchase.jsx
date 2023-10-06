import React from "react";
import { Header } from "../../views/common";
import _ from "lodash";
import "../../assets/styles/addInvoice.css";

import { IoMdAddCircle, IoMdRemoveCircle } from "react-icons/io";

import { UIContext } from "../../context/UIContext";
import Form from "../common/Form";

import { getDrugs } from "../../services/drug";
import { getSuppliers } from "../../services/supplier";
import { addPurchase } from "../../services/purchase";
import Alert from "../common/Alert";
import { withTranslation } from "react-i18next";
import i18n from "../../i18n";

class AddPurchase extends Form {
    state = {
        message: {},
        data: {
            number: "",
            supplier: "",
            date: "",
            paid: 0,
            rest: 0,
            drugOptions: [],
            supplierOptions: [],
            totalPrice: 0,
            rows: [
                {
                    drug: "",
                    cost: 0,
                    mfd: "",
                    exp: "",
                    qty: "",
                    total: 0,
                    costOptions: [],
                    expOptions: [],
                },
            ],
        },
        errors: {},
    };
    currentLanguage = i18n.language;

    getClassNameBasedOnLanguage = () => {
        if (this.currentLanguage === "en") {
            return "w-full md:w-1/3 md:pl-4";
        } else if (this.currentLanguage === "ar") {
            return "w-full md:w-1/3 md:pr-4";
        } else {
            return "w-full md:w-1/3 md:pl-4";
        }
    };
    className = this.getClassNameBasedOnLanguage();
    fetchDrugs = async () => {
        try {
            const { data: resData } = await getDrugs();
            const data = _.cloneDeep(this.state.data);
            const _drugs = Array.from(resData.data, ({ id, name }) => ({
                value: id,
                label: name,
            }));
            data.drugOptions = _drugs;
            this.setState({ data });
        } catch (ex) {
            console.log(ex);
        }
    };
    fetchSuppliers = async () => {
        try {
            const { data: resData } = await getSuppliers();
            const data = _.cloneDeep(this.state.data);
            const _suppliers = Array.from(resData.data, ({ id, name }) => ({
                value: id,
                label: name,
            }));
            data.supplierOptions = _suppliers;
            this.setState({ data });
        } catch (ex) {
            console.log(ex);
        }
    };

    componentDidMount() {
        const now = new Date();
        const date = now.toISOString().slice(0, 10);
        this.setState({ data: { ...this.state.data, date } });
        this.fetchDrugs();
        this.fetchSuppliers();
    }

    checkPurchaseData = () => {
        const data = _.cloneDeep(this.state.data);
        data.totalPrice = 0;
        data.rows.forEach((item) => {
            item.total = +item.qty * +item.cost;
            data.totalPrice += item.total;
        });
        data.rest = data.paid - data.totalPrice;

        this.setState({ data });
    };
    handleAddRow = () => {
        const data = _.cloneDeep(this.state.data);
        data.rows.push({
            drug: "",
            qty: "",
            exp: "",
            cost: 0,
            total: 0,
        });
        this.setState({ data });
    };
    handleRemoveRow = (index) => {
        const data = _.cloneDeep(this.state.data);
        data.rows.splice(index, 1);
        this.setState({ data });
    };

    doSubmit = async () => {
        const payload = {};
        payload.supplier = this.state.data.supplier;
        payload.date = this.state.data.date;
        payload.paid = this.state.data.paid;
        payload.items = [];
        this.state.data.rows.forEach(({ drug, cost, exp, mfd, qty }) => {
            payload.items.push({ drug, cost, exp, mfd, qty });
        });
        try {
            const response = await addPurchase(payload);
            this.handleApiResponse(response);
        } catch (ex) {
            const response = ex.response;
            if (response) {
                console.log(response);
                this.handleApiResponse(response);
            }
        }
    };
    handleDiscard = () => {
        let data = _.cloneDeep(this.state.data);
        data.supplier = "";
        data.date = new Date().toISOString().slice(0, 10);
        data.paid = 0;
        data.rest = 0;
        data.totalPrice = 0;
        data.rows = [
            {
                drug: "",
                cost: 0,
                mfd: "",
                exp: "",
                qty: "",
                total: 0,
                costOptions: [],
                expOptions: [],
            },
        ];
        this.setState({ data });
    };

    render() {
        const { t } = this.props;
        return (
            <div className="main-container mt-24 md:m-10 p-12 md:p-10 rounded-3xl drop-shadow-2xl dark:[#484B52] bg-white dark:text-gray-200 dark:bg-secondary-dark-bg overflow-auto">
                <Header title={t("purchase.purchaseAdd")} />
                {/* Invoice Header */}
                {Object.keys(this.state.message).length !== 0 && (
                    <Alert
                        message={this.state.message.body}
                        type={this.state.message.type}
                    >
                        {this.state.errors && (
                            <ul className="list-disc ml-10">
                                {Object.values(this.state.errors).map(
                                    (e, i) => (
                                        <li key={i}>{e}</li>
                                    )
                                )}
                            </ul>
                        )}
                    </Alert>
                )}
                <div className="flex flex-wrap align-stretch justify-start gap-10 mb-4 dark:text-gray-200">
                    {this.renderSelect({
                        label: t("purchase.supplier"),
                        name: "supplier",
                        data: this.state.data.supplierOptions,
                        placeholder: t("placeHolders.selectSupplier"),
                        className: "mt-4",
                    })}
                    {this.renderInput({
                        label: t("purchase.date"),
                        name: "date",
                        type: "date",
                        error: null,
                        className:
                            "block bg-white dark:bg-white-800 w-full md:w-4/1 sm:mt-2 rounded-md px-4 py-2 border border-gray-300 dark:border-gray-100  focus:outline-none focus:ring-2 focus:ring-blue-500  dark:focus:ring-blue-400   text-gray-800 dark:text-gray-600 placeholder-gray-400 dark:placeholder-gray-500",
                    })}
                </div>
                <div className="overflow-visiable">
                    <table className="table-auto w-full">
                        <thead className="upper-hr">
                            <tr>
                                {/* <th className="w-28 px-4 py-2">Drug</th>
                                <th className="w-20 px-4 py-2">Mfd</th>
                                <th className="w-20 px-4 py-2">Exp</th>
                                <th className="w-16 px-4 py-2">U.Cost</th>
                                <th className="w-16 px-4 py-2">Quantity</th>
                                <th className="w-16 px-4 py-2">Total</th>
                                <th className="w-4 px-4 py-2">Action</th> */}

                                <th className="w-60 px-4 py-2">
                                    {t("purchase.drug")}
                                </th>
                                <th className="w-60 px-4 py-2">
                                    {t("purchase.mfd")}
                                </th>
                                <th className="w-60 px-4 py-2">
                                    {t("purchase.exp")}
                                </th>
                                <th className="px-12 py-2">
                                    {t("purchase.unitCost")}
                                </th>
                                <th className="w-28 px-4 py-2">
                                    {t("purchase.qty")}
                                </th>
                                <th className="px-14 py-2">
                                    {t("purchase.total")}
                                </th>
                                <th className="px-4 py-2">
                                    {t("purchase.action")}
                                </th>
                            </tr>
                        </thead>
                        <tbody className="upper-hr ">
                            {this.state.data.rows.map((row, index) => (
                                <tr key={index}>
                                    <td className="border px-2 ">
                                        {this.renderSelect({
                                            error: null,
                                            name: `rows.${index}.drug`,
                                            data: this.state.data.drugOptions,
                                            placeholder: t(
                                                "placeHolders.selectDrug"
                                            ),
                                            className: "w-60",
                                        })}
                                    </td>
                                    <td className="border px-2 ">
                                        {this.renderInput({
                                            error: null,
                                            name: `rows.${index}.mfd`,
                                            type: "date",
                                        })}
                                    </td>
                                    <td className="border px-2 ">
                                        {this.renderInput({
                                            error: null,
                                            name: `rows.${index}.exp`,
                                            type: "date",
                                        })}
                                    </td>
                                    <td className="border px-2 ">
                                        {this.renderInput({
                                            error: null,
                                            name: `rows.${index}.cost`,
                                            type: "number",
                                            onChange: (target) =>
                                                this.checkPurchaseData(),
                                        })}
                                    </td>
                                    <td className="border px-2 ">
                                        {this.renderInput({
                                            name: `rows.${index}.qty`,
                                            error: null,
                                            type: "number",

                                            onChange: (target) =>
                                                this.checkPurchaseData(),
                                        })}
                                    </td>

                                    <td className="border px-2 ">
                                        {this.renderInput({
                                            name: `rows.${index}.total`,
                                            error: null,
                                            type: "number",
                                        })}
                                    </td>
                                    <td className="flex items-center justify-center">
                                        {index ===
                                        this.state.data.rows.length - 1 ? (
                                            <span className="w-full m-2">
                                                <IoMdAddCircle
                                                    className="w-full text-4xl cursor-pointer"
                                                    style={{
                                                        color: this.context
                                                            .currentColor,
                                                    }}
                                                    onClick={this.handleAddRow}
                                                />
                                            </span>
                                        ) : (
                                            <span className="w-full m-2">
                                                <IoMdRemoveCircle
                                                    className="w-full text-4xl cursor-pointer"
                                                    style={{
                                                        color: this.context
                                                            .currentColor,
                                                    }}
                                                    onClick={() =>
                                                        this.handleRemoveRow(
                                                            index
                                                        )
                                                    }
                                                />
                                            </span>
                                        )}
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
                <hr className="lower-hr mt-1" />
                {/* Invoice Footer */}
                <div className="flex flex-wrap items-center my-4 dark:text-gray-200">
                    <div className="w-full md:w-1/3">
                        {this.renderInput({
                            label: t("purchase.paid"),
                            name: "paid",
                            type: "number",
                            error: null,
                            onChange: (target) => this.checkPurchaseData(),
                        })}
                    </div>

                    <div className={this.className}>
                        {this.renderInput({
                            label: t("purchase.rest"),
                            name: "rest",
                            type: "number",
                            error: null,
                        })}
                    </div>
                    <div className={this.className}>
                        {this.renderInput({
                            label: t("purchase.total"),
                            name: "totalPrice",
                            error: null,
                            type: "number",
                        })}
                    </div>

                    <div className="w-full md:w-1/1  md:pl-4 mt-8 flex items-center justify-center gap-10">
                        {this.renderButton({
                            label: t("purchase.save"),
                            className:
                                "px-12 py-2 text-white bg-green-600 rounded-md hover:bg-green-500",
                            type: "submit",

                            events: { onClick: this.handleSubmit },
                        })}
                        {this.renderButton({
                            label: t("purchase.discard"),
                            className:
                                "px-8 py-2 text-white bg-red-600 rounded-md hover:bg-red-500",
                            events: { onClick: this.handleDiscard },
                        })}
                    </div>
                </div>
            </div>
        );
    }
}
AddPurchase.contextType = UIContext;
export default withTranslation()(AddPurchase);
