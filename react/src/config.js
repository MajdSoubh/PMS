const baseEndpoint = "http://127.0.0.1:8000";
const apiEndpoints = {
    api: `${baseEndpoint}/api`,

    storage: `${baseEndpoint}/storage`,

    signup: `${baseEndpoint}/api/owner`,
    signout: `${baseEndpoint}/api/logout`,
    login: `${baseEndpoint}/api/login`,
    forgetPassword: `${baseEndpoint}/api/forget-password`,
    resetPassword: `${baseEndpoint}/api/reset-password`,

    customer: `${baseEndpoint}/api/customer`,
    supplier: `${baseEndpoint}/api/supplier`,
    drug: `${baseEndpoint}/api/drug`,
    stock: `${baseEndpoint}/api/stock`,
    invoice: `${baseEndpoint}/api/invoice`,
    purchase: `${baseEndpoint}/api/purchase`,
    voucher: `${baseEndpoint}/api/voucher`,
    employee: `${baseEndpoint}/api/employee`,
    role: `${baseEndpoint}/api/role`,

    dashboardData: `${baseEndpoint}/api/analytical/dashboard`,
};
export { apiEndpoints, baseEndpoint };
