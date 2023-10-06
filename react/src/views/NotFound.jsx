import React from "react";
import { withTranslation } from "react-i18next";

const NotFound = () => {
    const styles = {
        div: {
            fontFamily: " Arial, sans-serif",
            backgroundColor: "#f0f0f0",
            color: "#333",
            padding: "50px",
            height: "100vh",
        },

        h1: {
            fontSize: "48px",
            marginBottom: "20px",
        },

        p: {
            fontSize: "24px",
            marginBottom: "40px",
        },

        a: {
            color: "#333",
            textDecoration: "none",
            fontWeight: "bold",
        },
    };
    return (
        <div style={styles.div} className="text-center">
            <h1 style={styles.h1}>404</h1>
            <p style={styles.p}>Page Not Found</p>
            <p style={styles.p}>
                <a href="/">Go back to home page</a>
            </p>
        </div>
    );
};

export default withTranslation()(NotFound);
