import { useTranslation, withTranslation } from "react-i18next";
import http from "../services/httpClient";
import { apiEndpoints } from "../config";
import { Pie, LineChart } from "./common";
import { useUIContext } from "../context/UIContext";
import { useEffect, useState } from "react";
import {
    dashboardCards,
    mapDataToPieChart,
    mapDataToLineChart,
} from "../utils/UIUtils";
import { Button } from "./common/index";
import { can } from "../utils/UIUtils";
import { useAuthContext } from "../context/AuthContext";
import { Link, NavLink, Navigate } from "react-router-dom";
const Dashboard = () => {
    const { currentColor } = useUIContext();
    const [statistics, setStatisticsData] = useState({});
    const { user, permissions } = useAuthContext();
    const getDashboardData = async () => {
        try {
            const { data: resData } = await http.get(
                apiEndpoints.dashboardData
            );
            setStatisticsData(resData.data);
            console.log(user);
        } catch (ex) {}
    };
    useEffect(() => {
        getDashboardData();
    }, []);
    const { t } = useTranslation();
    return (
        <>
            <style>
                {`
            #pie-chart * {
                direction: ltr !important;
            }
            #pie-chart1 * {
                direction: ltr !important;
            }
            #line-chart * {
                direction: ltr !important;
            }
            // .chart-caption {
            //     direction: rtl !important;
            // }

            #EarningCard {
                background-image: url('welcome.svg') !important;
                background-repeat: no-repeat;
                background-size: cover;
                background-position: center;
                direction: ltr;
            }
            
          `}
            </style>
            <div className="mt-12">
                {/* Main Cards Container */}
                <div className="flex flex-wrap justify-center p-3">
                    {/* Earnings Card */}
                    {/* <div className="bg-white dark:text-gray-200 dark:bg-secondary-dark-bg h-44 rounded-xl w-full p-8 pt-9 m-5 bg-no-repeat bg-cover bg-center drop-shadow-lg"> */}
                    <div
                        id="EarningCard"
                        className="bg-white dark:text-gray-200 dark:bg-secondary-dark-bg h-44 rounded-xl w-full p-8 m-5 drop-shadow-lg"
                    >
                        <div className="flex justify-between items-center">
                            <div>
                                <p className="font-bold text-gray-400">
                                    {t("cards.earnings")}
                                </p>
                                <p className="text-2xl font-semibold">
                                    {statistics.earnings
                                        ? statistics.earnings
                                        : 0}
                                    SP
                                </p>
                            </div>
                        </div>
                        <div className="mt-6">
                            <NavLink to="/invoices">
                                <Button
                                    color="white"
                                    bgColor={currentColor}
                                    text={t("additional.moreDetails")}
                                    borderRadius="10px"
                                    size="sm"
                                    onClick={() => {}}
                                />
                            </NavLink>
                        </div>
                    </div>

                    {/* Other Cards Container */}
                    <div className="flex flex-wrap w-full p-3 m-3 justify-center gap-7 items-center drop-shadow-lg">
                        {dashboardCards.map((item) => {
                            if (
                                !item.permissions ||
                                can(user, item.permissions)
                            )
                                return (
                                    <div
                                        key={item.title}
                                        className="basis-48 bg-white dark:text-gray-200 dark:bg-secondary-dark-bg p-4 pt-6 rounded-2xl hover:drop-shadow-xl"
                                    >
                                        {/* Icons For each Card */}
                                        <button
                                            type="button"
                                            style={{
                                                color: item.iconColor,
                                                backgroundColor: item.iconBg,
                                            }}
                                            className="text-3xl opacity-0.9 rounded-full p-4 hover:drop-shadow-xl"
                                        >
                                            {item.icon}
                                        </button>
                                        {/* Card Details */}
                                        <p className="pl-2 mt-3">
                                            <span className="text-lg font-semibold">
                                                {statistics[item.key]
                                                    ? statistics[item.key]
                                                    : item.default}
                                            </span>
                                        </p>
                                        <p className="pl-2 text-sm text-gray-400 font-semibold">
                                            <span>{item.title}</span>
                                        </p>
                                    </div>
                                );
                        })}
                    </div>
                </div>

                <div className="-z-30 flex gap-20 m-4 flex-wrap justify-center p-3">
                    <div className="flex gap-20 m-4 flex-row md:w-full justify-center p-3">
                        {statistics.invoicesYearsFinancial &&
                            statistics.purchasesYearsFinancial &&
                            can(user, [
                                [
                                    "invoice-list",
                                    "invoice-create",
                                    "invoice-delete",
                                    "invoice-edit",
                                ],
                                [
                                    "purchase-list",
                                    "purchase-create",
                                    "purchase-delete",
                                    "purchase-edit",
                                ],
                                [
                                    "voucher-list",
                                    "voucher-create",
                                    "voucher-delete",
                                    "voucher-edit",
                                ],
                            ]) && (
                                <div className="bg-white overflow-auto dark:text-gray-200 dark:bg-secondary-dark-bg p-6 rounded-2xl w-96 md:w-760 drop-shadow-lg">
                                    <div className="flex justify-between items-center gap-2 mb-10">
                                        <p className="text-xl md:text-xl font-semibold">
                                            {t("charts.financial")}
                                        </p>

                                        <p>..</p>
                                    </div>
                                    <div className="p-0 md:w-full overflow-auto">
                                        <LineChart
                                            invoices={mapDataToLineChart(
                                                statistics.invoicesYearsFinancial
                                            )}
                                            purchases={mapDataToLineChart(
                                                statistics.purchasesYearsFinancial
                                            )}
                                        />
                                    </div>
                                </div>
                            )}
                    </div>
                    <div className="flex gap-20 m-4 flex-col sm:flex-row justify-center p-3">
                        {statistics.sellingDrugsCounter &&
                            can(user, [
                                [
                                    "drug-list",
                                    "drug-create",
                                    "drug-delete",
                                    "drug-edit",
                                ],
                                [
                                    "invoice-list",
                                    "invoice-create",
                                    "invoice-delete",
                                    "invoice-edit",
                                ],
                                [
                                    "purchase-list",
                                    "purchase-create",
                                    "purchase-delete",
                                    "purchase-edit",
                                ],
                            ]) && (
                                <div className="bg-white dark:text-gray-200 dark:bg-secondary-dark-bg p-6 md:w-400 rounded-2xl drop-shadow-lg">
                                    <p className="font-extrabold md:text-xl text-gray-400">
                                        {t("charts.bestDrugs")}
                                    </p>
                                    <div className="w-72 md:w-80">
                                        <Pie
                                            id="pie-chart1"
                                            data={mapDataToPieChart(
                                                {
                                                    sellingCounter: "y",
                                                    name: "x",
                                                },
                                                statistics.bestSellingDrugs,
                                                statistics.sellingDrugsCounter
                                            )}
                                            legendVisiblity
                                            height="full"
                                        />
                                    </div>
                                </div>
                            )}

                        {statistics.invoicesYearsProfitTotal &&
                            can(user, [
                                [
                                    "invoice-list",
                                    "invoice-create",
                                    "invoice-delete",
                                    "invoice-edit",
                                ],
                                [
                                    "purchase-list",
                                    "purchase-create",
                                    "purchase-delete",
                                    "purchase-edit",
                                ],
                                [
                                    "voucher-list",
                                    "voucher-create",
                                    "voucher-delete",
                                    "voucher-edit",
                                ],
                            ]) && (
                                <div className=" bg-white dark:text-gray-200 dark:bg-secondary-dark-bg rounded-2xl  md:w-400 p-6 drop-shadow-lg">
                                    <p className="font-extrabold md:text-xl text-gray-400">
                                        {t("charts.yearlyProfit")}
                                    </p>
                                    <div className="w-72 md:w-80">
                                        <Pie
                                            id="pie-chart"
                                            data={mapDataToPieChart(
                                                { total: "y", year: "x" },
                                                statistics.invoicesYearsProfit,
                                                statistics.invoicesYearsProfitTotal
                                            )}
                                            legendVisiblity
                                            height="full"
                                        />
                                    </div>
                                </div>
                            )}
                    </div>
                </div>
            </div>
        </>
    );
};

export default withTranslation()(Dashboard);
