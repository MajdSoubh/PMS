import React from "react";
import { Header } from "../../views/common";
import _, { conforms } from "lodash";
import "../../assets/styles/addInvoice.css";
import { IoMdAddCircle, IoMdRemoveCircle } from "react-icons/io";
/* import { useUIContext } from "../../context/UIContext";
 */
import { UIContext } from "../../context/UIContext";
import Form from "../common/Form";
import { getStock } from "../../services/stock";
import { getDrugs } from "../../services/drug";
import { getCustomers } from "../../services/customer";
import { addInvoice } from "../../services/invoice";
import Alert from "../common/Alert";
import { withTranslation } from "react-i18next";
import i18n from "../../i18n";

class AddInvoice extends Form {
    state = {
        message: {},
        data: {
            number: "",
            customer: "",
            date: new Date().toISOString().slice(0, 10),
            paid: 0,
            rest: 0,
            drugOptions: [],
            customerOptions: [],
            totalPrice: 0,
            totalDiscount: 0,
            totalNet: 0,
            totalProfit: 0,
            rows: [
                {
                    drug: "",
                    cost: 0,
                    exp: "",
                    avQty: "",
                    price: 0,
                    qty: "",
                    discount: 0,
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
    fetchCustomers = async () => {
        try {
            const { data: resData } = await getCustomers();
            const data = _.cloneDeep(this.state.data);
            const _customers = Array.from(resData.data, ({ id, name }) => ({
                value: id,
                label: name,
            }));
            data.customerOptions = _customers;
            this.setState({ data });
        } catch (ex) {
            console.log(ex);
        }
    };
    fetchDrugData = async ({ drug }, row) => {
        try {
            const { data: resData } = await getStock({ drug });
            const data = _.cloneDeep(this.state.data);
            const expirayDates = resData.data.map((item) => ({
                label: item.exp,
                value: item.exp,
            }));
            data.rows[row].expOptions = expirayDates;
            data.rows[row].exp = expirayDates[0].value;
            data.rows[row].price = resData.data[0].drug.price;
            this.setState({ data });
        } catch (e) {
            console.log(e);
        }
    };
    fetchExpireData = async ({ drug, exp }, row) => {
        try {
            const { data: resData } = await getStock({ drug, exp });
            const data = _.cloneDeep(this.state.data);

            const costs = resData.data.map((item) => ({
                label: item.cost,
                value: item.cost,
            }));
            data.rows[row].costOptions = costs;
            data.rows[row].cost = costs[0].value;
            data.rows[row].avQty = resData.data[0].qty;
            console.log(resData.data);
            this.setState({ data });
        } catch (e) {
            console.log(e);
        }
    };
    fetchCostData = async ({ drug, exp, cost }, row) => {
        try {
            const { data: resData } = await getStock({ drug, exp, cost });
            const data = _.cloneDeep(this.state.data);

            data.rows[row].avQty = resData.data[0].qty;
            this.setState({ data });
        } catch (e) {
            console.log(e);
        }
    };
    componentDidMount() {
        this.fetchDrugs();
        this.fetchCustomers();
    }
    componentDidUpdate(prevProps, prevState) {
        const c = Math.min(
            this.state.data.rows.length,
            prevState.data.rows.length
        );
        for (let i = 0; i < c; i++) {
            const currentState = this.state.data.rows[i];
            const prevStateData = prevState.data.rows[i];
            if (currentState.drug != prevStateData.drug) {
                this.fetchDrugData(currentState, i);
            } else if (currentState.exp != prevStateData.exp) {
                this.fetchExpireData(currentState, i);
            } else if (currentState.cost != prevStateData.cost) {
                this.fetchCostData(currentState, i);
            }
        }
    }
    checkInvoiceData = () => {
        const data = _.cloneDeep(this.state.data);
        data.totalPrice = 0;
        data.totalDiscount = 0;
        data.totalProfit = 0;

        data.rows.forEach((item) => {
            item.total = +item.qty * (+item.price - +item.discount);
            data.totalPrice += +item.qty * +item.price;
            data.totalDiscount += +item.discount;
            data.totalProfit +=
                //        +data.totalPrice - +data.totalDiscount - item.qty * item.cost;
                +item.total - item.qty * item.cost;
        });
        data.totalNet = data.totalPrice - data.totalDiscount;
        data.rest = data.paid - data.totalNet;

        this.setState({ data });
    };
    handleAddRow = () => {
        const data = _.cloneDeep(this.state.data);
        data.rows.push({
            drug: "",
            qty: "",
            avQty: "",
            exp: "",
            cost: 0,
            price: 0,
            discount: 0,
            total: "",
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
        payload.customer = this.state.data.customer;
        payload.date = this.state.data.date;
        payload.paid = this.state.data.paid;
        payload.items = [];
        this.state.data.rows.forEach(
            ({ drug, cost, exp, price, qty, discount }) => {
                payload.items.push({ drug, cost, exp, price, qty, discount });
            }
        );

        const response = await addInvoice(payload);
        this.handleApiResponse(response);
    };
    handleDiscard = () => {
        let data = _.cloneDeep(this.state.data);
        data.customer = "";
        data.date = new Date().toISOString().slice(0, 10);
        data.paid = 0;
        data.rest = 0;
        data.totalPrice = 0;
        data.totalDiscount = 0;
        data.totalNet = 0;
        data.totalProfit = 0;
        data.rows = [
            {
                drug: "",
                cost: 0,
                exp: "",
                avQty: "",
                price: 0,
                qty: "",
                discount: 0,
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
                <Header title={t("invoice.addInvoice")} />
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
                <div className="flex flex-wrap align-stretch justify-start gap-8 mb-4 dark:text-gray-200">
                    {this.renderSelect({
                        label: t("invoice.customer"),
                        name: "customer",
                        data: this.state.data.customerOptions,
                        placeholder: t("placeHolders.selectCustomer"),
                        className: "mt-4 ",
                    })}
                    {this.renderInput({
                        label: t("invoice.date"),
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
                                <th className="w-20 px-4 py-2">Expiry</th>
                                <th className="w-20 px-4 py-2">Cost</th>
                                <th className="w-16 px-4 py-2">Av.Qty</th>
                                <th className="w-16 px-4 py-2">Quantity</th>
                                <th className="w-16 px-4 py-2">U.Price</th>
                                <th className="w-16 px-4 py-2">Discount</th>
                                <th className="w-16 px-4 py-2">Total</th>
                                <th className="w-4 px-4 py-2">Action</th> */}

                                <th className="w-60 px-4 py-2">
                                    {" "}
                                    {t("invoice.drug")}
                                </th>
                                <th className="w-60 px-4 py-2">
                                    {" "}
                                    {t("invoice.expiry")}
                                </th>
                                <th className="w-36 px-12 py-2">
                                    {" "}
                                    {t("invoice.cost")}
                                </th>
                                <th className="px-6 py-2">
                                    {" "}
                                    {t("invoice.availQty")}
                                </th>
                                <th className="w-28 px-4 py-2">
                                    {" "}
                                    {t("invoice.quantity")}
                                </th>
                                <th className="w-16 px-12 py-2">
                                    {" "}
                                    {t("invoice.unitPrice")}
                                </th>
                                <th className="w-36 px-8 py-2">
                                    {" "}
                                    {t("invoice.discount")}
                                </th>
                                <th className="px-12 py-2">
                                    {" "}
                                    {t("invoice.total")}
                                </th>
                                <th className="px-4 py-2">
                                    {" "}
                                    {t("invoice.action")}
                                </th>
                            </tr>
                        </thead>
                        <tbody className="upper-hr">
                            {this.state.data.rows.map((row, index) => (
                                <tr key={index}>
                                    <td className="border px-1">
                                        {this.renderSelect({
                                            error: null,
                                            name: `rows.${index}.drug`,
                                            data: this.state.data.drugOptions,
                                            placeholder: t(
                                                "placeHolders.selectDrug"
                                            ),
                                            className: "w-60 ",
                                        })}
                                    </td>
                                    <td className="border px-1 ">
                                        {this.renderSelect({
                                            error: null,
                                            name: `rows.${index}.exp`,
                                            data: this.state.data.rows[index]
                                                .expOptions,
                                            placeholder: t(
                                                "placeHolders.selectDate"
                                            ),
                                            className: "w-60",
                                        })}
                                    </td>
                                    <td className="border px-1 ">
                                        {this.renderSelect({
                                            error: null,
                                            name: `rows.${index}.cost`,
                                            data: this.state.data.rows[index]
                                                .costOptions,
                                            placeholder: t(
                                                "placeHolders.selectCost"
                                            ),
                                            className: "w-36",
                                        })}
                                    </td>
                                    <td className="border px-1 ">
                                        {this.renderInput({
                                            name: `rows.${index}.avQty`,
                                            type: "number",
                                            error: null,
                                        })}
                                    </td>
                                    <td className="border px-1 ">
                                        {this.renderInput({
                                            name: `rows.${index}.qty`,
                                            error: null,
                                            type: "number",

                                            onChange: (target) =>
                                                this.checkInvoiceData(),
                                        })}
                                    </td>

                                    <td className="border px-1 ">
                                        {this.renderInput({
                                            name: `rows.${index}.price`,
                                            error: null,
                                            type: "number",
                                            onChange: (target) =>
                                                this.checkInvoiceData(),
                                        })}
                                    </td>
                                    <td className="border px-1 ">
                                        {this.renderInput({
                                            name: `rows.${index}.discount`,
                                            error: null,
                                            type: "number",

                                            onChange: (target) =>
                                                this.checkInvoiceData(),
                                        })}
                                    </td>

                                    <td className="border px-1">
                                        {this.renderInput({
                                            name: `rows.${index}.total`,
                                            error: null,
                                            type: "number",
                                        })}
                                    </td>
                                    <td className="flex border px-1 py-3 items-center justify-center">
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
                            label: t("invoice.totalNet"),
                            name: "totalNet",
                            error: null,
                            type: "number",
                        })}
                    </div>

                    <div className={this.className}>
                        {this.renderInput({
                            label: t("invoice.totalDiscount"),
                            name: "totalDiscount",
                            error: null,
                            type: "number",
                        })}
                    </div>
                    <div className={this.className}>
                        {this.renderInput({
                            label: t("invoice.totalPrice"),
                            name: "totalPrice",
                            error: null,
                            type: "number",
                        })}
                    </div>

                    <div className="w-full md:w-1/3">
                        {this.renderInput({
                            label: t("invoice.paid"),
                            name: "paid",
                            type: "number",
                            error: null,
                            onChange: (target) => this.checkInvoiceData(),
                        })}
                    </div>

                    <div className={this.className}>
                        {this.renderInput({
                            label: t("invoice.rest"),
                            name: "rest",
                            type: "number",
                            error: null,
                        })}
                    </div>
                    <div className={this.className}>
                        {this.renderInput({
                            label: t("invoice.totalProfit"),
                            name: "totalProfit",
                            error: null,
                            type: "number",
                        })}
                    </div>

                    <div className="w-full md:w-1/1  md:pl-4 mt-8 flex items-center justify-center gap-10">
                        {this.renderButton({
                            label: t("invoice.save"),
                            className:
                                "px-12 py-2 text-white bg-green-600 rounded-md hover:bg-green-500",
                            type: "submit",

                            events: { onClick: this.handleSubmit },
                        })}
                        {this.renderButton({
                            label: t("invoice.discard"),
                            className:
                                "px-8 py-2 text-white bg-red-600 rounded-md hover:bg-red-500 ",
                            events: { onClick: this.handleDiscard },
                        })}
                    </div>
                </div>
            </div>
        );
    }
}
AddInvoice.contextType = UIContext;

export default withTranslation()(AddInvoice);
