/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
function redrawConnectors() {
    var chart = this,
            d;

    Highcharts.each(chart.series[0].data, function(point, i) {
        if (point.connector) {
            d = point.connector.d.split(' ');
            d = [d[0], d[1], d[2], d[10], d[11], d[12]];
            point.connector.attr({
                d: d
            });
        }
    });
}
var dataArrayEthnicity=[];
var dataDetailArrayEthnicity = [];
var arr_tmp_ethinicity=[];
var sum_key_ethnicity = 0;
var sAgEthnicity = 0;
var para_over_ethnicity = {
    credits: {
        enabled: false
    },
    chart: {
        renderTo: 'chart-1',
        type: 'pie',
        style: {
            fontFamily: chart_fontFamily,
            fontSize: chart_fontSize,
            fontWeight: chart_fontWeight,
            color:color,
        },
        plotShadow: false,
        events: {
            load: redrawConnectors,
            redraw: redrawConnectors
        },
        spacingLeft: 5,
        spacingRight: 5,
        reflow:false,
    },
    lang: {
        noData: "No residents in selected area."
    },
    noData: {
        style: {
            fontWeight: 'normal',
            fontSize: '12pt',
            fontFamily: chart_fontFamily,
            color: color
        }
    },
    title: {
        text: ''
    },
    yAxis: {
        title: {
            text: ''
        }
    },
    plotOptions: {
        pie: {
            borderWidth:0,
            dataLabels: {
                connectorPadding: 5,
                softConnector: true,
                distance: 8,
                crop:false,
                style: {
                    fontSize: pie_chart_data,
                    fontWeight: 'normal',
                    color:color,
                    textShadow: 'none'
                },
            },
            shadow: false,
            center: ['50%', '50%'],
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
                        var cKey = this.name.replace(/ /g, "").toLowerCase();
                        this.graphic.attr({
                            fill: colorEthnicityCensus[cKey][1]
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
        valueSuffix: '%',
        style: {
            fontFamily: chart_fontFamily,
            fontSize: font_size_tool_tip,
            fontWeight: chart_fontWeight,
        },
        formatter: function() {
            // display only if larger than 1
            for(var i=0;i<dataArrayEthnicity.length;i++){
                if(dataArrayEthnicity[i].y==this.y){
                    return  this.point.name + ': <b> ' + dataArrayEthnicity[i].fy + '%</b>';
                }
            }
        }
    },
    series: [{
            name: 'Ethnicity',
            data: dataArrayEthnicity,
            size:chart_size_ethnicity, // 70% (Thuan Apr 1)
            innerSize: chart_size_inner_ethnicity,
            dataLabels: {
                formatter: function() {
                    var str = this.point.name;
                    var val = this.y;
                    if(val < 1){
                        val=val.toString().replace("0.",".");
                    }
                    if(str=="Two or More"){
                        str="2 OR MORE";
                        return str +  ': ' + val + '%';
                    }else if(str=="Pacific Islander"){
                        str="PAC ISLAND";
                        return str+ ': ' + val + '%';
                    }else if(str=="American Indian"){
                        str="AM IND";
                        return str+ ': ' + val + '%';
                    }else{
                        return  this.point.name.toUpperCase() + ': ' + val + '%';
                    }
                    // display only if larger than 
                }
            },
            startAngle:0
        }],
    exporting: {
        enabled: false
    }
};

var para_detail_ethnicity = {
    credits: {
        enabled: false
    },
    chart: {
        type: 'pie',
        //width: 0,
        renderTo: 'chart-detail',
        style: {
            fontFamily: chart_fontFamily,
            fontSize: chart_fontSize,
            fontWeight: chart_fontWeight,
            color:color,
        },
        events: {
            load: redrawConnectors,
            redraw: redrawConnectors
        },
        reflow:false,
    },
    title: {
        text: '',
        style: {
            color: '#194378',
            fontFamily: chart_fontFamily,
            fontSize: "18px",
            fontWeight: 'normal',
            color:color,
        },
        align: 'left',
        x: 10,
        y: 20
    },
    yAxis: {
        title: {
            text: ''
        }
    },
    plotOptions: {
        pie: {
            borderWidth:0,
            dataLabels: {
                connectorPadding: 5,
                distance: pie_distance,
                softConnector: true,
                style: {
                    fontSize: detail_pie_chart_data,
                    fontWeight: 'normal',
                    color:color,
                    textShadow: 'none'
                }
            },
            shadow: false,
            center: ['50%', '50%'],
            innerSize: '50%',
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
                        var cKey = this.name.replace(/ /g, "").toLowerCase();
                        this.graphic.attr({
                            fill: colorEthnicityCensus[cKey][1]
                        });
                    },
                    mouseOut: function() {
                        this.graphic.attr("fill", this.options.oldColor);
                    }
                }
            }

        },
        dataLabels: {
            align: 'center',
            enabled: true,
        }
    },
    tooltip: {
        valueSuffix: '%',
        formatter: function() {
            return  this.point.name.toUpperCase() + ': ' + this.y + '%';
        },
        style: {
            fontFamily: chart_fontFamily,
            fontSize: font_size_detail_tool_tip,
            fontWeight: chart_fontWeight,
        },
    },
    series: [{
            name: 'Ethnicity',
            data: dataDetailArrayEthnicity,
            size: chart_size_ethnicity,
            innerSize: chart_size_inner_ethnicity,
            dataLabels: {
                formatter: function() {
                    var str = this.point.name;
                    var val = this.y;
                    if(str=="Two or More"){
                        str="2 OR MORE";
                        return str +  ': ' + val + '%';
                    }else if(str=="Pacific Islander"){
                        str="PAC ISLAND";
                        return str+ ': ' + val + '%';
                    }else if(str=="American Indian"){
                        str="AM IND";
                        return str+ ': ' + val + '%';
                    }else{
                        return  this.point.name.toUpperCase() + ': ' + val + '%';
                    }
                }
            },
            startAngle:0
        }],
    exporting: {
        enabled: false
    }
};

var sum_key_housing = 1;
var dataArrayHousing = [];
var occupieds_housing = [];
var vacants_housing = [];
var sAgHousing = 0;
var para_over_housing = {
    credits: {
        enabled: false
    },
    chart: {
        renderTo:'chart-2',
        type: 'pie',
        style: {
            fontFamily: chart_fontFamily,
            fontSize: chart_fontSize,
            fontWeight: chart_fontWeight,
            color:color,
        },
        events: {
            load: redrawConnectors,
            redraw: redrawConnectors
        },
        spacingLeft: 5,
        spacingRight: 5,
        reflow:false,
    },
    title: {
        text: ''
    },
    lang: {
        noData: "No housing units in selected area."
    },
    noData: {
        style: {
            fontWeight: 'normal',
            fontSize: '12pt',
            fontFamily: chart_fontFamily,
            color: color
        }
    },
    yAxis: {
        title: {
            text: ''
        }
    },
    plotOptions: {
        pie: {
            borderWidth:0,
            center: ['50%', '50%'],
            dataLabels: {
                enabled: true,
                formatter: function() {
                    var num = (this.y / sum_key_housing) * 100;
                    //console.log(this.point.name);
                    var str = this.point.name;
                    var val = Highcharts.numberFormat(num, 0);
                    if(val < 1){
                        val=val.toString().replace("0.",".");
                    }
                    if(str=="Single Family - Multiple Unit"){
                        str="SINGLE FAMILY <br>MULTIPLE";
                        return str + ': ' + val + '%';
                    }else if(str=="Multifamily"){
                        str="MULTIPLE <br>FAMILY";
                        return str+ ': ' + val + '%';
                    }else if(str=="Single Family - Detached"){
                        str="SINGLE <br>FAMILY <br>DETACHED";
                        return str + ': <br>' + val + '%';
                    }else if(str=="Mobile Home"){
                        str="MOBILE HOMES "+val + '%';
                        return str;
                    }else{
                        return str.toUpperCase().replace("-", "<br>") + ': ' +val + '%';
                    }
                },
                style: {
                    fontSize: pie_chart_data,
                    fontWeight: 'normal',
                    width: '120px',
                    color:color,
                    textShadow: 'none',
                    lineHeight: '11%', //(Thuan Apr 1) added this to control spacing between lines
                },
                distance: 8

            }
        },
        series: {
            states: {
                hover: {
                    enabled: false
                }
            },
            point: {
                events: {
                    mouseOver: function() {
                        this.options.oldColor = this.color;
                        var index = keys_housing.indexOf(this.y);
                        for(var i=0;i<dataArrayHousing.length;i++){
                            if(this.y==dataArrayHousing[i].y){
                                index = i;
                            }
                        }
                        this.graphic.attr({
                            fill: dataArrayHousing[index]['downstate']

                        });
                    },
                    mouseOut: function() {
                        this.graphic.attr("fill", this.options.oldColor);
                    }
                }
            },
            startAngle:0
        }
    },
    tooltip: {
        formatter: function() {
            var num = (this.y / sum_key_housing) * 100;
            var capitalized=this.point.name.replace("-", "<br>");
            if(capitalized=="Mobile Home"){
                capitalized="Mobile Homes";
            }else if(capitalized=="Multifamily"){
                capitalized="Mulitiple Family";
            }
            return capitalized + ': <b>' + Highcharts.numberFormat(num, 1) + '% <b>';

        },
        style: {
            fontFamily: chart_fontFamily,
            fontSize: font_size_tool_tip,
            fontWeight: chart_fontWeight,
        },
    },
    series: [{
        size: chart_size_housing,
        name: '',
        data: dataArrayHousing,
        startAngle: 0
    }],
    exporting: {
        enabled: false
    }
}
var para_detail_housing = {
    credits: {
        enabled: false
    },
    chart: {
        renderTo:'chart-detail_2',
        type: 'pie',
        width: 0,
        style: {
            fontFamily: chart_fontFamily,
            fontSize: chart_fontSize,
            fontWeight: 'normal',
            color:color,
        },
        events: {
            load: redrawConnectors,
            redraw: redrawConnectors
        },
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
    yAxis: {
        title: {
            text: ''
        }
    },
    plotOptions: {
        pie: {
            borderWidth:0,
            center: ['50%', '50%'],
            dataLabels: {
                formatter: function() {
                    var num = (this.y / sum_key_housing) * 100;
                    var str = this.point.name;
                    if(str=="Single Family - Multiple Unit"){
                        str="SINGLE FAMILY - MULTIPLE";
                    }else if(str=="Multifamily"){
                        str="MULTIPLE <br>FAMILY";
                    }else if(str=="Single Family - Detached"){
                        return str="SINGLE <br> FAMILY <br> DETACHED:<br>" + Highcharts.numberFormat(num, 1) + '%';
                    }else if(str=="Mobile Home"){
                        str="MOBILE HOMES";
                    }else{
                        str = str.toUpperCase().replace("-", "<br>");
                    }
                    if(num==0){
                        return  str.replace("-", "<br>")+ ': 0%';
                    }else{
                        return  str.replace("-", "<br>")+ ': ' + Highcharts.numberFormat(num, 1) + '%';
                    }
                    

                },
                style: {
                    fontSize: detail_pie_chart_data,
                    fontWeight: 'normal',
                    textShadow: 'none',
                    color:color,
                },
                distance:pie_distance,
            }
        },
        series: {
            states: {
                hover: {
                    enabled: false
                }
            },
            point: {
                events: {
                    mouseOver: function() {
                        this.options.oldColor = this.color;
                        var index = keys_housing.indexOf(this.y);
                        for(var i=0;i<dataArrayHousing.length;i++){
                            if(this.y==dataArrayHousing[i].y){
                                index = i;
                            }
                        }
                        this.graphic.attr({
                            fill: dataArrayHousing[index]['downstate']

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
            var num = (this.y / sum_key_housing) * 100;
            var index = keys_housing.indexOf(this.y);
            var num = (this.y / sum_key_housing) * 100;
            var num_occupied = (occupieds_housing[index] / sum_key_housing) * 100;
            var num_vacant = (vacants_housing[index] / sum_key_housing) * 100;
            var str = this.point.name;
            var val = Highcharts.numberFormat(num, 1);
            var val_num_occupied = Highcharts.numberFormat(num_occupied, 1);
            
            var val_num_vacant = Highcharts.numberFormat(num_vacant, 1);
            
            var capitalized = this.point.name[0].toUpperCase() + this.point.name.substring(1);
            capitalized = capitalized.replace("-","");
            if(capitalized=="Mobile Home"){
                capitalized="Mobile Homes";
            }else if(capitalized=="Multifamily"){
                capitalized="Mulitiple Family";
            }
            var str_tooltip = "";
            str_tooltip = capitalized + '<br>Total: <b>' + val + '% <b>';
            str_tooltip += "<br>Occupied: <b>" + val_num_occupied + "%<b>";
            str_tooltip += "<br>Vacant: <b>" + val_num_vacant + "%<b>";
            return str_tooltip;
        },
        style: {
            fontFamily: chart_fontFamily,
            fontSize: font_size_detail_tool_tip,
            fontWeight: chart_fontWeight,
        },
    },
    series: [{
            size:chart_size_housing,
            name: '',
            data: dataArrayHousing,
        }
    ],
    exporting: {
        enabled: false
    }
};

var categories_over_age=[];
var arrDataAge = [];
var max_over_age = 0;
var para_over_age={
    credits: {
        enabled: false
    },
    chart: {
        renderTo:'chart-4',
        type: 'column',
        style: {
            fontFamily: chart_fontFamily,
            fontSize: chart_fontSize,
            fontWeight: chart_fontWeight,
            color:color,
        },
        marginBottom: margin_bottom_age,
        marginTop:margin_top_age,
        marginRight:margin_right_age,
        marginLeft:margin_left_age,
        spacingLeft: spacing_left_age,
        spacingBottom: spacing_bottom_age,
        reflow:false,
    },
    title: {
        text: '',
        style: {
            color:color,
            fontFace: 'Ubuntu',
            fontSize: "18px",
            fontWeight: 'normal'
        },
        align: 'left',
    },
    lang: {
        noData: "No residents in selected area."
    },
    noData: {
        style: {
            fontWeight: 'normal',
            fontSize: '12pt',
            fontFamily: chart_fontFamily,
            color: color
        }
    },
    xAxis: {
        categories: categories_over_age,
        //max:max,
        min:0,
        labels: {
            style: {
                fontSize: overview_axisData,
                color:color,
                fontWeight: chart_fontWeight,
            },
            rotation: -45,
        },
        title: {
            text: 'AGE RANGE',
            offset: offset_x_title_age,
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
        offset: -8, // (Thuan Apr 1)
        max:max_over_age,
        min:0,
        useHTML: true,
        title: {
            text: 'TOTAL POPULATION',
            align: 'middle', //middle
            offset: offset_y_title_age,
            style:{
                color:color,
                fontSize: overview_axisLable,
                fontWeight: chart_fontWeight,
            },

        },
        stackLabels: {
            enabled: false,
            style: {
                fontWeight: 'normal',
                color:color,
            }
        },
        labels: {
            style:{
                color:color,
                fontSize: overview_axisData,
                fontWeight: chart_fontWeight,
            },
            formatter: function() {
                if(this.value>0){
                    return shortenBigNumber2(this.value,1);
                }else{
                    return this.value;
                }
            }
        },
        gridLineWidth: 0,
        gridLineColor: '#ffffff'
    },
    plotOptions: {
        column: {
            stacking: 'normal',
            borderWidth:0,
            dataLabels: {
                enabled: true,
                color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
                style: {
                    pointPadding: 0,
                    textShadow: 'none',
                    color:color,
                }
            }
        },
        series: {
            // pointWidth: 20,//width of the column bars irrespective of the chart size
            dataLabels: {
                enabled: false
            },
            states: {
                hover: {
                    enabled: false
                }
            },
            point: {
                events: {
                    mouseOver: function() {
                        this.options.oldColor = this.color;
                        var cKey = this.series.name.replace(/ /g, "").toLowerCase();
                        if(typeof(cKey)!='undefined'){
                            this.graphic.attr({
                                fill: fontAgeCensus[cKey][1]

                            });
                        }
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
            fontSize: overview_Legend,
            font: 'Ubuntu',
            color:color,
            fontWeight: 'normal'
        },
        symbolHeight: 6,
        symbolWidth: 8,
        itemMarginBottom: 5,
        labelFormatter: function() {
            return this.name.toUpperCase();
        },
        y:y_legend_age,
        x:x_legend_age,
        align: align_legend,
        verticalAlign: vertical_align_legend,
        layout: layout_legend,

    },
    tooltip: {
        formatter: function() {
            return 'Age: ' + this.x + '<br/><span style="color: ' + this.series.color + '">' + this.series.name + ': </span><b>' + Highcharts.numberFormat(this.y, 0, '.', ',') + '</b><br/>' +
                    'Total: <b>' + Highcharts.numberFormat(this.point.stackTotal, 0, '.', ',') + '</b>';
        },
        style: {
            fontFamily: chart_fontFamily,
            fontSize: font_size_tool_tip,
            fontWeight: chart_fontWeight,
        },
    },
    series: arrDataAge,
    exporting: {
        enabled: false
    }
};
var para_detail_age={
    credits: {
        enabled: false
    },
    chart: {
        renderTo:'chart-detail_3',
        type: 'column',
        style: {
            fontFamily: chart_fontFamily,
            fontSize: chart_detail_fontSize,
            fontWeight: chart_fontWeight,
            color:color,
        },
        spacingLeft: spacing_left_detail_age,
        spacingBottom: spacing_bottom_detail_age,
        marginLeft:margin_left_detail_age,
        marginRight:margin_right_detail_age,
        marginBottom: margin_bottom_detail_age,
        marginTop:margin_top_detail_age,
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
        categories: categories_over_age,
        //max:max,
        min:0,
        labels: {
            style: {
                fontSize: detail_axisData,
                color:color,
                fontWeight: chart_fontWeight,
            },
            y:y_label_detail_age
        },
        title: {
            text: 'AGE RANGE',
            offset: offset_x_title_detail_age,
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
        title: {
            text: 'TOTAL POPULATION',
            align: 'middle',
            style:{
                color:color,
                fontSize: detail_axisLable,
                fontWeight: chart_fontWeight,
            },
            offset:offset_y_title_detail_age
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
                color:color,
                fontSize: detail_axisData,
            },
            formatter: function() {
                if(this.value>0){
                    return shortenBigNumber2(this.value,1);
                }else{
                    return this.value;
                }
            }
        },
        lineWidth: 0,
    },
    plotOptions: {
        column: {
            stacking: 'normal',
            borderWidth:0,
            dataLabels: {
                enabled: true,
                color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
                style: {
                    pointPadding: 0,
                    textShadow: 'none',
                    fontWeight: 'normal',
                    fontSize: detail_callout,
                    fontFamily: 'Ubuntu',
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
            }
        },
        series: {
            pointWidth: point_width_age, //width of the column bars irrespective of the chart size
            dataLabels: {
                enabled: true,
            },
            states: {
                hover: {
                    enabled: false
                }
            },
            point: {
                events: {
                    mouseOver: function() {
                        this.options.oldColor = this.color;
                        var cKey = this.series.name.replace(/ /g, "").toLowerCase();
                        this.graphic.attr({
                            fill: fontAgeCensus[cKey][1]

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
        symbolPadding: 6,//6
        itemMarginTop: 10,
        itemMarginBottom: 0,//0
        labelFormatter: function() {
            return this.name.toUpperCase();
        },
        align: align_legend,
        verticalAlign: vertical_align_legend,
        layout: layout_legend,
        y:y_legend_detail_age,
        x:x_legend_detail_age
    },
    tooltip: {
        formatter: function() {
            return 'Age: ' + this.x + '<br/><span style="color: ' + this.series.color + '">' + this.series.name + ': </span><b>' + Highcharts.numberFormat(this.y, 0, '.', ',') + '</b><br/>' +
                    'Total: <b>' + Highcharts.numberFormat(this.point.stackTotal, 0, '.', ',') + '</b>';
        },
        style: {
            fontFamily: chart_fontFamily,
            fontSize: font_size_detail_tool_tip,
            fontWeight: chart_fontWeight,
        },
    },
    series: arrDataAge,
    exporting: {
        enabled: false
    }
};

var categories_over_income=[];
var arrDataIncome = [];
var max_over_income = 0;
var sandiegoLabelIncome = '';
var locationLabelIncome = '';
var para_over_income = {
    credits: {
        enabled: false
    },
    chart: {
        renderTo:'chart-5',
        type: 'column',
        style: {
            fontFamily: chart_fontFamily,
            fontSize: chart_fontSize,
            fontWeight: chart_fontWeight,
            color:color,
        },
        marginBottom: margin_bottom_income,
        marginTop:margin_top_income,
        marginRight:margin_right_income,
        marginLeft:margin_left_income,
        spacingLeft: spacing_left_income,
        spacingBottom: spacing_bottom_income,
        reflow:false,
    },
    title: {
        text: ''
    },
    lang: {
        noData: "No households in selected area."
    },
    noData: {
        style: {
            fontWeight: 'normal',
            fontSize: '12pt',
            fontFamily: chart_fontFamily,
            color: color
        }
    },
    xAxis: {
        categories: categories_over_income,
        useHTML: true,
        labels: {
            style: {
                fontSize: overview_axisData,
                color:color,
                lineHeight: '10%', //(Thuan Apr 2) added this to control spacing between lines
            },
            formatter: function() {
				var window_width = $(window).width();
                if (window_width <1024) {
                     return this.value.toUpperCase();
                }else{
                    return this.value.toUpperCase().replace('-', '-<br/>');
                }
            },
        },
        title: {
            text: 'INCOME',
            offset: offset_x_title_income,
            style:{
                color:color,
                fontSize: overview_axisLable,
            }
        },
        lineWidth: 0,
        tickLength: 0,
        minorTickLength: 0
    },
    yAxis: {
        offset: -8, // (Thuan Apr 1)
        minRange: 10,
        tickInterval: 10,
        min:0,
        max:max_over_income,
        title: {
            text: 'TOTAL HOUSEHOLDS',
            align: 'middle',
            style:{
                color:color,
                fontSize: overview_axisLable,
            },
            offset: offset_y_title_income,
            y:y_title_income,
        },
        labels: {
            style: {
                fontSize: overview_axisData,
                color:color,
            },
            formatter: function() {
                return (this.value > 0) ? Math.round(this.value) + '%' : 0;
            }

        },
        gridLineWidth: 0,
        gridLineColor: '#ffffff'
    },
    legend: {
        itemStyle: {
            fontSize: overview_Legend,
            font: 'Ubuntu',
            fontWeight: 'normal',
            color:color,
            lineHeight: '11%', //(Thuan Apr 1) added this to control spacing between lines
        },
        symbolHeight: 6,
        symbolWidth: 8,
        itemMarginTop: 5,
        itemMarginBottom: 0,
        labelFormatter: function() {
            var str=this.name;
            str = str.toUpperCase();
            var window_width = $(window).width();
            if (window_width <1024) {
            }else{
                while(str.indexOf(" ")> -1){
                    str = str.replace(" ","<br>");
                }
                if(str.indexOf("-")> -1){
                    str = str.replace("-","<br>");
                }
            }
            return str;//this.name.toUpperCase();
        },
        align: align_legend,
        verticalAlign: vertical_align_legend,
        layout: layout_legend,
        x:x_legend_income,
        y:y_legend_income
    },
    tooltip: {
        formatter: function() {
            var data_Point = this.series.options.data;
            var data_Value = this.series.options.data_value;
            var index = categories_over_income.indexOf(this.point.x);
            var house_hold = Highcharts.numberFormat(data_Value[this.point.x], 0, '.', ',');

            var str = '<span">'+this.point.category+'</span><table>'
            str += '<tr><td style="text-transform:uppercase;"><span style="color:'+this.series.color+';text-transform:capitalize;">'+this.series.name.toLowerCase()+':</span> ';
            str += '<b>'+this.point.y+'%</b></td></tr>';
            str += '<tr><td>Households: <b>'+house_hold+'</b></td></tr>';
            str +='</table>'
            return str;
        },
        style: {
            fontFamily: chart_fontFamily,
            fontSize: font_size_tool_tip,
            fontWeight: chart_fontWeight,
        },
        useHTML: true
    },
    plotOptions: {
        column: {
            pointPadding: 0.1,
            groupPadding: 0.1,
            borderWidth: 0,
        },
        series: {
            //pointWidth: point_width_income,
            groupPadding: group_padding_income,
            states: {
                hover: {
                    enabled: false
                }
            },
            point: {
                events: {
                    mouseOver: function() {
                        this.options.oldColor = this.color;
                        var str_color = '';

                        if (sandiegoLabelIncome == this.series.name) {
                            str_color = fontIncomeCensus['sandiego'][1];
                        } else {
                            str_color = fontIncomeCensus['location'][1];
                        }

                        this.graphic.attr({
                            fill: str_color

                        });
                    },
                    mouseOut: function() {
                        this.graphic.attr("fill", this.options.oldColor);
                    }
                }
            }

        }
    },
    series: arrDataIncome,
    exporting: {
        enabled: false
    }
};
var para_detail_income={
    credits: {
        enabled: false
    },
    chart: {
        renderTo:'chart-detail_4',
        type: 'column',
        width: 0,
        style: {
            fontFamily: chart_fontFamily,
            fontSize: chart_fontSize,
            fontWeight: chart_fontWeight,
            color:color,
        },
        spacingLeft: spacing_left_detail_income,
        spacingBottom: spacing_bottom_detail_income,
        marginLeft:margin_left_detail_income,
        marginRight:margin_right_detail_income,
        marginBottom: margin_bottom_detail_income,
        marginTop:margin_top_detail_income,
        reflow:false,
        //reflow: true
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
    subtitle: {
        text: ''
    },
    xAxis: {
        categories: categories_over_income,
        useHTML: true,
        labels: {
            style: {
                fontSize: detail_axisData,
                color:color,
                fontWeight: 'normal'
            },
            formatter: function() {
				var window_width = $(window).width();
                if (window_width <768) {
                    return this.value.toUpperCase();
                }else{
                    if(typeof(this.value)=='string'){
                        return this.value.toUpperCase().replace("-","-<br>");
                    }
                    return '';
                }
            },
            y:y_label_detail_income,
        },
        title: {
            text: 'INCOME',
            offset: offset_x_title_detail_income,
            style:{
                fontSize: detail_axisLable,
                color:color,
                fontWeight: 'normal'
            },
            //margin:margin_title
        },
        lineWidth: 0,
        tickLength: 0,
        minorTickLength: 0
    },
    yAxis: {
        offset: -8, // (Thuan Apr 1)
        minRange: 10,
        tickInterval: 10,
        title: {
            text: 'TOTAL HOUSEHOLDS',
            align: 'middle',
            style:{
                color:color,
                fontSize: detail_axisLable,
                fontWeight: 'normal'
            },
            //margin:margin_title
            offset:offset_y_title_detail_income
        },
        labels: {
            formatter: function() {
                return (this.value > 0) ? Math.round(this.value) + '%' : 0;
            },
            style: {
                color:color,
                fontSize: detail_axisData,
            },

        }

    },
    legend: {
        itemStyle: {
            fontSize: detail_legend,
            font: 'Ubuntu',
            color:color,
            fontWeight: 'normal',
        },
        itemWidth:197,
        symbolHeight: detail_symbol_height,
        symbolWidth: detail_symbol_width,
        symbolPadding: 6,
        itemMarginTop: 5,
        itemMarginBottom: 0,
        labelFormatter: function() {
            var str=this.name;
            str = str.toUpperCase();
            var window_width = $(window).width();
            if (window_width <1024) {
            }else{
                while(str.indexOf(" ")> -1){
                    str = str.replace(" ","<br>");
                }
                if(str.indexOf("-")> -1){
                    str = str.replace("-","<br>");
                }
            }
            return str;//this.name.toUpperCase();
        },
        align: align_legend,
        verticalAlign: vertical_align_legend,
        layout: layout_legend,
        y:y_legend_detail_income,
        x:x_legend_detail_income
    },
    tooltip: {
        formatter: function() {
            var data_Point = this.series.options.data;
            var data_Value = this.series.options.data_value;
            var index = data_Point.indexOf(this.point.y);
            var house_hold = Highcharts.numberFormat(data_Value[this.point.x], 0, '.', ',');

            var str = '<span">'+this.point.category+'</span><table>'
            str += '<tr><td style="text-transform:uppercase;"><span style="color:'+this.series.color+';text-transform:capitalize;">'+this.series.name.toLowerCase()+':</span> ';
            str += '<b>'+this.point.y+'%</b></td></tr>';
            str += '<tr><td>Households: <b>'+house_hold+'</b></td></tr>';
            str +='</table>'
            return str;
        },
        style: {
            fontFamily: chart_fontFamily,
            fontSize: font_size_tool_tip,
            fontWeight: chart_fontWeight,
        },
        useHTML: true
    },
    plotOptions: {
        series: {
            pointWidth: point_width_detail_income,
            //groupPadding: 0.5,
            states: {
                hover: {
                    enabled: false
                }
            },
            point: {
                events: {
                    mouseOver: function() {
                        this.options.oldColor = this.color;
                        var str_color = '';
                        if (sandiegoLabelIncome == this.series.name) {
                            str_color = fontIncomeCensus['sandiego'][1];
                        } else {
                            str_color = fontIncomeCensus['location'][1];
                        }

                        this.graphic.attr({
                            fill: str_color

                        });
                    },
                    mouseOut: function() {
                        this.graphic.attr("fill", this.options.oldColor);
                    }
                }
            }

        }
    },
    series: arrDataIncome,
    exporting: {
        enabled: false
    }
}

