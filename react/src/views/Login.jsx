import React from "react";
import "../assets/styles/entry.css";
import { Link } from "react-router-dom";
import Form from "./common/Form";
import { AuthContext } from "../context/AuthContext";
import { withTranslation } from "react-i18next";
import Alert from "./common/Alert";
class Login extends Form {
    state = {
        data: { email: "", password: "", remember: false },
        errors: {},
        message: "",
    };
    doSubmit = async () => {
        const { login } = this.context;
        const response = await login(this.state.data);
        this.handleApiResponse(response);
    };
    render() {
        const { t, i18n } = this.props;
        return (
            <section className="entry-form">
                <div className="container  h-screen mx-auto">
                    <div className=" flex  items-center justify-center h-full">
                        <div className="w-full  shadow-lg max-w-md mb-5">
                            <div className="bg-white  rounded-md px-8 py-6">
                                <h1 className="text-lg font-bold mb-4">
                                    {t("auth.loginTitle")}
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
                                    {this.renderInput({
                                        label: t("auth.password"),
                                        name: "password",
                                        type: "password",
                                    })}
                                    <div className="flex items-center justify-between mt-4">
                                        {this.renderInput({
                                            label: t("auth.remember"),
                                            name: "remember",
                                            type: "checkbox",
                                        })}
                                        {this.renderButton({
                                            label: t("auth.loginButton"),
                                            className:
                                                "bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md",
                                            type: "submit",
                                        })}
                                    </div>
                                </form>
                            </div>
                            <div className="bg-white ">
                                <div className="text-center">
                                    {t("auth.signupPrompt")}{" "}
                                    <Link
                                        to="/signup"
                                        className="text-blue-500 font-bold"
                                    >
                                        {t("auth.signupLink")}
                                    </Link>
                                </div>
                                <div className="text-center mt-2">
                                    {t("auth.forgetPasswordPrompt")}{" "}
                                    <Link
                                        to="/forget-password"
                                        className="text-blue-500 font-bold"
                                    >
                                        {t("auth.forgetPasswordLink")}
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
Login.contextType = AuthContext;
export default withTranslation()(Login);
