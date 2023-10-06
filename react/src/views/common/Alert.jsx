const Alert = ({ message, type, children }) => {
    const color = () => {
        switch (type) {
            case "success":
                return "mb-3 inline-flex w-full items-center rounded-lg bg-green-100 px-6 py-5 text-base text-green-700";
                break;
            case "danger":
                return "mb-3 inline-flex w-full items-center rounded-lg bg-red-100 px-6 py-5 text-base text-red-700";
                break;
            default:
                return "mb-3 inline-flex w-full items-center rounded-lg bg-blue-100 px-6 py-5 text-base ";
        }
    };
    return (
        <div className={color()} role="alert">
            <div>
                {message}
                {children}
            </div>
        </div>
    );
};
export default Alert;
