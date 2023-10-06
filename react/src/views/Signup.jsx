import React, { Component } from "react";
import { Link } from "react-router-dom";
import { AuthContext } from "../context/AuthContext";
import Form from "./common/Form";
import { withTranslation } from "react-i18next";
import "../assets/styles/entry.css";
import Alert from "./common/Alert";

class Signup extends Form {
    state = {
        data: {
            firstName: "",
            lastName: "",
            pharmacyName: "",
            email: "",
            password: "",
            password_confirmation: "",
        },
        errors: {},
        message: "",
    };
    doSubmit = async () => {
        const { signup } = this.context;

        const response = await signup(this.state.data);
        const { data: resData } = response;
        this.handleApiResponse(response);
    };

    render() {
        const { t, i18n } = this.props;
        return (
            <section className="entry-form">
                <div className="container  ">
                    <div className="flex  justify-center items-center ">
                        <div className="shadow-lg px-8 rounded-lg w-11/12 md:w-5/6 lg:w-4/5 xl:w-3/4 2xl:w-96">
                            <div className="bg-white   pt-6  mb-4">
                                <h1 className="text-2xl font-bold mb-6">
                                    {t("auth.signupTitle")}
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

                                    <div className="flex flex-col md:flex-row md:justify-between md:gap-4 mb-6">
                                        {this.renderInput({
                                            label: t("auth.firstName"),
                                            name: "firstName",
                                        })}
                                        {this.renderInput({
                                            label: t("auth.lastName"),
                                            name: "lastName",
                                        })}
                                    </div>
                                    {this.renderInput({
                                        label: t("auth.pharmacyName"),
                                        name: "pharmacyName",
                                    })}
                                    {this.renderInput({
                                        label: t("auth.password"),
                                        name: "password",
                                        type: "password",
                                    })}
                                    {this.renderInput({
                                        label: t("auth.passwordConfirm"),
                                        name: "password_confirmation",
                                        type: "password",
                                    })}

                                    <p className="text-gray-500 text-sm mb-6">
                                        {t("auth.policy")}
                                    </p>

                                    {this.renderButton({
                                        label: t("auth.signupButton"),
                                        className:
                                            "w-full bg-blue-500 text-white font-bold py-2 px-4 rounded-md mb-6",
                                        type: "submit",
                                    })}
                                </form>
                            </div>
                            <div className="bg-white   ">
                                <div className="text-center">
                                    {t("auth.loginPrompt")}{" "}
                                    <Link
                                        to="/login"
                                        className="text-blue-500 font-bold"
                                    >
                                        {t("auth.loginLink")}
                                    </Link>
                                    <div
                                        className="flex items-center justify-center gap-2 mx-auto my-6"
                                        style={{ maxWidth: "max-content" }}
                                    >
                                        {t("lang")}
                                        <select
                                            className="form-select block w-24 py-2 px-3 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                            onChange={({ target }) => {
                                                i18n.changeLanguage(
                                                    target.value
                                                );
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
                </div>
            </section>
        );
    }
}
Signup.contextType = AuthContext;
export default withTranslation()(Signup);
