import React from "react";
import { Page, Text, View, Document, StyleSheet } from "@react-pdf/renderer";
import _ from "lodash";

export const PDF = ({ data, columns, user, name }) => {
    const styles = StyleSheet.create({
        page: {
            flexDirection: "column",
            padding: 20,
        },
        heading: {
            fontSize: 14,
            fontWeight: 900,
            marginBottom: 10,
            display: "inline-block",
        },
        subHeading: {
            fontSize: 13,
            fontWeight: 900,
            marginBottom: 10,
            display: "inline-block",
            color: "#464747",
        },
        table: {
            display: "table",
            width: "100%",

            marginTop: 28,
        },
        tableRow: {
            margin: "auto",
            flexDirection: "row",
        },
        tableColHeader: {
            width: `${100 / (columns.length - 1)}%`,
            borderStyle: "solid",
            borderBottomWidth: 1,
            borderRightWidth: 1,
            borderTopWidth: 0,
            borderLeftWidth: 0,
            padding: 5,
            backgroundColor: "#1691f5",
        },
        tableCol: {
            width: `${100 / (columns.length - 1)}%`,
            borderStyle: "solid",
            borderBottomWidth: 1,
            borderRightWidth: 1,
            borderTopWidth: 0,
            borderLeftWidth: 0,
            padding: 5,
        },
        tableCellHeader: {
            margin: "auto",
            fontSize: 14,
            fontWeight: 900,
            color: "white",
        },
        tableCell: {
            margin: "auto",
            fontSize: 10,
            fontWeight: 900,
        },
        footer: {
            marginTop: 30,
            fontSize: 14,
            fontWeight: 900,
            marginBottom: 10,
            display: "inline-block",
        },
    });
    const totalPrice = data.reduce((acc, curr) => acc + +curr.total, 0);
    const totalDiscount = data.reduce(
        (acc, curr) => acc + +curr.totalDiscount,
        0
    );
    const totalProfit = data.reduce((acc, curr) => acc + +curr.totalProfit, 0);
    const totalNet = data.reduce((acc, curr) => acc + +curr.totalNet, 0);
    const options = {
        year: "numeric",
        month: "2-digit",
        day: "2-digit",
        hour: "2-digit",
        minute: "2-digit",
        second: "2-digit",
    };

    return (
        <Document>
            <Page style={styles.page}>
                <View>
                    <Text style={styles.heading} className="text-lg">
                        Pharmacy Name:
                        <Text style={styles.subHeading}>
                            {" "}
                            {user.pharmacy.name}
                        </Text>
                    </Text>
                    <Text style={styles.heading} className="text-lg">
                        User Name:{user.name}
                        <Text style={styles.subHeading}>
                            {user.firstName + " " + user.lastName}
                        </Text>
                    </Text>
                    <Text style={styles.heading}>
                        Generated at:{" "}
                        <Text style={styles.subHeading}>
                            {new Date().toLocaleDateString(undefined, options)}
                        </Text>
                    </Text>
                    <Text style={styles.heading}>
                        Type: <Text style={styles.subHeading}> {name}</Text>
                    </Text>
                </View>
                <View style={styles.table}>
                    <View style={styles.tableRow}>
                        {columns.map((col) => {
                            if (col.key == "action") return null;
                            return (
                                <View
                                    key={col.key}
                                    style={styles.tableColHeader}
                                >
                                    <Text style={styles.tableCellHeader}>
                                        {col.name}
                                    </Text>
                                </View>
                            );
                        })}
                    </View>

                    {data.map((row, ind) => {
                        return (
                            <View key={ind} style={styles.tableRow}>
                                {columns.map((col, ind) => {
                                    if (col.key == "action") return null;
                                    return (
                                        <View key={ind} style={styles.tableCol}>
                                            <Text style={styles.tableCell}>
                                                {_.get(row, col.key)
                                                    ? _.get(row, col.key)
                                                    : "--"}
                                            </Text>
                                        </View>
                                    );
                                })}
                            </View>
                        );
                    })}
                </View>
                <View style={styles.footer}>
                    <Text style={styles.heading}>
                        Total Price:{" "}
                        <Text style={styles.subHeading}>{totalPrice} SP</Text>
                    </Text>
                    <Text style={styles.heading}>
                        Total Discount:{" "}
                        <Text style={styles.subHeading}>
                            {totalDiscount} SP
                        </Text>
                    </Text>
                    <Text style={styles.heading}>
                        Total Net:{" "}
                        <Text style={styles.subHeading}>{totalNet} SP</Text>
                    </Text>{" "}
                    <Text style={styles.heading}>
                        Total Profit:{" "}
                        <Text style={styles.subHeading}>{totalProfit} SP</Text>
                    </Text>
                </View>
            </Page>
        </Document>
    );
};

export default PDF;
