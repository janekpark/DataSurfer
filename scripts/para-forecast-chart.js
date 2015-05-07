/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
function isInArray(value, array) {
    if(array.indexOf(value) > -1){
        return true;
    }
    return false;
}
function shortenBigNumber(num) {
    var units = ['K', 'M', 'G', 'T', 'P', 'E', 'Z', 'Y'],
            decimal;

    for (var i = units.length - 1; i >= 0; i--) {
        decimal = Math.pow(1000, i + 1);

        if (num >= decimal) {
            return Math.round(num / decimal, -2) + units[i];
        }
    }

    return num;
}
var arrYearForecastEthnicity = [];
var arrDataForecastEthnicity = [];
var arrDataSumForecastEthnicity = [];
var cYear =[];
var para_over_forecast_ethnicity={
    credits: {
        enabled: false
    },
    chart: {
        renderTo:'chart-1',
        type: 'area',
        style: {
            fontFamily: chart_fontFamily, // default font
            fontSize: chart_fontSize,
            fontWeight: chart_fontWeight,
            color:color,
        },
        spacingLeft: spacing_left_forcast_ethnicity,
        spacingBottom: spacing_bottom_forcast_ethnicity,
        marginLeft:margin_left_forcast_ethnicity,
        marginRight:margin_right_forcast_ethnicity,
        marginBottom: margin_bottom_forcast_ethnicity,
        marginTop:margin_top_forcast_ethnicity,
    },
    title: {
        text: ''
    },
    xAxis: {
        categories: arrYearForecastEthnicity,
        labels: {
            style: {
                fontSize: overview_axisData,
                color:color,
            },
            rotation: -45
        },
        title: {
            text: 'YEAR',
            margin: 0,
            offset: offset_x_title_forcast_ethnicity,
            style:{
                color:color,
                fontSize: overview_axisLable,
				fontWeight: chart_fontWeight,
            }
        },
        lineWidth: 0,
        tickLength: 0,
        minorTickLength: 0
    },
    yAxis: {
        min: 0,
        //max:maxVal,
        title: {
            text: 'TOTAL POPULATION',
            style:{
                color:color,
                fontSize: overview_axisLable,
				fontWeight: chart_fontWeight,
            },
            offset:offset_y_title_forcast_ethnicity,
            y:10
        },
        labels: {
            style: {
                fontSize:overview_axisData,
                color:color,
				fontWeight: chart_fontWeight,
            },
            formatter: function() {
                var str_result = "0";
                if (this.value > 0) {
                    str_result = shortenBigNumber2(this.value,1)
                }
                return str_result;
            },
            x:-5
        },
        stackLabels: {
            enabled: false,
            style: {
                fontWeight: 'normal',
                color:color,
            }
        },
        gridLineWidth: 0,
        gridLineColor: '#ffffff'
    },
    tooltip: {
        //shared: true,
        style: {
            fontFamily: chart_fontFamily,
            fontSize: font_size_tool_tip,
            fontWeight: chart_fontWeight,
            marginLeft:'5px'
        },
        //headerFormat: '<font size='+font_size_tool_tip+'>{point.key}</font><br>',
        formatter: function () {
            var s = '<font size='+font_size_tool_tip+'>' + this.x + '</font>',
                sum = 0,
                nowY = this.y,
                points = [],
                series = this.series.chart.series,
                len = series.length,
                i = 0;
            for(i;i<len;i++) {
                points.push(series[i].points[this.point.x]);
            }

            $.each(points, function (i, point) {
                var window_width = $(window).width();
                
                if(window_width >=1024){
                    s += '<br/><span style=\"color:' + point.series.color + '\;font-size:9px;">\u25CF </span>' + point.series.name + ': <b>' + Highcharts.numberFormat(point.y, 0) + ' ('+Highcharts.numberFormat(point.percentage,1)+'%)</b>';
                }else{
                    s += '<br/><span style=\"color:' + point.series.color + '\">\u25CF </span>' + point.series.name + ': <b>' + Highcharts.numberFormat(point.y, 0)+ ' ('+Highcharts.numberFormat(point.percentage,1)+'%)</b>';
                }
                sum = sum + point.y;
            });
            s += '<br/>Total: <b>' + Highcharts.numberFormat(sum, 0) + '<b>';
            return s;
        },
    },
    plotOptions: {
        area: {
            stacking: 'normal',
            //lineColor: '#ffffff',
            lineWidth: 1,
            marker: {
                enabled: false,
                symbol: "circle",
                states: {
                    hover: {
                        enabled: false
                    }
                }
            },
            fillOpacity:1
        },
        series: {
            states: {
                hover: {
                    enabled: true,
                }
            },
            point: {
                events: {
                    mouseOver: function() {
                        /*this.options.oldColor = this.color;
                        var cKey = this.series.name.replace(/ /g, "").toLowerCase();
                        this.graphic.attr({
                            fill: colorEthnicityForecast[cKey][1]
                        });*/
                    },
                    mouseOut: function() {
                        //this.graphic.attr("fill", this.options.oldColor);
                    }
                }
            }

        }
    },
    legend: {
        itemStyle: {
            fontSize: overview_Legend,
            font: 'Ubuntu',
            fontWeight: 'normal',
            color:color,
        },
        symbolHeight: 6,
        symbolWidth: 8,
        labelFormatter: function() {
            var str = this.name
            if(str=="Two or More"){
                str="2 OR MORE";
            }else if(str=="Pacific Islander"){
                str="PAC ISLAND";
            }else if(str=="American Indian"){
                str="AM IND";
            }
            return str.toUpperCase();
        },
        align: align_legend,
        verticalAlign: vertical_align_legend,
        layout: layout_legend,
        itemMarginBottom: 5.25,
        y:y_legend_forcast_ethnicity,
        x:x_legend_forcast_ethnicity
    },
    series: arrDataForecastEthnicity,
    exporting: {
        enabled: false
    }
};
var max_detail_forecast_ethnicity = 0;
var para_detail_forecast_ethnicity={
    credits: {
        enabled: false
    },
    chart: {
        renderTo:'chart-detail',
        type: 'area',
        width: 0,
        style: {
            fontFamily: chart_fontFamily,
            fontSize: chart_fontSize,
            fontWeight: chart_fontWeight,
            color:color,
        },
        spacingLeft: spacing_left_detail_forcast_ethnicity,
        spacingBottom: spacing_bottom_detail_forcast_ethnicity,
        marginLeft:margin_left_detail_forcast_ethnicity,
        marginRight:margin_right_detail_forcast_ethnicity,
        marginBottom: margin_bottom_detail_forcast_ethnicity,
        marginTop:margin_top_detail_forcast_ethnicity,
        reflow: true
    },
    title: {
        text: '',
        style: {
            color:color,
            fontFamily: chart_fontFamily,
            fontSize: "18px",
            fontWeight: 'normal'
        },
        align: 'left',
        x: 10,
        y: 20
    },
    xAxis: {
        categories: arrYearForecastEthnicity,
        tickmarkPlacement: 'on',
        labels: {
            style: {
                fontSize: detail_axisData,
                color:color,
                fontWeight: chart_fontWeight,
            },
            y:label_x_detail_forcast_ethnicity
        },
        title: {
            text: 'YEAR',
            style:{
                fontSize: detail_axisLable,
                color:color,
                fontWeight: chart_fontWeight,
            },
            //margin:margin_title
            offset:offset_x_title_detail_forcast_ethnicity
        },
        lineWidth: 0,
        tickLength: 0,
        minorTickLength: 0
    },
    yAxis: {
        min: 0,
        max:max_detail_forecast_ethnicity,
        title: {
            text: 'TOTAL POPULATION',
            style:{
                fontSize: detail_axisLable,
                color:color,
                fontWeight: chart_fontWeight,
            },
            //margin:margin_title
            offset:offset_y_title_detail_forcast_ethnicity
        },
        labels: {
            style: {
                fontSize: detail_axisData,
                color:color,
                fontWeight: chart_fontWeight,
            },
            formatter: function() {
                var str_result = "0";
                if (this.value > 0) {
                    str_result = shortenBigNumber(this.value)
                }
                return str_result;
            },
            //y:label_y_detail_forcast_ethnicity
        },
        stackLabels: {
            enabled: false,
            style: {
                fontWeight: 'normal',
                color:color,
                //color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
            }
        },
        gridLineWidth: 1
    },
    tooltip: {
        //shared: true,
        style: {
            fontFamily: chart_fontFamily,
            fontSize: font_size_detail_tool_tip,
            fontWeight: chart_fontWeight,
        },
        //headerFormat: '<font size='+font_size_detail_tool_tip+'>{point.key}</font><br>',
        formatter: function () {
            var s = '<font size='+font_size_tool_tip+'>' + this.x + '</font>',
                sum = 0,
                nowY = this.y,
                points = [],
                series = this.series.chart.series,
                len = series.length,
                i = 0;
            for(i;i<len;i++) {
                points.push(series[i].points[this.point.x]);
            }

            $.each(points, function (i, point) {
                var window_width = $(window).width();
                s += '<br/><span style=\"color:' + point.series.color + '\">\u25CF </span>' + point.series.name + ': <b>' + Highcharts.numberFormat(point.y, 0) + ' ('+Highcharts.numberFormat(point.percentage,1)+'%)</b>';
                sum = sum + point.y;
            });
            s += '<br/>Total: <b>' + Highcharts.numberFormat(sum, 0) + '<b>';
            return s;
        },
    },
    plotOptions: {
        area: {
            stacking: 'normal',
            //lineColor: '#ffffff',
            lineWidth: 1,
            marker: {
                enabled: true,
                symbol: "circle",
                radius: 3
            },
            pointStart: 0,
            fillOpacity:1
        },
        series: {
            states: {
                hover: {
                    enabled: true,
                }
            },
            point: {
                events: {
                    mouseOver: function() {
                        /*this.options.oldColor = this.color;
                        var cKey = this.name.replace(/ /g, "").toLowerCase();
                        this.graphic.attr({
                            fill: colorEthnicityForecast[cKey][1]
                        });*/
                    },
                    mouseOut: function() {
                        this.graphic.attr("fill", this.options.oldColor);
                    }
                }
            }

        }

    },
    legend: {
        itemStyle: {
            fontSize: detail_legend,
            font: 'Ubuntu',
            fontWeight: 'normal',
            color:color,
        },
        symbolHeight: detail_symbol_height,
        symbolWidth: detail_symbol_width,
        labelFormatter: function() {
            var str = this.name
            if(str=="Two or More"){
                str="2 OR MORE";
            }else if(str=="Pacific Islander"){
                str="PAC ISLAND";
            }else if(str=="American Indian"){
                str="AM IND";
            }
            return str.toUpperCase();
        },
        align: align_legend,
        verticalAlign: vertical_align_legend,
        layout: layout_legend,
        itemMarginTop: item_margin_top_detail_forcast_ethnicity,
        itemMarginBottom: item_margin_bottom_detail_forcast_ethnicity,
        y:y_legend_detail_forcast_ethnicity,
        x:x_legend_detail_forcast_ethnicity

    },
    series: arrDataForecastEthnicity,
    exporting: {
        enabled: false
    }
};


var arrYearForecastHousing = [];
var arrDataForecastHousing = [];
var max_detail_forecast_housing = 0;
var para_over_forecast_housing={
    credits: {
        enabled: false
    },
    chart: {
        renderTo:'chart-2',
        type: 'column',
        spacingLeft: spacing_left_forcast_housing,
        spacingBottom: spacing_bottom_forcast_housing,
        marginLeft:margin_left_forcast_housing,
        marginRight:margin_right_forcast_housing,
        marginBottom: margin_bottom_forcast_housing,
        marginTop:margin_top_forcast_housing,
        style: {
            fontFamily: chart_fontFamily, // default font
            fontSize: chart_fontSize,
            fontWeight: chart_fontWeight,
            color:color,
        },
        reflow:false,
    },
    title: {
        text: ''
    },
    xAxis: {
        categories: arrYearForecastHousing.sort(),
        labels: {
            style: {
                fontSize: overview_axisData,
                color:color,
                fontWeight: chart_fontWeight,
            },
            rotation: -45
        },
        title: {
            text: 'YEAR',
            margin: 0,
            offset: offset_x_title_forcast_housing,
            style:{
                color:color,
                fontSize: overview_axisLable,
                fontWeight: chart_fontWeight,
            }
        },
        lineWidth: 0,
        tickLength: 0,
        minorTickLength: 0
    },
    yAxis: {
        min: 0,
        max:max_detail_forecast_housing,
        title: {
            text: 'TOTAL HOUSING TYPES',
            style:{
                color:color,
                fontSize: overview_axisLable,
                fontWeight: chart_fontWeight,
            },
            offset:offset_y_title_forcast_housing,
            x:-5,
            y:10
        },
        stackLabels: {
            enabled: false,
            style: {
                fontWeight: 'normal',
                color:color,
            }
        },
        labels: {
            style: {
                fontSize: overview_axisData,
                color:color,
                fontWeight: chart_fontWeight,
            },
            formatter: function() {
                var str_result = "0";
                if (this.value > 0) {
                    str_result = shortenBigNumber(this.value)
                }
                return str_result;
            },
            x:-10
        },
        gridLineWidth: 0,
        gridLineColor: '#ffffff'
    },
    plotOptions: {
        column: {
            stacking: 'normal',
            dataLabels: {
                enabled: false,
                color:color,
                style: {
                    pointPadding: 0,
                    textShadow: '0 0 3px black',
                    color:color,
                }
            },
            borderWidth:0,
        },
        series: {
            states: {
                hover: {
                    enabled: false,
                }
            },
            point: {
                events: {
                    mouseOver: function() {
                        this.options.oldColor = this.color;
                        var cKey = this.series.name.replace(/ |-/g, "").toLowerCase();
                        this.graphic.attr({
                            fill: colorHousingTypeForecast[cKey][1]
                        });
                    },
                    mouseOut: function() {
                        this.graphic.attr("fill", this.options.oldColor);
                    }
                }
            }

        }

    },
    tooltip: {
        formatter: function() {
            var str=this.series.name 
            if(str=="Single Family - Multiple Unit"){
                str="Single Family Multiple";
            }else if(str=="Multifamily"){
                str="Multiple Family";
            }else if(str=="Single Family - Detached"){
                str="Single Family Detached";
            }else if(str=="Mobile Home"){
                str="Mobile Homes";
            }
            str=str.replace("-","");
     
            return this.x + '<br/><span style="color: ' + this.series.color + '">' + str + ': </span><b>' + Highcharts.numberFormat(this.y, 0, '.', ',') + ' ('+Highcharts.numberFormat(this.percentage,1)+'%)</b><br/>' +
                    'Total: <b>' + Highcharts.numberFormat(this.point.stackTotal, 0, '.', ',')+'</b>';
        },
        style: {
            fontFamily: chart_fontFamily,
            fontSize: font_size_tool_tip,
            fontWeight: chart_fontWeight,
        },
    },
    legend: {
        itemStyle: {
            fontSize: overview_Legend,
            font: 'Ubuntu',
            fontWeight: 'normal',
            color:color,
            lineHeight: '11%'
        },
        symbolHeight: 6,
        symbolWidth: 8,
        symbolPadding: 3,
        labelFormatter: function() {
            var str=this.name;
			var window_width = $(window).width();
            if (window_width <1024) {
                if(str=="Single Family - Multiple Unit"){
                    str="SINGLE FAMILY MULTIPLE";
                }else if(str=="Multifamily"){
                    str="MULTIPLE FAMILY";
                }else if(str=="Single Family - Detached"){
                    str="SINGLE FAMILY DETACHED";
                }else if(str=="Mobile Home"){
                    str="MOBILE HOMES";
                }
                return str.toUpperCase();
            } else {
                if(str=="Single Family - Multiple Unit"){
                    str="SINGLE FAMILY - MULTIPLE";
                }else if(str=="Multifamily"){
                    str="MULTIPLE - FAMILY";
                }else if(str=="Single Family - Detached"){
                    str="SINGLE FAMILY - DETACHED";
                }else if(str=="Mobile Home"){
                    str="MOBILE - HOMES";
                }
                return str.toUpperCase().replace('-', '<br/>');
            }
        },
        align: align_legend,
        verticalAlign: vertical_align_legend,
        layout: layout_legend,
        //itemMarginTop: 5,
        itemMarginBottom: 5,
        y:y_legend_forcast_housing,
        x:x_legend_forcast_housing
    },
    series: arrDataForecastHousing,
    exporting: {
        enabled: false
    }
};
var para_detail_forecast_housing={
    credits: {
        enabled: false
    },
    chart: {
        renderTo:'chart-detail_2',
        type: 'column',
        width: 0,
        style: {
            fontFamily: 'Ubuntu', // default font
            fontSize: '12px',
            fontWeight: 'normal',
            color:color,
        },
        spacingLeft: spacing_left_detail_forcast_housing,
        spacingBottom: spacing_bottom_detail_forcast_housing,
        marginLeft:margin_left_detail_forcast_housing,
        marginRight:margin_right_detail_forcast_housing,
        marginBottom: margin_bottom_detail_forcast_housing,
        marginTop:margin_top_detail_forcast_housing,
        reflow: false
    },
    title: {
        text: '',
        style: {
            color:color,
            fontFamily: chart_fontFamily,
            fontSize: "18px",
            fontWeight: 'normal'
        },
        align: 'left',
        x: 10,
        y: 20,
        spacingButon: 20,
    },
    xAxis: {
        categories: arrYearForecastHousing.sort(),
        labels: {
            style: {
                fontSize: detail_axisData,
                color:color,
                fontWeight: chart_fontWeight,
            },
            y:label_x_detail_forcast_housing
        },
        title: {
            text: 'YEAR',
            offset: offset_x_title_detail_forcast_housing,
            style:{
                fontSize: detail_axisLable,
                color:color,
                fontWeight: chart_fontWeight,
            },
        },
        lineWidth: 0,
        tickLength: 0,
        minorTickLength: 0
    },
    yAxis: {
        min: 0,
        max:max_detail_forecast_housing,
        title: {
            text: 'TOTAL HOUSING TYPES',
            align: 'middle',
            style:{
                color:color,
                fontSize: detail_axisLable,
                fontWeight: chart_fontWeight,
            },
            offset:offset_y_title_detail_forcast_housing
        },
        tackLabels: {
            enabled: true,
            style: {
                fontWeight: 'normal',
                color:color,
            }
        },
        labels: {
            style: {
                fontSize: detail_axisData,
                color:color,
                fontWeight: chart_fontWeight,
            },
            formatter: function() {
                var str_result = "0";
                if (this.value > 0) {
                    str_result = shortenBigNumber3(this.value,1)
                }
                return str_result;
            },
        }
    },
    plotOptions: {
        column: {
            stacking: 'normal',
            dataLabels: {
                enabled: true,
                color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
                style: {
                    pointPadding: 0,
                    textShadow: 'none',
                    fontWeight:'normal',
                    fontSize: detail_callout,
                    color:color,
                },
                formatter: function() {
					var window_width = $(window).width();
                    if(window_width <1024){
                        if(window_width >= 768){
                            return Highcharts.numberFormat(this.point.y, 0, '.', ',');
                        }
                        return '';
                    }else{
                        return Highcharts.numberFormat(this.point.y, 0, '.', ',');
                    }
                }
            },
            borderWidth:0,
        },
        series: {
            states: {
                hover: {
                    enabled: false,
                }
            },
            point: {
                events: {
                    mouseOver: function() {
                        this.options.oldColor = this.color;
                        var cKey = this.series.name.replace(/ |-/g, "").toLowerCase();
                        this.graphic.attr({
                            fill: colorHousingTypeForecast[cKey][1]
                        });
                    },
                    mouseOut: function() {
                        this.graphic.attr("fill", this.options.oldColor);
                    }
                }
            }

        }
    },
    legend: {
        itemStyle: {
            fontSize: detail_legend,
            font: 'Ubuntu',
            fontWeight: 'normal',
            color:color,
        },
        symbolHeight: detail_symbol_height,
        symbolWidth: detail_symbol_width,
        //symbolPadding: 3,
        labelFormatter: function() {
            var str=this.name;
            if(str=="Single Family - Multiple Unit"){
                str="SINGLE FAMILY - MULTIPLE";
            }else if(str=="Multifamily"){
                str="MULTIPLE FAMILY";
            }else if(str=="Single Family - Detached"){
                str="SINGLE FAMILY - DETACHED";
            }else if(str=="Mobile Home"){
                str="MOBILE HOMES";
            }
			var window_width = $(window).width();
            if (window_width <1024) {
                return str.toUpperCase();
            } else {
                return str.toUpperCase().replace('-', '<br/>');
            }
        },
        align: align_legend,
        verticalAlign: vertical_align_legend,
        layout: layout_legend,
        itemMarginTop: item_margin_top_detail_focast_housing,
        itemMarginBottom: item_margin_bottom_detail_focast_housing,
        y:y_legend_detail_forcast_housing,
        x:x_legend_detail_forcast_housing,
    },
    tooltip: {
        formatter: function() {
            var str=this.series.name 
            if(str=="Single Family - Multiple Unit"){
                str="Single Family Multiple";
            }else if(str=="Multifamily"){
                str="Multiple Family";
            }else if(str=="Single Family - Detached"){
                str="Single Family Detached";
            }else if(str=="Mobile Home"){
                str="Mobile Homes";
            }
            str=str.replace("-","");
            
            return this.x + '<br/><span style="color: ' + this.series.color + '">' + str + ': </span><b>' + Highcharts.numberFormat(this.y, 0, '.', ',') + ' ('+Highcharts.numberFormat(this.percentage,1)+'%)</b><br/>' +
                    'Total: <b>' + Highcharts.numberFormat(this.point.stackTotal, 0, '.', ',')+'</b>';
        },
        style: {
            fontFamily: chart_fontFamily,
            fontSize: font_size_detail_tool_tip,
            fontWeight: chart_fontWeight,
        },
    },
    series: arrDataForecastHousing,
    exporting: {
        enabled: false
    }
};

var arrYearForecastJob = [];
var arrDataForecastJob = [];
var max_detail_forecast_job = 0; 
var para_over_forecast_job={
    credits: {
        enabled: false
    },
    chart: {
        renderTo:'chart-4',
        type: 'bar',
        style: {
            fontFamily: chart_fontFamily,
            fontSize: chart_fontSize,
            fontWeight: chart_fontWeight,
            color:color,
        },
        spacingLeft: spacing_left_forcast_job,
        spacingBottom: spacing_bottom_forcast_job,
        marginLeft:margin_left_forcast_job,
        marginRight:margin_right_forcast_job,
        marginBottom: margin_bottom_forcast_job,
        marginTop:margin_top_forcast_job,
        reflow:false,
    },
    title: {
        text: ''
    },
    xAxis: {
        categories: arrYearForecastJob,
        labels: {
            style: {
                fontSize: overview_axisData,
                width: 300,
                color:color,
                fontWeight: chart_fontWeight,
            },
        },
        title: {
            text: 'YEAR',
            style:{
                color:color,
                fontSize: overview_axisLable,
                fontWeight: chart_fontWeight,
            },
            offset:offset_x_title_forcast_job
        },
        lineWidth: 0,
        tickLength: 0,
        minorTickLength: 0
    },
    yAxis: {
        min: 0,
        //max:maxVal,
        title: {
            text: 'TOTAL NUMBER OF JOBS',
            margin: 0,
            offset: offset_y_title_forcast_job,
            style:{
                color:color,
                fontSize: overview_axisLable,
                fontWeight: chart_fontWeight,
            }
        },

        labels: {
            style: {
                fontSize: overview_axisData,
                color:color,
                fontWeight: chart_fontWeight,
            },
            formatter: function() {
                var str_result = "0";
                if (this.value > 0) {
                    str_result = shortenBigNumber2(this.value,1)
                }
                return str_result;
            },
            rotation: -45
        },
        gridLineWidth: 0,
        gridLineColor: '#ffffff'
    },
    plotOptions: {
        bar:{
            borderWidth:0,
        },
        series: {
            stacking: 'normal',
            states: {
                hover: {
                    enabled: false,
                }
            },
            point: {
                events: {
                    mouseOver: function() {
                        this.options.oldColor = this.color;
                        var cKey = this.series.name.replace(/ |&/g, "").toLowerCase();
                        this.graphic.attr({
                            fill: fontJobForecast[cKey][1]
                        });
                    },
                    mouseOut: function() {
                        this.graphic.attr("fill", this.options.oldColor);
                    }
                }
            }
        },
    },
    legend: {
        itemStyle: {
            fontSize: overview_Legend,
            font: 'Ubuntu',
            fontWeight: 'normal',
            color:color,
            lineHeight: '10%',
        },
        symbolHeight: 6,
        symbolWidth: 8,
        //  symbolPadding: 3,
        labelFormatter: function() {
            var str = this.name;
			var window_width = $(window).width();
            if(window_width >= 1024){
                if(str=="Business Services"){
                    str="BUSINESS <br>SERVICES";
                    return str;
                }else if(str=="Education & Health"){
                    str="EDUCATION & <br>HEALTH";
                    return str;
                }else if(str=="Leisure & Hospitality"){
                    str="LEISURE & <br>HOSPITALITY";
                    return str;
                }else{
                    return str.toUpperCase();
                }
            }else{
                return str.toUpperCase();
            }
        },
        align: align_legend,
        verticalAlign: vertical_align_legend,
        layout: layout_legend,
        //itemMarginTop: 3,
        itemMarginBottom: 5.25,
        reversed: true,
        y: y_legend_forcast_job,
        x:x_legend_forcast_job
    },
    tooltip: {
        valueSuffix: '',
        formatter: function() {
            // display only if larger than 1
            return this.x + '<br><span style="color: ' + this.series.color + '">' + this.series.name + ':</span> <b> ' + Highcharts.numberFormat(this.y, 0, '.', ',') + '</b>';
        },
        style: {
            fontFamily: chart_fontFamily,
            fontSize: font_size_tool_tip,
            fontWeight: chart_fontWeight,
        },
    },
    series: arrDataForecastJob,
    exporting: {
        enabled: false
    }
};
var para_detail_forecast_job={
    credits: {
        enabled: false
    },
    chart: {
        renderTo:'chart-detail_3',
        type: 'bar',
        width: 0,
        style: {
            fontFamily: 'Ubuntu', // default font
            fontSize: '12px',
            fontWeight: 'normal',
            color:color,
        },
        spacingLeft: spacing_left_detail_forcast_job,
        spacingBottom: spacing_bottom_detail_forcast_job,
        marginLeft:margin_left_detail_forcast_job,
        marginRight:margin_right_detail_forcast_job,
        marginBottom: margin_bottom_detail_forcast_job,
        marginTop:margin_top_detail_forcast_job,
        reflow: false
    },
    title: {
        text: '',
        style: {
            color:color,
            fontFamily: chart_fontFamily,
            fontSize: "18px",
            fontWeight: 'normal'
        },
        align: 'left',
        x: 10,
        y: 20
    },
    xAxis: {
        categories: arrYearForecastJob,
        labels: {
            style: {
                fontSize: detail_axisData,
                color:color,
                fontWeight: chart_fontWeight,
            },
            x:label_x_detail_forcast_job
        },
        title: {
            text: 'YEAR',
            offset: offset_y_title_detail_forcast_job,
            style:{
                fontSize: detail_axisLable,
                color:color,
                fontWeight: chart_fontWeight,
            },
            margin:margin_title
        },
        lineWidth: 0,
        tickLength: 0,
        minorTickLength: 0
    },
    yAxis: {
        min: 0,
        max:max_detail_forecast_job,
        title: {
            text: 'TOTAL NUMBER OF JOBS',
            align: 'middle',
            style:{
                color:color,
                fontSize: detail_axisLable,
                fontWeight: chart_fontWeight,
            },
            offset:offset_x_title_detail_forcast_job
        },
        stackLabels: {
            enabled: false,
            style: {
                fontWeight: 'normal',
                color:color,
            }
        },
        labels: {
            title: {
                text: 'YEAR',
                style:{
                    color:color,
                    fontSize: detail_axisData,
                    fontWeight: chart_fontWeight,
                }
            },
            style: {
                fontSize: detail_axisData,
                color:color,
                fontWeight: chart_fontWeight,
            },
            formatter: function() {
                var str_result = "0";
                if (this.value > 0) {
                    str_result = shortenBigNumber2(this.value,1)
                }
                return str_result;
            },
            y:label_y_detail_forcast_job
        }
    },
    plotOptions: {
        bar:{
            pointWidth: point_width_detail_focast_job, //width of the column bars irrespective of the chart size
            borderWidth:0,
        },
        series: {
            stacking: 'normal',
            states: {
                hover: {
                    enabled: false,
                }
            },
            point: {
                events: {
                    mouseOver: function() {
                        this.options.oldColor = this.color;
                        var cKey = this.series.name.replace(/ |&/g, "").toLowerCase();
                        this.graphic.attr({
                            fill: fontJobForecast[cKey][1]
                        });
                    },
                    mouseOut: function() {
                        this.graphic.attr("fill", this.options.oldColor);
                    }
                }
            }
        }
    },
    legend: {
        itemStyle: {
            fontSize: detail_legend,
            font: 'Ubuntu',
            fontWeight: 'normal',
            color:color,
        },
        symbolHeight: detail_symbol_height,
        symbolWidth: detail_symbol_width,
        labelFormatter: function() {
            return this.name.toUpperCase();
        },
        align: align_legend,
        verticalAlign: vertical_align_legend,
        layout: layout_legend,
        itemMarginTop: item_margin_top_detail_focast_job,
        itemMarginBottom: item_margin_bottom_detail_focast_job,
        x:x_legend_detail_forcast_job,
        y:y_legend_detail_forcast_job,
        reversed: true
    },
    tooltip: {
        valueSuffix: '',
        formatter: function() {
            // display only if larger than 1
            return  this.x + '<br><span style="color: ' + this.series.color + '">' + this.series.name + ':</span> <b> ' + Highcharts.numberFormat(this.y, 0, '.', ',') + '</b>';
        },
        style: {
            fontFamily: chart_fontFamily,
            fontSize: font_size_detail_tool_tip,
            fontWeight: chart_fontWeight,
        },
    },
    series: arrDataForecastJob,
    exporting: {
        enabled: false
    }
};

var categories_forecast_income = [];
var arrDataForecastIncome = [];
var para_over_forecast_income={
    credits: {
        enabled: false
    },
    chart: {
        renderTo:'chart-5',
        type: 'area',
        style: {
            fontFamily: chart_fontFamily,
            fontSize: chart_fontSize,
            fontWeight: chart_fontWeight,
            color:color,
        },
        spacingLeft: spacing_left_forcast_income,
        spacingBottom: spacing_bottom_forcast_income,
        marginLeft:margin_left_forcast_income,
        marginRight:margin_right_forcast_income,
        marginBottom: margin_bottom_forcast_income,
        marginTop:margin_top_forcast_income,
        reflow:false,
    },
    title: {
        text: ''
    },
    xAxis: {
        categories: categories_forecast_income,
        labels: {
            style: {
                fontSize: overview_axisData,
                rotation: -65,
                margin: 0,
                paddingTop: '0px',
                color:color,
                lineHeight: '10%', //(Thuan Apr 2) added this to control spacing between lines
            },
            formatter: function() {
                var str = this.value;
                if(typeof(str)=='string'){
                    str = str.replace("&lt;","<");
					var window_width = $(window).width();
                    if (window_width <1024) {
                        return str.toUpperCase();
                    }else{
                        return str.toUpperCase().replace('-', '-<br/>');
                    }
                }else{
                    return '';
                }
            },
            y:10
        },
        title: {
            text: 'INCOME',
            margin: 0,
            offset: offset_x_title_forcast_income,
           style:{
                color:color,
                fontSize: overview_axisLable,
                fontWeight: 'normal',
            }
        },
        lineWidth: 0,
        tickLength: 0,
        minorTickLength: 0
    },
    yAxis: {
        min: 0,
        title: {
            text: 'TOTAL HOUSEHOLDS',
            style:{
                color:color,
                fontSize: overview_axisLable,
                fontWeight: 'normal',
            },
            offset:offset_y_title_forcast_income,
            y:y_title_forcast_income,
        },
        stackLabels: {
            enabled: false,
            style: {
                fontWeight: 'normal',
                color:color,
            }
        },
        labels:{
            style: {
                fontSize: overview_axisData,
                color:color,
                fontWeight: chart_fontWeight,
            },
            formatter: function() {
                var str_result = "0";
                if (this.value > 0) {
                    str_result = shortenBigNumber(this.value)
                }
                return str_result;
            },
            x:3
        },
        gridLineWidth: 0,
        gridLineColor: '#ffffff'
    },
    tooltip: {
        shared: true,
        style: {
            fontFamily: chart_fontFamily,
            fontSize: font_size_tool_tip,
            fontWeight: chart_fontWeight,
        },
        headerFormat: '<font size='+font_size_tool_tip+'>{point.key}</font><br>',
        //pointFormat: '<span style="color:{series.color};font-size:9px;">● </span>{series.name}: <b>{point.y}</b><br>',
    },
    plotOptions: {
        area: {
            stacking: 'normal',
            marker: {
                enabled: false,
                symbol: "circle",
                states: {
                    hover: {
                        enabled: false
                    }
                }
            },
            fillOpacity:1
        },
        series: {
            states: {
                hover: {
                    enabled: false,
                }
            },
            point: {
                events: {
                    mouseOver: function() {
                        this.options.oldColor = this.color;
                        var str_color = this.color;
                        if (isInArray(this.series.name, Object.keys(fontIncomeForecast))) {
                            str_color = fontIncomeForecast[this.series.name][0];
                        }

                        fillColor:str_color
                        var cKey = this.series.name;
                    },
                    mouseOut: function() {
                        fillColor:this.options.oldColor
                    }
                }
            }
        }

    },
    legend: {
        itemStyle: {
            fontSize: overview_Legend,
            font: 'Ubuntu',
            fontWeight: 'normal',
            color:color,
        },
        symbolHeight: 6,
        symbolWidth: 8,
        labelFormatter: function() {
            return this.name;
        },
        align: align_legend,
        verticalAlign: vertical_align_legend,
        layout: layout_legend,
        itemMarginBottom: 5.25,
        y:y_legend_forcast_income,
        x:x_legend_forcast_income//-3
    },
    series: arrDataForecastIncome,
    exporting: {
        enabled: false
    }
};
var para_detail_forecast_income={
    credits: {
        enabled: false
    },
    chart: {
        renderTo:'chart-detail_4',
        type: 'area',
        width: 0,
        style: {
            fontFamily: chart_fontFamily,
            fontSize: chart_fontSize,
            fontWeight: chart_fontWeight,
            color:color,
        },
        spacingLeft: spacing_left_detail_forcast_income,
        spacingBottom: spacing_bottom_detail_forcast_income,
        marginLeft:margin_left_detail_forcast_income,
        marginRight:margin_right_detail_forcast_income,
        marginBottom: margin_bottom_detail_forcast_income,
        marginTop:margin_top_detail_forcast_income,
        reflow:false,
    },
    title: {
        text: '',
        style: {
            color:color,
            fontFamily: chart_fontFamily,
            fontSize: "18px",
            fontWeight: 'normal'
        },
        align: 'left',
        x: 10,
        y: 20
    },
    xAxis: {
        categories: categories_forecast_income,
        labels: {
            style: {
                fontSize: detail_axisData,
                color:color,
                fontWeight: chart_fontWeight,
            },
            rotation: rotation_detail_forcast_income,
            formatter: function() {
                var str = this.value;
                str=str.replace("&lt;","<");
				var window_width = $(window).width();
                if (window_width < 1024) {
                    if(window_width >= 768){
                        return str.toUpperCase().replace('-', '-<br/>');
                    }else{
                        return str.toUpperCase();
                    }
                }else{
                    return str.toUpperCase().replace('-', '-<br/>');
                }
            },
            y:label_x_detail_forcast_income
        },
        title: {
            text: 'INCOME',
            offset: offset_x_title_detail_forcast_income,
            style:{
                fontSize: detail_axisLable,
                color:color,
                fontWeight: chart_fontWeight,
            },
        },
        lineWidth: 0,
        tickLength: 0,
        minorTickLength: 0
    },
    yAxis: {
        min: 0,
        title: {
            text: 'TOTAL HOUSEHOLDS',
            align: 'middle',
            style:{
                color:color,
                fontSize: detail_axisLable,
                fontWeight: chart_fontWeight,
            },
            offset: offset_y_title_detail_forcast_income,
        },
        tackLabels: {
            enabled: true,
            style: {
                fontWeight: 'normal',
                color:color,
            }
        },
        labels: {
            style: {
                color:color,
                fontSize: detail_axisData,
                fontWeight: chart_fontWeight,
            },
            formatter: function() {
                var str_result = "0";
                if (this.value > 0) {
                    str_result = shortenBigNumber(this.value)
                }
                return str_result;
            }
        }
    },
    tooltip: {
        // pointFormat: '<span style="color:{series.color}">{series.name}</span>:{point.y:,.0f}<br/>',
        shared: true,
        style: {
            fontFamily: chart_fontFamily,
            fontSize: font_size_detail_tool_tip,
            fontWeight: chart_fontWeight,
        },
        headerFormat: '<font size='+font_size_detail_tool_tip+'>{point.key}</font><br>',
    },
    plotOptions: {
        area: {
            stacking: 'normal',
            //lineColor: '#ffffff',
            lineWidth: 1,
            marker: {
                enabled: false,
                symbol: "circle",
                states: {
                    hover: {
                        enabled: false
                    }
                }
            },
            fillOpacity:1
        },
    },
    legend: {
        itemStyle: {
            fontSize: detail_legend,
            font: 'Ubuntu',
            fontWeight: 'normal',
            color:color,
        },
        symbolHeight: detail_symbol_height,
        symbolWidth: detail_symbol_width,
        //  symbolPadding: 3,
        labelFormatter: function() {
            return this.name;
        },
        align: align_legend,
        verticalAlign: vertical_align_legend,
        layout: layout_legend,
        itemMarginTop: item_margin_top_detail_focast_income,
        itemMarginBottom: item_margin_bottom_detail_focast_income,
        y:y_legend_detail_forcast_income,
        x:x_legend_detail_forcast_income
    },
    series: arrDataForecastIncome,
    exporting: {
        enabled: false
    }
};


