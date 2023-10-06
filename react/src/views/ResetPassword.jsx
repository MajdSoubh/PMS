import React from "react";
import Form from "./common/Form";
import { withRouter } from "../utils/withRouter";
import { AuthContext } from "../context/AuthContext";
import { withTranslation } from "react-i18next";
import Alert from "./common/Alert";
class ResetPassword extends Form {
    state = {
        data: {
            email: "",
            password: "",
            password_confirmation: "",
            logoutDevices: false,
        },
        errors: {},
        message: "",
    };
    doSubmit = async () => {
        const { resetPassword } = this.context;
        const payload = { ...this.state.data };
        payload["token"] = this.props.params.token;
        const response = await resetPassword(payload);
        this.handleApiResponse(response);
    };
    render() {
        const { t, i18n } = this.props;
        return (
            <section className="entry-form">
                <div className="container h-100">
                    <div className="row justify-content-sm-center h-100">
                        <div className="col-xxl-4 col-xl-5 col-lg-5 col-md-7 col-sm-9">
                            <div className="card shadow-lg">
                                <div className="card-body p-5">
                                    <h1 className="fs-4 card-title fw-bold mb-4">
                                        {t("auth.resetPasswordTitle")}
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
                                            label: t("auth.newPassword"),
                                            name: "password",
                                            type: "password",
                                        })}
                                        {this.renderInput({
                                            label: t("auth.passwordConfirm"),
                                            name: "password_confirmation",
                                            type: "password",
                                        })}
                                        <div className="d-flex gap-1 align-items-center justify-content-between">
                                            {this.renderInput({
                                                label: t(
                                                    "auth.logoutAllDevices"
                                                ),
                                                name: "logoutDevices",
                                                type: "checkbox",
                                            })}

                                            {this.renderButton({
                                                label: t(
                                                    "auth.resetPasswordButton"
                                                ),
                                                className:
                                                    "bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md float-right",
                                                type: "submit",
                                            })}
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        );
    }
}
ResetPassword.contextType = AuthContext;
export default withTranslation()(withRouter(ResetPassword));
