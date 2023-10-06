import { withTranslation } from "react-i18next";
import { FiEdit } from "react-icons/fi";
import React from "react";
import Alert from "../Alert";
import Form from "../Form";
import { editDrug } from "../../../services/drug";
class EditDrugModal extends Form {
    state = {
        show: false,
        data: { name: "", generic: "", description: "", price: "" },
        errors: {},
        message: {},
    };
    toggelDisplay = () => {
        this.setState({ show: !this.state.show });
    };

    componentDidMount() {
        const data = {};
        for (let [k, v] of Object.entries(this.props.data)) {
            data[k] = v || "";
        }
        this.setState({ data });
    }
    doSubmit = async () => {
        try {
            const response = await editDrug(this.state.data);
            const { data: resData } = response;
            this.handleApiResponse(response);
            this.toggelDisplay();
            this.props.onSuccess(resData.message);
        } catch (ex) {
            const response = ex.response;
            if (response) {
                this.handleApiResponse(response);
            }
        }
    };
    render() {
        const { t } = this.props;
        return (
            <>
                <button
                    style={{
                        fontSize: "1.2rem",
                    }}
                    className="hover:bg-blue-500 bg-blue-600 text-white active:bg-yellow-600 font-bold uppercase text-xs px-4 py-2 rounded shadow hover:shadow-md outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150"
                    onClick={this.toggelDisplay}
                >
                    <FiEdit />
                </button>
                {this.state.show ? (
                    <>
                        <div
                            style={{ zIndex: "400" }}
                            className="justify-center items-center flex overflow-x-hidden overflow-y-auto fixed inset-0 z-400 outline-none focus:outline-none"
                        >
                            <div className="relative w-auto my-6 mx-auto max-w-sm">
                                {/*content*/}
                                <div className="dark:bg-secondary-dark-bg border-0 rounded-lg shadow-lg relative flex flex-col w-96 bg-white outline-none focus:outline-none">
                                    {/* start */}
                                    <div className="mt-3 mx-3 basis-0">
                                        {Object.keys(this.state.message)
                                            .length !== 0 && (
                                            <Alert
                                                message={
                                                    this.state.message.body
                                                }
                                                type={this.state.message.type}
                                            />
                                        )}
                                    </div>
                                    <form onSubmit={this.handleSubmit}>
                                        <div className="block  rounded-lg bg-white p-5  dark:bg-secondary-dark-bg">
                                            {this.renderInput({
                                                label: t("drug.name"),
                                                name: "name",
                                            })}
                                            {this.renderInput({
                                                label: t("drug.generic"),
                                                name: "generic",
                                            })}
                                            {this.renderTextarea({
                                                label: t("drug.description"),
                                                name: "description",
                                            })}
                                            {this.renderInput({
                                                label: t("drug.price"),
                                                name: "price",
                                            })}

                                            <div className="flex justify-end gap-3 mt-4">
                                                <button
                                                    onClick={() =>
                                                        this.toggelDisplay()
                                                    }
                                                    className="px-4 py-2 text-white bg-red-600 rounded-md hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50"
                                                >
                                                    {t("drug.close")}
                                                </button>
                                                {this.renderButton({
                                                    label: t("drug.save"),
                                                    className:
                                                        "bg-green-600 hover:bg-green-500 text-white font-bold py-2 px-4 rounded-md",
                                                    type: "submit",
                                                })}
                                            </div>
                                        </div>
                                    </form>
                                    {/* end */}
                                </div>
                            </div>
                        </div>

                        {/* background */}
                        <div
                            style={{ zIndex: "300" }}
                            className="opacity-25 fixed inset-0  bg-black"
                        ></div>
                    </>
                ) : null}
            </>
        );
    }
}

export default withTranslation()(EditDrugModal);
