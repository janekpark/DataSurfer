var api_port=window.location.port;
var api_url ="";
if(api_port >0){
    api_url = window.location.protocol +'//' + window.location.hostname+ ':'+api_port
}else{
    api_url = window.location.protocol + '//' + window.location.hostname
}
api_url += "/services.php";

$(function() {
    Highcharts.setOptions({
        lang: {
            decimalPoint: '.',
            thousandsSep: ','
        }
    });
});
function drawForecastChart(type){
    if(type=="ethnicity"){
        var chart_over_force_ethnicity = new Highcharts.Chart(para_over_forecast_ethnicity,function(objChart){});
    }else if(type=="housing"){
        var chart_over_force_housing = new Highcharts.Chart(para_over_forecast_housing,function(objChart){});
    }else if(type=="job"){
        var chart_over_force_job = new Highcharts.Chart(para_over_forecast_job,function(objChart){});
    }else if(type=="income"){
        var chart_over_force_income = new Highcharts.Chart(para_over_forecast_income,function(objChart){});
    } 
}
function loadChart()
{
    $('#chart-1').addClass("chart-frame-402");
    $('#chart-detail').addClass("chart-frame-402");
    $('#chart-2').addClass("chart-frame-380");
    $('#chart-detail_2').addClass("chart-frame-380");
    
    $('#chart-4').addClass("chart-frame-402");
    $('#chart-detail_3').addClass("chart-frame-402");
    $('#chart-5').addClass("chart-frame-423");
    $('#chart-detail_4').addClass("chart-frame-423");
    
    $.ajax({
        url: api_url,
        type: "POST",
        dataType: "json",
        data: {type_chart: "ethnicity", url_des: $('#url_ethnicity').val()},
        success: function(res) {
            if (res.length > 0) {
                var categories = [];
                var arrData = [];
                var arrYear = [];
                res.sort(function(a, b) {
                    return a.year - b.year;
                });
                var maxVal = 0;
                for (var i = 0; i < res.length; i++) {
                    var obj = res[i];
                    if ('ethnicity' in obj && !isInArray(obj.ethnicity, categories) && obj.ethnicity.indexOf('Total') < 0) {
                        categories.push(obj.ethnicity);
                    }
                    if(obj.ethnicity=='Total Population'){
                        if(obj.population > maxVal){
                            maxVal = obj.population;
                        }
                    }
                }
                

                for (var j = 0; j < categories.length; j++) {
                    var arr = [];
                    for (var i = 0; i < res.length; i++) {
                        var obj = res[i];
                        if (obj.ethnicity == categories[j]) {
                            arr.push(obj.population)
                        }

                        if ('ethnicity' in obj && obj.ethnicity == "Total Population") {
                            arrYear.push(obj.year);
                        }
                    }
                    var cKey = categories[j].replace(/ /g, "").toLowerCase();
                    var str_name=categories[j]
                    if(str_name=="Two or More"){
                        str_name="2 or More";
                    }
                    arrData.push({name: str_name, data: arr, color: colorEthnicityForecast[cKey][0]});
                }
               
                arrData.sort(sortByProperty('name'));
                var arrSum = [];
                var temp = [];
                
                for(var i=0;i<arrData.length;i++){
                    var obj = {};
                    var yearSum = 0;
                    for(var x=0;x<arrData.length;x++){
                        yearSum+=arrData[x].data[i];
                    }
                    temp.push(parseInt(arrYear[i], 10));
                    arrSum.push(yearSum);
                }
                cYear=temp;
                arrDataSumForecastEthnicity = arrSum;
                console.log(cYear);
                console.log(arrDataSumForecastEthnicity);
                arrYearForecastEthnicity = arrYear;
                arrDataForecastEthnicity = arrData;
                para_over_forecast_ethnicity.series = arrDataForecastEthnicity;
                para_over_forecast_ethnicity.xAxis.categories = arrYearForecastEthnicity;
                
                para_over_forecast_ethnicity.legend.itemStyle.fontSize = overview_Legend;
                para_over_forecast_ethnicity.legend.layout = layout_legend;
                para_over_forecast_ethnicity.legend.align = align_legend;
                para_over_forecast_ethnicity.legend.verticalAlign = vertical_align_legend;
                para_over_forecast_ethnicity.legend.y = y_legend_forcast_ethnicity;
                para_over_forecast_ethnicity.legend.x = x_legend_forcast_ethnicity;
                
                para_over_forecast_ethnicity.chart.style.fontFamily=chart_fontFamily;
                para_over_forecast_ethnicity.chart.style.fontSize=chart_fontSize;
                para_over_forecast_ethnicity.chart.style.fontWeight=chart_fontWeight;
                para_over_forecast_ethnicity.chart.spacingLeft = spacing_left_forcast_ethnicity;
                para_over_forecast_ethnicity.chart.spacingBottom = spacing_bottom_forcast_ethnicity;
                para_over_forecast_ethnicity.chart.marginLeft = margin_left_forcast_ethnicity;
                para_over_forecast_ethnicity.chart.marginRight = margin_right_forcast_ethnicity;
                para_over_forecast_ethnicity.chart.marginBottom = margin_bottom_forcast_ethnicity;
                para_over_forecast_ethnicity.chart.marginTop = margin_top_forcast_ethnicity;
                
                para_over_forecast_ethnicity.xAxis.title.offset=offset_x_title_forcast_ethnicity;
                para_over_forecast_ethnicity.xAxis.title.style.fontSize=overview_axisLable;
                para_over_forecast_ethnicity.xAxis.labels.style.fontSize=overview_axisData;
                para_over_forecast_ethnicity.yAxis.title.style.fontSize=overview_axisLable;
                para_over_forecast_ethnicity.yAxis.title.offset=offset_y_title_forcast_ethnicity;
                para_over_forecast_ethnicity.yAxis.labels.style.fontSize=overview_axisData;
                para_over_forecast_ethnicity.tooltip.style.fontSize=font_size_tool_tip;
                var window_width = $(window).width();
                var indexofyear=cYear.indexOf('{point.key}');
                para_over_forecast_ethnicity.tooltip.footerFormat = 'Total: '+cYear.indexOf(' + {point.key} + ');
                if(window_width >=1024){
                    para_over_forecast_ethnicity.tooltip.pointFormat = '<span style="color:{series.color};font-size:9px;margin-bottom:-10px;">● </span>{series.name}: <b>{point.y}</b><br>';
                }else{
                    var keyToDelete = "pointFormat";
                    delete   para_over_forecast_ethnicity.tooltip[keyToDelete];
                }
                
                chart2();
                
                // updateLegendBox(chart1);
                var width = $(".chart-list").width();
                max_detail_forecast_ethnicity=maxVal;
                para_detail_forecast_ethnicity.series = arrDataForecastEthnicity;
                para_detail_forecast_ethnicity.xAxis.categories = arrYearForecastEthnicity;
                para_detail_forecast_ethnicity.chart.width = width;
                para_detail_forecast_ethnicity.yAxis.max = max_detail_forecast_ethnicity;
                
                para_detail_forecast_ethnicity.legend.symbolHeight = detail_symbol_height;
                para_detail_forecast_ethnicity.legend.symbolWidth = detail_symbol_width;
                para_detail_forecast_ethnicity.legend.itemMarginTop = item_margin_top_detail_forcast_ethnicity;
                para_detail_forecast_ethnicity.legend.itemMarginBottom = item_margin_bottom_detail_forcast_ethnicity;
                para_detail_forecast_ethnicity.legend.itemStyle.fontSize = detail_legend;
                para_detail_forecast_ethnicity.legend.layout = layout_legend;
                para_detail_forecast_ethnicity.legend.align = align_legend;
                para_detail_forecast_ethnicity.legend.verticalAlign = vertical_align_legend;
                para_detail_forecast_ethnicity.legend.y = y_legend_detail_forcast_ethnicity;
                para_detail_forecast_ethnicity.legend.x = x_legend_detail_forcast_ethnicity;
                
                para_detail_forecast_ethnicity.chart.style.fontFamily=chart_fontFamily;
                para_detail_forecast_ethnicity.chart.style.fontSize=chart_fontSize;
                para_detail_forecast_ethnicity.chart.style.fontWeight=chart_fontWeight;
                
                para_detail_forecast_ethnicity.chart.spacingLeft = spacing_left_detail_forcast_ethnicity;
                para_detail_forecast_ethnicity.chart.spacingBottom = spacing_bottom_detail_forcast_ethnicity;
                para_detail_forecast_ethnicity.chart.marginLeft = margin_left_detail_forcast_ethnicity;
                para_detail_forecast_ethnicity.chart.marginRight = margin_right_detail_forcast_ethnicity;
                para_detail_forecast_ethnicity.chart.marginBottom = margin_bottom_detail_forcast_ethnicity;
                para_detail_forecast_ethnicity.chart.marginTop = margin_top_detail_forcast_ethnicity;
                
                para_detail_forecast_ethnicity.xAxis.title.offset=offset_x_title_detail_forcast_ethnicity;
                para_detail_forecast_ethnicity.xAxis.title.style.fontSize=detail_axisLable;
                para_detail_forecast_ethnicity.xAxis.labels.style.fontSize=detail_axisData;
                para_detail_forecast_ethnicity.yAxis.title.style.fontSize=detail_axisLable;
                para_detail_forecast_ethnicity.yAxis.title.offset=offset_y_title_detail_forcast_ethnicity;
                para_detail_forecast_ethnicity.yAxis.labels.style.fontSize=detail_axisData;
                para_detail_forecast_ethnicity.tooltip.style.fontSize=font_size_detail_tool_tip;
                /*var chart_detail_force_ethnicity = new Highcharts.Chart(para_detail_forecast_ethnicity,function(objChart){});
                createMenu('detail');*/
            }
        },
        error: function(request, status, error) {
            console.log($('#url_ethnicity').val());
            console.log(request.responseText);
        }


    });

}

function chart2()
{
    $.ajax({
        url: api_url,
        type: "POST",
        dataType: "json",
        data: {type_chart: "housing", url_des: $('#url_housing').val()},
        success: function(res) {
            if (res.length > 0) {
                var categories = [];
                var arrData = [];
                var arrYear = [];
                res.sort(function(a, b) {
                    return a.year - b.year;
                });

           
                for (var i = 0; i < res.length; i++) {
                    var obj = res[i];
                    if ('unit_type' in obj && !isInArray(obj.unit_type, categories) && obj.unit_type.indexOf('Total') < 0) {
                        categories.push(obj.unit_type);
                    }
                }
                categories.sort(function(a, b) {
                    return a.unit_type - b.unit_type;
                });
                
                var maxVal = 0;
                for (var j = 0; j < categories.length; j++) {
                    var arr = [];
                    for (var i = 0; i < res.length; i++) {
                        var obj = res[i];
                        if (obj.unit_type == categories[j]) {
                            arr.push(obj.units);
                        }

                        if ('unit_type' in obj &&!isInArray(obj.year, arrYear) && obj.unit_type == "Total Units") {
                            arrYear.push(obj.year);
                            if(obj.units > maxVal){
                                maxVal = obj.units;
                            }
                        }
                    }
                    var cKey = categories[j].replace(/ |-/g, "").toLowerCase();
                    arrData.push({name: categories[j], data: arr, color: colorHousingTypeForecast[cKey][0]});
                }

                arrData.sort(sortByProperty('name'));
                arrYearForecastHousing = arrYear;
                arrDataForecastHousing = arrData;
                max_detail_forecast_housing = maxVal;
                para_over_forecast_housing.series = arrDataForecastHousing;
                para_over_forecast_housing.xAxis.categories = arrYearForecastHousing;
                para_over_forecast_housing.yAxis.max = max_detail_forecast_housing;
                
                para_over_forecast_housing.legend.itemStyle.fontSize = overview_Legend;
                para_over_forecast_housing.legend.layout = layout_legend;
                para_over_forecast_housing.legend.align = align_legend;
                para_over_forecast_housing.legend.verticalAlign = vertical_align_legend;
                para_over_forecast_housing.legend.y = y_legend_forcast_housing;
                para_over_forecast_housing.legend.x = x_legend_forcast_housing;
                
                para_over_forecast_housing.chart.style.fontFamily=chart_fontFamily;
                para_over_forecast_housing.chart.style.fontSize=chart_fontSize;
                para_over_forecast_housing.chart.style.fontWeight=chart_fontWeight;
                
                para_over_forecast_housing.chart.spacingLeft = spacing_left_forcast_housing;
                para_over_forecast_housing.chart.spacingBottom = spacing_bottom_forcast_housing;
                para_over_forecast_housing.chart.marginLeft = margin_left_forcast_housing;
                para_over_forecast_housing.chart.marginRight = margin_right_forcast_housing;
                para_over_forecast_housing.chart.marginBottom = margin_bottom_forcast_housing;
                para_over_forecast_housing.chart.marginTop = margin_top_forcast_housing;
                
                para_over_forecast_housing.xAxis.title.style.fontSize=overview_axisLable;
                para_over_forecast_housing.xAxis.labels.style.fontSize=overview_axisData;
                para_over_forecast_housing.yAxis.title.offset=offset_y_title_forcast_housing;
                para_over_forecast_housing.xAxis.title.offset=offset_x_title_forcast_housing;
                para_over_forecast_housing.yAxis.labels.style.fontSize=overview_axisData;
                para_over_forecast_housing.yAxis.title.style.fontSize=overview_axisLable;
                if(checkInternetExplorer()){
                    var keyToDelete = "lineHeight";
                    delete  para_over_forecast_housing.legend.itemStyle[keyToDelete];
                }else{
                    para_over_forecast_housing.legend.itemStyle.lineHeight='11%'
                }
                para_over_forecast_housing.tooltip.style.fontSize=font_size_tool_tip;
                chart4();
                
                var width = $(".chart-list").width();
                para_detail_forecast_housing.chart.width = width;
                para_detail_forecast_housing.series = arrDataForecastHousing;
                para_detail_forecast_housing.xAxis.categories = arrYearForecastHousing;
                para_detail_forecast_housing.yAxis.max = max_detail_forecast_housing;
                
                para_detail_forecast_housing.legend.symbolHeight = detail_symbol_height;
                para_detail_forecast_housing.legend.symbolWidth = detail_symbol_width;
                para_detail_forecast_housing.legend.itemMarginTop = item_margin_top_detail_focast_housing;
                para_detail_forecast_housing.legend.itemMarginBottom = item_margin_bottom_detail_focast_housing;
                para_detail_forecast_housing.legend.itemStyle.fontSize = detail_legend;
                para_detail_forecast_housing.legend.layout = layout_legend;
                para_detail_forecast_housing.legend.align = align_legend;
                para_detail_forecast_housing.legend.verticalAlign = vertical_align_legend;
                para_detail_forecast_housing.legend.y = y_legend_detail_forcast_housing;
                para_detail_forecast_housing.legend.x = x_legend_detail_forcast_housing;
                
                para_detail_forecast_housing.chart.style.fontFamily = chart_fontFamily;
                para_detail_forecast_housing.chart.style.fontSize = chart_fontSize;
                para_detail_forecast_housing.chart.style.fontWeight = chart_fontWeight;
                
                para_detail_forecast_housing.chart.spacingLeft = spacing_left_detail_forcast_housing;
                para_detail_forecast_housing.chart.spacingBottom = spacing_bottom_detail_forcast_housing;
                para_detail_forecast_housing.chart.marginLeft = margin_left_detail_forcast_housing;
                para_detail_forecast_housing.chart.marginRight = margin_right_detail_forcast_housing;
                para_detail_forecast_housing.chart.marginBottom = margin_bottom_detail_forcast_housing;
                para_detail_forecast_housing.chart.marginTop = margin_top_detail_forcast_housing;
                
                para_detail_forecast_housing.xAxis.title.style.fontSize = detail_axisLable;
                para_detail_forecast_housing.xAxis.labels.style.fontSize = detail_axisData;
                para_detail_forecast_housing.yAxis.title.offset = offset_y_title_detail_forcast_housing;
                para_detail_forecast_housing.xAxis.title.offset = offset_x_title_detail_forcast_housing;
                para_detail_forecast_housing.yAxis.labels.style.fontSize = detail_axisData;
                para_detail_forecast_housing.yAxis.title.style.fontSize = detail_axisLable;
                var width = $(".chart-list").width();
                var window_width = $(window).width();
                if(window_width >=768){
                    para_detail_forecast_housing.plotOptions.series.pointWidth = '45';
                }else{
                    var keyToDelete = "pointWidth";
                    delete  para_detail_forecast_housing.plotOptions.series[keyToDelete];
                }
                para_detail_forecast_housing.tooltip.style.fontSize=font_size_detail_tool_tip;
                /*var chart_over_force_housing = new Highcharts.Chart(para_detail_forecast_housing,function(objChart){});
                createMenu('detail_2');*/
            }
        },
        error: function(request, status, error) {
            console.log($('#url_housing').val());
            console.log(request.responseText);
        }
    });
}


function chart4()
{
    $.ajax({
        url: api_url,
        type: "POST",
        dataType: "json",
        data: {type_chart: "jobs", url_des: $('#url_jobs').val()},
        success: function(res) {
            if (res.length > 0) {
                var categories = [];
                var arr_year = [];
                var colors = [];
                var arrData = [];
                var arrTmp = [];
                var maxVal = 0;
                for (var i = 0; i < res.length; i++) {
                    var obj = res[i];
                    if ('category' in obj && !isInArray(obj.category, categories) && obj.category.indexOf('Total') < 0) {
                        categories.push(obj.category);
                    }
                    if(obj.category.indexOf('Total')>-1){
                        if(obj.jobs > maxVal){
                            maxVal = obj.jobs;
                        }
                    }
                }
                
                res.sort(function(a, b) {
                    return a.category - b.category;
                }
                );

                for (var j = 0; j < categories.length; j++) {
                    var arr = [];

                    for (var i = 0; i < res.length; i++) {
                        var obj = res[i];
                        obj.other = "0";
                        if (obj.category == categories[j]) {
                            if (categories[j] == "Professional and Business Services"
                                    || categories[j] == "Education and Healthcare"
                                    || categories[j] == "Government"
                                    || categories[j] == "Liesure and Hospitality") {
                                arr.push(obj)
                            } else {
                                obj.other = "1";
                                arr.push(obj)
                            }
                        }
                    }

                    var ser_data = [];
                    arr.sort(function(a, b) {
                        return a.year - b.year;
                    }
                    );

                    for (var ind = 0; ind < arr.length; ind++) {
                        var objArr = arr[ind];
                        ser_data.push(objArr.jobs);
                        if ('year' in objArr && !isInArray(objArr.year, arr_year)) {
                            arr_year.push(objArr.year);
                        }
                    }
                    if (categories[j] == "Professional and Business Services") {
                        arrData.push({name: categories[j], data: ser_data});
                    } else if (categories[j] == "Education and Healthcare") {
                        arrData.push({name: categories[j], data: ser_data});
                    } else if (categories[j] == "Government") {
                        arrData.push({name: categories[j], data: ser_data});
                    } else if (categories[j] == "Liesure and Hospitality") {
                        arrData.push({name: categories[j], data: ser_data});
                    } else {
                        arrData.push({name: categories[j], data: ser_data});
                    }
                }

                var relData = [];
                var arrOther = [];
                for (var i = 0; i < arrData.length; i++) {
                    var obj = arrData[i];
                    if (obj.name == "Professional and Business Services"
                            || obj.name == "Education and Healthcare"
                            || obj.name == "Government"
                            || obj.name == "Liesure and Hospitality") {
                        if (obj.name == "Professional and Business Services") {
                            obj.name = "Business Services";
                        } else if (obj.name == "Education and Healthcare") {
                            obj.name = "Education & Health";
                        }
                        if (obj.name == "Liesure and Hospitality") {
                            obj.name = "Leisure & Hospitality";
                        }
                        var cKey = obj.name.replace(/ |&/g, "").toLowerCase();
                        
                        obj.color = fontJobForecast[cKey][0];
                        relData.push(obj);
                    } else {
                        var tmpDataOther = obj.data;
                        for (var j = 0; j < tmpDataOther.length; j++) {
                            if (i == 0) {
                                arrOther.push(tmpDataOther[j]);
                            } else {
                                arrOther[j] = arrOther[j] + tmpDataOther[j]
                            }
                        }
                    }

                }

                relData.push({name: "Other", data: arrOther, color: fontJobForecast['other'][0]})
                relData.sort(newSortByProperty('name'));
                
                for(var i=0;i<relData.length;i++){
                    var obj = relData[i];
                    obj.zIndex = i+1;
                }
                console.log(relData);
                arrYearForecastJob = arr_year;
                arrDataForecastJob = relData;
                para_over_forecast_job.series = arrDataForecastJob;
                para_over_forecast_job.xAxis.categories = arrYearForecastJob;
                
                para_over_forecast_job.legend.itemStyle.fontSize = overview_Legend;
                para_over_forecast_job.legend.layout = layout_legend;
                para_over_forecast_job.legend.align = align_legend;
                para_over_forecast_job.legend.verticalAlign = vertical_align_legend;
                para_over_forecast_job.legend.y = y_legend_forcast_job;
                para_over_forecast_job.legend.x = x_legend_forcast_job;
                
                para_over_forecast_job.chart.style.fontFamily=chart_fontFamily;
                para_over_forecast_job.chart.style.fontSize=chart_fontSize;
                para_over_forecast_job.chart.style.fontWeight=chart_fontWeight;
                
                para_over_forecast_job.chart.spacingLeft = spacing_left_forcast_job;
                para_over_forecast_job.chart.spacingBottom = spacing_bottom_forcast_job;
                para_over_forecast_job.chart.marginLeft = margin_left_forcast_job;
                para_over_forecast_job.chart.marginRight = margin_right_forcast_job;
                para_over_forecast_job.chart.marginBottom = margin_bottom_forcast_job;
                para_over_forecast_job.chart.marginTop = margin_top_forcast_job;
                
                para_over_forecast_job.xAxis.title.style.fontSize=overview_axisLable;
                para_over_forecast_job.xAxis.labels.style.fontSize=overview_axisData;
                para_over_forecast_job.yAxis.title.offset=offset_y_title_forcast_job;
                para_over_forecast_job.xAxis.title.offset=offset_x_title_forcast_job;
                para_over_forecast_job.yAxis.labels.style.fontSize=overview_axisData;
                para_over_forecast_job.yAxis.title.style.fontSize=overview_axisLable;
                if(checkInternetExplorer()){
                    var keyToDelete = "lineHeight";
                    delete  para_over_forecast_job.legend.itemStyle[keyToDelete];
                }else{
                    para_over_forecast_job.legend.itemStyle.lineHeight='11%'
                }
                para_over_forecast_job.tooltip.style.fontSize=font_size_tool_tip;
                chart5();

                var width = $(".chart-list").width();
                para_detail_forecast_job.series = arrDataForecastJob;
                para_detail_forecast_job.xAxis.categories = arrYearForecastJob;
                max_detail_forecast_job = maxVal;
                para_detail_forecast_job.chart.width = width;
                para_detail_forecast_job.yAxis.max = max_detail_forecast_job;
                
                para_detail_forecast_job.legend.symbolHeight = detail_symbol_height;
                para_detail_forecast_job.legend.symbolWidth = detail_symbol_width;
                para_detail_forecast_job.legend.itemMarginTop = item_margin_top_detail_focast_job;
                para_detail_forecast_job.legend.itemMarginBottom = item_margin_bottom_detail_focast_job;
                para_detail_forecast_job.legend.itemStyle.fontSize = detail_legend;
                para_detail_forecast_job.legend.layout = layout_legend;
                para_detail_forecast_job.legend.align = align_legend;
                para_detail_forecast_job.legend.verticalAlign = vertical_align_legend;
                para_detail_forecast_job.legend.y = y_legend_detail_forcast_job;
                para_detail_forecast_job.legend.x = x_legend_detail_forcast_job;
                
                para_detail_forecast_job.chart.style.fontFamily = chart_fontFamily;
                para_detail_forecast_job.chart.style.fontSize = chart_fontSize;
                para_detail_forecast_job.chart.style.fontWeight = chart_fontWeight;
                
                para_detail_forecast_job.chart.spacingLeft = spacing_left_detail_forcast_job;
                para_detail_forecast_job.chart.spacingBottom = spacing_bottom_detail_forcast_job;
                para_detail_forecast_job.chart.marginLeft = margin_left_detail_forcast_job;
                para_detail_forecast_job.chart.marginRight = margin_right_detail_forcast_job;
                para_detail_forecast_job.chart.marginBottom = margin_bottom_detail_forcast_job;
                para_detail_forecast_job.chart.marginTop = margin_top_detail_forcast_job;
                
                para_detail_forecast_job.xAxis.title.style.fontSize = detail_axisLable;
                para_detail_forecast_job.xAxis.labels.style.fontSize = detail_axisData;
                para_detail_forecast_job.yAxis.title.offset = offset_y_title_detail_forcast_job;
                para_detail_forecast_job.yAxis.labels.style.fontSize = detail_axisData;
                para_detail_forecast_job.yAxis.title.style.fontSize = detail_axisLable;
                para_detail_forecast_job.tooltip.style.fontSize=font_size_detail_tool_tip;
                /*var chart_detail_force_job = new Highcharts.Chart(para_detail_forecast_job,function(objChart){});
                createMenu('detail_3');*/
            }
        },
        error: function(request, status, error) {
            console.log($('#url_housing').val());
            console.log(request.responseText);
        }
    });
}
function chart5()
{
    $.ajax({
        url: api_url,
        type: "POST",
        dataType: "json",
        data: {type_chart: "income", url_des: $('#url_income').val()},
        success: function(res) {
            if (res.length > 0) {
                var categories = [];
                var arr_year = [];
                var colors = [];
                var arrData = [];
                var arrTmp = [];
                for (var i = 0; i < res.length; i++) {
                    var obj = res[i];
                    if (obj.income_group != "") {
                        var str = obj.income_group;
                        while (obj.income_group.indexOf("$") >= 0) {
                            obj.income_group = obj.income_group.replace("$", "");
                        }
                        while (obj.income_group.indexOf(",") >= 0) {
                            obj.income_group = obj.income_group.replace(",", "");
                        }
                        if (obj.income_group.indexOf("Less than") >= 0) {
                            obj.income_group = obj.income_group.replace("Less than", "")
                        }
                        if (obj.income_group.indexOf("or more") >= 0) {
                            obj.income_group = obj.income_group.replace("or more", "")
                        }
                        if (obj.income_group.indexOf(" to ") >= 0) {
                            obj.income_group = obj.income_group.replace(" to ", ".")
                        }
                    }
                }

                res.sort(function(a, b) {
                    return a.year - b.year;
                }
                );
                for (var i = 0; i < res.length; i++) {
                    var obj = res[i];
                    if ('year' in obj && !isInArray(obj.year, arr_year)) {
                        arr_year.push(obj.year);
                    }
                }

                for (var j = 0; j < arr_year.length; j++) {
                    var arr = [];

                    for (var i = 0; i < res.length; i++) {
                        var obj = res[i];
                        if (obj.year == arr_year[j]) {
                            arr.push(obj)
                        }
                    }

                    arr.sort(function(a, b) {
                        return a.income_group - b.income_group;
                    }
                    );

                    var ser_data = [];

                    for (var ind = 0; ind < arr.length; ind++) {
                        var objArr = arr[ind];
                        if (!isInArray(objArr.income_group, categories)) {
                            var str_category = objArr.income_group
                            if (ind == 0) {
                                str_category = "&lt;$" + shortenBigNumber(str_category);
                            }
                            if (ind == (arr.length - 1)) {
                                var tmp = str_category / 1000
                                tmp = tmp.toString();
                                if (tmp.indexOf(".999") >= 0) {
                                    str_category = "≥$ " + tmp.replace(".999", ".9K");
                                } else {
                                    str_category = "≥$" + shortenBigNumber(str_category);
                                }
                            }
                            var str_spl = str_category.split(".");

                            if (str_spl.length > 1) {
                                var tmp = str_spl[1] / 1000
                                tmp = tmp.toString();
                                if (tmp.indexOf(".999") >= 0) {
                                    tmp = tmp.replace(".999", ".9K");
                                } else {
                                    tmp = shortenBigNumber(str_category);
                                }
                                str_category = "$" + shortenBigNumber(str_spl[0]) + "-$" + tmp
                            }
                            categories.push(str_category);
                        }
                        ser_data.push(objArr.households);
                    }

                    var str_color = '';
                    if (isInArray(arr_year[j].toString(), Object.keys(fontIncomeForecast))) {
                        str_color = fontIncomeForecast[arr_year[j]][0];
                    }
                    arrData.push({name: arr_year[j], data: ser_data, color: str_color});
                }
                var window_width = $(window).width();
                categories_forecast_income = categories;
                arrDataForecastIncome = arrData;
                para_over_forecast_income.series = arrDataForecastIncome;
                para_over_forecast_income.xAxis.categories = categories_forecast_income;
                
                para_over_forecast_income.legend.itemStyle.fontSize = overview_Legend;
                para_over_forecast_income.legend.layout = layout_legend;
                para_over_forecast_income.legend.align = align_legend;
                para_over_forecast_income.legend.verticalAlign = vertical_align_legend;
                para_over_forecast_income.legend.y = y_legend_forcast_income;
                para_over_forecast_income.legend.x = x_legend_forcast_income;
                
                para_over_forecast_income.chart.style.fontFamily=chart_fontFamily;
                para_over_forecast_income.chart.style.fontSize=chart_fontSize;
                para_over_forecast_income.chart.style.fontWeight=chart_fontWeight;
                
                para_over_forecast_income.chart.spacingLeft = spacing_left_forcast_income;
                para_over_forecast_income.chart.spacingBottom = spacing_bottom_forcast_income;
                para_over_forecast_income.chart.marginLeft = margin_left_forcast_income;
                para_over_forecast_income.chart.marginRight = margin_right_forcast_income;
                para_over_forecast_income.chart.marginBottom = margin_bottom_forcast_income;
                para_over_forecast_income.chart.marginTop = margin_top_forcast_income;
                
                para_over_forecast_income.xAxis.title.style.fontSize=overview_axisLable;
                para_over_forecast_income.xAxis.labels.style.fontSize=overview_axisData;
                para_over_forecast_income.yAxis.title.offset = offset_y_title_forcast_income;
                para_over_forecast_income.xAxis.title.offset = offset_x_title_forcast_income;
                para_over_forecast_income.yAxis.labels.style.fontSize=overview_axisData;
                para_over_forecast_income.yAxis.title.style.fontSize=overview_axisLable;
                para_over_forecast_income.tooltip.style.fontSize = font_size_tool_tip;
                if(window_width >=1024){
                    para_over_forecast_income.tooltip.pointFormat = '<span style="color:{series.color};font-size:9px;margin-bottom:-10px;">● </span>{series.name}: <b>{point.y}</b><br>';
                }else{
                    var keyToDelete = "pointFormat";
                    delete   para_over_forecast_income.tooltip[keyToDelete];
                }
                if(window_width < 768){
                    var flag_ethnicity = false;
                    var flag_housing = false;
                    var flag_job = false;
                    var flag_income = false;
                    $('#loading-section').addClass("hide");
                    $('.site-header').css({"z-index":''});
                    $('#overview-body').removeClass("visibility");

                    var ethnicity = $('#chart-1');
                    var ethnicityHeight = ethnicity.offset().top;
                    var housing = $('#chart-2');
                    var housingHeight = housing.offset().top;
                    var job = $('#chart-4');
                    var jobHeight = job.offset().top;
                    var income = $('#chart-5');
                    var incomeHeight = income.offset().top;
                    if(isMobile.any()){
                        $(window).on('scroll',function(e){
                            var st = $(window).scrollTop();
                            if(st > (ethnicityHeight-175)){
                                if(!flag_ethnicity){
                                    drawForecastChart("ethnicity");
                                    flag_ethnicity = true;
                                }
                            }
                            if(st > (housingHeight-conScroll)){
                                if(!flag_housing){
                                    drawForecastChart("housing");
                                    flag_housing = true;
                                }
                            }
                            if(st > (jobHeight-conScroll)){
                                if(!flag_job){
                                    drawForecastChart("job");
                                    flag_job = true;
                                }
                            }
                            if(st > (incomeHeight-conScroll)){
                                if(!flag_income){
                                    drawForecastChart("income");
                                    flag_income = true;
                                }
                            }
                        });
                    }else{
                        $('.st-content').on('scroll',function(e){
                            var st = $('.st-content').scrollTop();
                            if(st > (ethnicityHeight-conScroll)){
                                if(!flag_ethnicity){
                                    drawForecastChart("ethnicity");
                                    flag_ethnicity = true;
                                }
                            }
                            if(st > (housingHeight-conScroll)){
                                if(!flag_housing){
                                    drawForecastChart("housing");
                                    flag_housing = true;
                                }
                            }
                            if(st > (jobHeight-conScroll)){
                                if(!flag_job){
                                    drawForecastChart("job");
                                    flag_job = true;
                                }
                            }
                            if(st > (incomeHeight-conScroll)){
                                if(!flag_income){
                                    drawForecastChart("income");
                                    flag_income = true;
                                }
                            }
                        });
                    }
                }else{
                    var chart_over_force_income = new Highcharts.Chart(para_over_forecast_income,function(objChart){

                        $('#loading-section').addClass("hide");
                        $('.site-header').css({"z-index":''});
                        $('#overview-body').removeClass("visibility");
                        drawForecastChart("ethnicity");
                        drawForecastChart("housing");
                        drawForecastChart("job");
                    });
                }
                var width = $(".chart-list").width();
                para_detail_forecast_income.series = arrDataForecastIncome;
                para_detail_forecast_income.xAxis.categories = categories_forecast_income;
                para_detail_forecast_income.chart.width = width;
                
                para_detail_forecast_income.legend.symbolHeight = detail_symbol_height;
                para_detail_forecast_income.legend.symbolWidth = detail_symbol_width;
                para_detail_forecast_income.legend.itemMarginTop = item_margin_top_detail_focast_income;
                para_detail_forecast_income.legend.itemMarginBottom = item_margin_bottom_detail_focast_income;
                para_detail_forecast_income.legend.itemStyle.fontSize = detail_legend;
                para_detail_forecast_income.legend.layout = layout_legend;
                para_detail_forecast_income.legend.align = align_legend;
                para_detail_forecast_income.legend.verticalAlign = vertical_align_legend;
                para_detail_forecast_income.legend.y = y_legend_detail_forcast_income;
                para_detail_forecast_income.legend.x = x_legend_detail_forcast_income;
                
                para_detail_forecast_income.chart.style.fontFamily = chart_fontFamily;
                para_detail_forecast_income.chart.style.fontSize = chart_fontSize;
                para_detail_forecast_income.chart.style.fontWeight = chart_fontWeight;
                
                para_detail_forecast_income.chart.spacingLeft = spacing_left_detail_forcast_income;
                para_detail_forecast_income.chart.spacingBottom = spacing_bottom_detail_forcast_income;
                para_detail_forecast_income.chart.marginLeft = margin_left_detail_forcast_income;
                para_detail_forecast_income.chart.marginRight = margin_right_detail_forcast_income;
                para_detail_forecast_income.chart.marginBottom = margin_bottom_detail_forcast_income;
                para_detail_forecast_income.chart.marginTop = margin_top_detail_forcast_income;
                
                para_detail_forecast_income.xAxis.labels.style.fontSize = detail_axisData;
                para_detail_forecast_income.xAxis.title.style.fontSize = detail_axisLable;
                 para_detail_forecast_income.xAxis.labels.rotation = rotation_detail_forcast_income;
                para_detail_forecast_income.xAxis.labels.y = label_x_detail_forcast_income;
                para_detail_forecast_income.yAxis.title.offset = offset_y_title_detail_forcast_income;
                para_detail_forecast_income.xAxis.title.offset = offset_x_title_detail_forcast_income;
                para_detail_forecast_income.yAxis.labels.style.fontSize = detail_axisData;
                para_detail_forecast_income.yAxis.title.style.fontSize = detail_axisLable;
                para_detail_forecast_income.tooltip.style.fontSize=font_size_detail_tool_tip;
                /*var chart_detail_force_income = new Highcharts.Chart(para_detail_forecast_income,function(objChart){});
                createMenu('detail_4');*/
            }
        },
        error: function(request, status, error) {
            console.log($('#url_ethnicity').val());
            console.log(request.responseText);
        }
    });

}
var sort_by = function(field, reverse, primer) {
    var key = primer ?
            function(x) {
                return primer(x[field])
            } :
            function(x) {
                return x[field]
            };

    reverse = !reverse ? 1 : -1;

    return function(a, b) {
        return a = key(a), b = key(b), reverse * ((a > b) - (b > a));
    }
}

function sortByKey(array, key) {
    return array.sort(function(a, b) {
        var x = a[key];
        var y = b[key];

        if (typeof x == "string")
        {
            x = x.toLowerCase();
            y = y.toLowerCase();
        }

        return ((x < y) ? -1 : ((x > y) ? 1 : 0));
    });
}
function updateLegendBox(chart) {
    //number of series
    if (chart.series.length == 1) {
        var title = $("g.highcharts-legend-title tspan")[0];
        var len = title.textContent.length * 8;
        var box = $("g.highcharts-legend rect")[0];
        
        box.setAttribute('width', 10);
    }
}