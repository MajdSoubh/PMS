import React from "react";
import {
    ChartComponent,
    SeriesCollectionDirective,
    SeriesDirective,
    Inject,
    LineSeries,
    DateTime,
    Legend,
    Tooltip,
} from "@syncfusion/ej2-react-charts";

import { LinePrimaryXAxis, LinePrimaryYAxis } from "../../../utils/UIUtils";

import { useUIContext } from "../../../context/UIContext";
const LineChart = ({ invoices, purchases }) => {
    const { currentMode } = useUIContext();
    return (
        <ChartComponent
            id="line-chart"
            height="420px"
            primaryXAxis={LinePrimaryXAxis}
            primaryYAxis={LinePrimaryYAxis}
            chartArea={{ border: { width: 0 } }}
            tooltip={{ enable: true }}
            // background={currentMode === "Dark" ? "#33373E" : "#fff"}
            background={currentMode === "Dark" ? "#33373E" : "#fff"}
            legendSettings={{ background: "white" }}
        >
            <Inject services={[LineSeries, DateTime, Legend, Tooltip]} />
            <SeriesCollectionDirective>
                <SeriesDirective
                    dataSource={invoices}
                    xName={"x"}
                    yName={"y"}
                    name={"Invoices"}
                    width={"2"}
                    marker={{ visible: true, width: 10, height: 10 }}
                    type={"Line"}
                />
                <SeriesDirective
                    dataSource={purchases}
                    xName={"x"}
                    yName={"y"}
                    name={"Purchases"}
                    width={"2"}
                    marker={{ visible: true, width: 10, height: 10 }}
                    type={"Line"}
                />
            </SeriesCollectionDirective>
        </ChartComponent>
    );
};

export default LineChart;
