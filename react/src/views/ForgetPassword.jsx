import React from "react";
import { Link } from "react-router-dom";
import { AuthContext } from "../context/AuthContext";
import Form from "./common/Form";
import { withTranslation } from "react-i18next";
import Alert from "./common/Alert";

class ForgetPassword extends Form {
    state = { data: { email: "" }, errors: {}, message: "" };
    doSubmit = async () => {
        const { forgetPassword } = this.context;

        const response = await forgetPassword(this.state.data);
        this.handleApiResponse(response);
    };
    render() {
        const { t, i18n } = this.props;
        return (
            <section className="entry-form">
                <div className="container h-screen mx-auto">
                    <div className="flex items-center justify-center h-full">
                        <div className="w-full  shadow-lg max-w-md">
                            <div className="bg-white rounded-md px-8 py-6">
                                <h1 className="text-lg font-bold mb-4">
                                    {t("auth.forgetPasswordTitle")}
                                </h1>
                                {Object.keys(this.state.message).length !==
                                    0 && (
                                    <Alert
                                        message={this.state.message.body}
                                        type={this.state.message.type}
                                    />
                                )}
                                <form onSubmit={this.handleSubmit}>
                                    {this.renderInput({
                                        label: t("auth.email"),
                                        name: "email",
                                    })}
                                    {this.renderButton({
                                        label: t("auth.forgetPasswordButton"),
                                        className:
                                            "bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md float-right",
                                        type: "submit",
                                    })}
                                </form>
                            </div>
                            <div className="bg-white ">
                                <div className="text-center">
                                    {t("auth.rememberPrompt")}{" "}
                                    <Link
                                        to="/login"
                                        className="text-blue-500 font-bold"
                                    >
                                        {t("auth.loginLink")}
                                    </Link>
                                </div>
                                <div
                                    className="flex items-center justify-center gap-2 mx-auto mt-6"
                                    style={{ maxWidth: "max-content" }}
                                >
                                    {t("lang")}
                                    <select
                                        className="form-select block w-24 py-2 px-3 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                        onChange={({ target }) => {
                                            i18n.changeLanguage(target.value);
                                            document.body.dir = i18n.dir();
                                        }}
                                        value={i18n.language}
                                    >
                                        <option value="en">EN</option>
                                        <option value="ar">AR</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        );
    }
}

ForgetPassword.contextType = AuthContext;
export default withTranslation()(ForgetPassword);
