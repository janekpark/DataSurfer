var api_port=window.location.port;
var api_url =""
if(api_port >0){
    api_url = window.location.protocol +'//' + window.location.hostname+ ':'+api_port
}else{
    api_url = window.location.protocol + '//' + window.location.hostname
}
api_url += "/services.php";
function changeArray(categories) {
    var tmp = [];
    if (categories.length > 0) {
        tmp[0] = categories[categories.length - 1]
        for (var i = 1; i < categories.length; i++) {
            tmp[i] = categories[i - 1];
        }

    }
    return tmp;
}
$(function() {
    Highcharts.setOptions({
        lang: {
            decimalPoint: '.',
            thousandsSep: ','
        }
    });

});


function drawChart(type) {
    if(type=="education"){
        var chart_education = new Highcharts.Chart(para_over_education, function (objChart) { });
    }else if (type == "employmentstatus") {
        var chart_over_employmentstatus = new Highcharts.Chart(para_over_employmentstatus, function (objChart) { });
    } else if (type == "transportation") {
        var chart_over_transportation = new Highcharts.Chart(para_over_transportation, function (objChart) { });
    } else if (type == "language") {
        var chart_over_transportation = new Highcharts.Chart(para_over_language, function (objChart) { });
    }else if(type=="housing"){
        var chart_over_housing = new Highcharts.Chart(para_over_housing,function(obj){
            var xr1=0,xr2=0,yr1=0,yr2=0;
            var window_width = $(window).width();
            var heigh_chart = $('#chart-2').height();
            var width_chart= $('#chart-2').width();
            var size_chart=chart_size_housing.replace('%','');    

            $.each(obj.series[0].data, function(i, point) {
                var min_size = heigh_chart;
                if(heigh_chart > width_chart){
                    min_size = width_chart
                    xr1 = (min_size*(100-size_chart)/2)/100;
                    xr2 = (min_size*(100-((100-size_chart)/2)))/100;

                    yr1=(heigh_chart*(100-size_chart)/2)/100;
                    yr2=(heigh_chart*(100-((100-size_chart)/2)))/100;
                }else{
                    yr1 = (min_size*(100-size_chart)/2)/100;
                    yr2 = (min_size*(100-((100-size_chart)/2)))/100;

                    xr1=(width_chart*(100-size_chart)/2)/100;
                    xr2=(width_chart*(100-((100-size_chart)/2)))/100;
                }

                if(point.name=="Multifamily" || point.name=="Single Family - Multiple Unit"){
                    if(point.dataLabel.y <yr1 && (point.dataLabel.y+35) > yr1){
                        var new_y = point.dataLabel.y -10;
                        point.dataLabel.attr({y: new_y});
                    }
                }else if(point.name=="Single Family - Detached"){
                    if(point.dataLabel.y + 50 > yr1 && point.dataLabel.y < yr1){
                        var new_x = point.dataLabel.x +10;
                        var new_y = point.dataLabel.y -10 -(yr1-point.dataLabel.y);
                        point.dataLabel.attr({x: new_x});
                        point.dataLabel.attr({y: new_y});
                    }
                }else if(point.name=="Mobile Home"){
                    if(point.dataLabel.x-100 < 0){
                        var new_x = point.dataLabel.x +12;
                         point.dataLabel.attr({x: new_x});
                    }
                }

            })
        });
    //}else if(type=="age"){
    //    var chart_over_age = new Highcharts.Chart(para_over_age,function(objChart){});
    }else if(type=="income"){
        var chart_over_income = new Highcharts.Chart(para_over_income,function(objChart){});
    } 
}
//census chart educational attainment
function loadChart() {
    $('#chart-1').addClass("chart-frame-248");
    $('#chart-detail').addClass("chart-frame-248");
    $('#chart-2').addClass("chart-frame-248");
    $('#chart-detail_2').addClass("chart-frame-248");

    $('#chart-4').addClass("chart-frame-380");
    $('#chart-detail_3').addClass("chart-frame-380");
    $('#chart-5').addClass("chart-frame-402");
    $('#chart-detail_4').addClass("chart-frame-402");

    // for census--retrieve total population from #url_age
    $.ajax({
        url: api_url,
        type: "POST",
        dataType: "json",
        data: { type_chart: "age", url_des: $('#url_age').val() },
        success: function (res) {
            if (res.length > 0) {
                for (var i = 0; i < res.length; i++) {
                    var obj = res[i];
                    if ('sex' in obj && obj.sex == "Total"
                            && obj.group_10yr == "Total Population") {
                        // $('#set_location_popuplate').val(obj.population);
                        $('#total_population').html(Highcharts.numberFormat(obj.population, 0, '.', ','));
                    }
                }
            }
            return 1;
        },
        error: function (request, status, error) {
            console.log($('#url_age').val());
            console.log(request.responseText);
        }
    });
    //

    $.ajax({
        url: api_url,
        type: "POST",
        dataType: "json",
        data: { type_chart: "education", url_des: $('#url_education').val() },
        success: function (res) {
            if (res.length > 0) {
                var categories = [];
                var keys = [];
                var sum_key = 0;
                var arr_tmp = [];
                var colors = [];

                var arrEducation = [];
                var dataDetailArray = [];

                for (var i = 0; i < res.length; i++) {
                    var obj = res[i]; // console.log(obj[key]);

                    if (obj.level != 'Total Population age 25 and older')
                        sum_key += obj.population;
                }

                var i_counter = 0;
                for (var i = 0; i < res.length; i++) {
                    var obj = res[i];
                    if (obj.level != 'Total Population age 25 and older') {
                        categories.push(obj.level);
                        var cKey = obj.level.replace(/ /g, "").toLowerCase();
                        
                        colors.push(colorEducationCensus[cKey][0]);
                        keys.push(obj.population);

                        datalen = keys[i_counter].length;
                        var brightness = 0.2 - (i_counter) / res.length;
                        arrEducation.push({
                            name: categories[i_counter],
                            y: precise_round((keys[i_counter] / sum_key) * 100, 0),
                            color: colors[i_counter],
                            fy: precise_round((keys[i_counter] / sum_key) * 100, 1),
                        });
                        dataDetailArray.push({
                            name: categories[i_counter],
                            y: precise_round((keys[i_counter] / sum_key) * 100, 1),
                            color: colors[i_counter]
                        });
                        i_counter += 1;
                    }
                }

                arrEducation.sort(function (a, b) {
                    return b.y - a.y;
                })
                for (var i = 0; i < arrEducation.length; i++) {
                    arr_tmp.push(arrEducation[i].y);
                }
                arr_tmp_education = arr_tmp;
                dataDetailArray.sort(function (a, b) {
                    return b.y - a.y;
                })
                var temp = arrEducation[1];
                arrEducation[1] = arrEducation[4];
                arrEducation[4] = temp;

                var temp = dataDetailArray[1];
                dataDetailArray[1] = dataDetailArray[4];
                dataDetailArray[4] = temp;
                var sAg = -arrEducation[0].y * 9 / 5;

                if (sum_key > 0) {
                    sum_key_education = sum_key;
                    dataArrayEducation = arrEducation;
                    dataDetailArrayEducation = dataDetailArray;
                    sAgEducation = sAg;
                    para_over_education.series[0].data = dataArrayEducation;
                    para_over_education.series[0].size = chart_size_education;
                    para_over_education.series[0].innerSize = chart_size_inner_education;
                    para_over_education.series[0].startAngle = sAg;
                    para_over_education.plotOptions.pie.dataLabels.style.fontSize = pie_chart_data;
                    para_over_education.tooltip.style.fontSize = font_size_tool_tip;
                    para_over_education.chart.marginTop = margin_top_education;
                    para_over_education.chart.marginBottom = margin_bottom_education;

                    chartCensus2();
                    var width = $(".chart-list").width();
                    para_detail_education.series[0].data = dataDetailArrayEducation;
                    para_detail_education.series[0].startAngle = sAg;
                    para_detail_education.series[0].size = chart_size_education;
                    para_detail_education.series[0].innerSize = chart_size_inner_education;
                    para_detail_education.chart.width = width;
                    para_detail_education.plotOptions.pie.dataLabels.style.fontSize = detail_pie_chart_data;
                    para_detail_education.plotOptions.pie.dataLabels.distance = pie_distance;
                    para_detail_education.tooltip.style.fontSize = font_size_detail_tool_tip;
                    //var chart_detail_ethnicity = new Highcharts.Chart(para_detail_ethnicity,function(objChart){});
                    //createMenu('detail')
                } else {
                    $('#link_education').addClass('hide');
                    $('#chart-1').highcharts({
                        chart: {
                            style: {
                                color: color,
                            },
                        },
                        credits: {
                            enabled: false
                        },
                        title: {
                            text: '',
                            style: {
                                color: color,
                            }
                        },
                        series: [{
                            type: 'pie',
                            name: 'Random data',
                            data: []
                        }],
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
                        exporting: {
                            enabled: false
                        }
                    }, function () {
                        //chart2();
                        chartCensus2();
                    });
                }
            }
            return 1;
        },
        error: function (request, status, error) {
            console.log($('#url_education').val());
            console.log(request.responseText);
        }
    });
}

function chartCensus2() {
    $('#chart-2').addClass("chart-frame-cencus");
    $('#chart-detail_2').addClass("chart-frame-cencus");
    //alert('chartCensus2');
    $.ajax({
        url: api_url,
        type: "POST",
        dataType: "json",
        data: { type_chart: "employmentstatus", url_des: $('#url_employmentstatus').val() },
        success: function (res) {
            if (res.length > 0) {
                var categories = [];
                var arrFemale = [];
                var arrMale = [];
                //var arrOMale = [];
                //var arrOFemale = [];
                var arrData = [];
                var max_female = 0;
                var max_male = 0;
                var max = 0;
                var sum_key = 0;
            
                for (var i = 0; i < res.length; i++) {
                    var obj = res[i];
                    if (obj.status != "Population age 16 and older") {
                        categories.push(obj.status);
                        var cKey = obj.status.replace(/ /g, "").toLowerCase();
                        //alert(' in    chartCensus2()  ' + obj.status + ' : male: ' + obj.male + ' female: ' + obj.female * -1);
                        arrFemale.push(obj.female * -1);
                        arrMale.push(obj.male);

                        if(obj.female > max_female) {
                            max_female = obj.female;
                        }
                        if (obj.male > max_male) {
                            max_male = obj.male;
                        }
                    }

                 
                    if (obj.status == "Population age 16 and older") {
                        $('#set_location_popuplate').val((obj.male + obj.female));
                        
                    }
                }
                arrData = [
                    {
                        name: 'Women',
                        data: arrFemale,
                        color: fontEmploymentStatusCensus['women'][0]
                    },
                    {
                    name: 'Men',
                    data: arrMale,
                    color: fontEmploymentStatusCensus['men'][0]
                    }];
                arrData.sort(function (a, b) {
                    return a.name - b.name;
                })
                max = max_female + max_male;
                // test this ==delete later jpa
                max = max_male;
                if(max_female > max_male){
                    max = max_female;
                }
              
                if ($('#set_location_popuplate').val() > 0) {
                    arrDataemploymentstatus = arrData;
                    categories_over_employmentstatus = categories;
                    max_over_employmentstatus = max;
                    para_over_employmentstatus.series = arrDataemploymentstatus;
                    para_over_employmentstatus.xAxis.categories = categories_over_employmentstatus;
                    para_over_employmentstatus.yAxis.max = max_over_employmentstatus;

                    para_over_employmentstatus.legend.itemStyle.fontSize = overview_Legend;
                    //para_over_employmentstatus.legend.layout = layout_legend;
                    // try this as horizontal layout -- jpa
                    para_over_employmentstatus.legend.layout = layout_legend_employmentstatus;
                    para_over_employmentstatus.legend.align = align_legend_employmentstatus;
                   // para_over_employmentstatus.legend.verticalAlign = vertical_align_legend;
                    para_over_employmentstatus.legend.verticalAlign = 'bottom';
                    para_over_employmentstatus.legend.y = y_legend_employmentstatus;
                    para_over_employmentstatus.legend.x = x_legend_employmentstatus;
                    para_over_employmentstatus.legend.itemMarginBottom = 5;

                    para_over_employmentstatus.chart.style.fontFamily = chart_fontFamily;
                    para_over_employmentstatus.chart.style.fontSize = chart_fontSize;
                    para_over_employmentstatus.chart.style.fontWeight = chart_fontWeight;
                    para_over_employmentstatus.chart.spacingLeft = spacing_left_employmentstatus;
                    para_over_employmentstatus.chart.spacingBottom = spacing_bottom_employmentstatus;
                    para_over_employmentstatus.chart.marginLeft = margin_left_employmentstatus;
                    para_over_employmentstatus.chart.marginRight = margin_right_employmentstatus;
                    para_over_employmentstatus.chart.marginBottom = margin_bottom_employmentstatus;
                    para_over_employmentstatus.chart.marginTop = margin_top_employmentstatus;

                    para_over_employmentstatus.xAxis.title.offset = offset_x_title_employmentstatus;
                    para_over_employmentstatus.xAxis.title.style.fontSize = overview_axisLable;
                    para_over_employmentstatus.xAxis.labels.style.fontSize = overview_axisData;

                    para_over_employmentstatus.yAxis.title.offset = offset_y_title_employmentstatus;
                    para_over_employmentstatus.yAxis.title.style.fontSize = overview_axisLable;
                    para_over_employmentstatus.yAxis.offset = -8;
                    para_over_employmentstatus.yAxis.labels.style.fontSize = overview_axisData;
                    para_over_employmentstatus.tooltip.style.fontSize = font_size_tool_tip;
                    chartCensus4();
                    var width = $(".chart-list").width();
                    para_detail_employmentstatus.series = arrDataemploymentstatus;
                    para_detail_employmentstatus.plotOptions.series.pointWidth = point_width_employmentstatus;
                    para_detail_employmentstatus.xAxis.categories = categories_over_employmentstatus;
                    para_detail_employmentstatus.yAxis.max = max_over_employmentstatus;
                    para_detail_employmentstatus.chart.width = width;

                    para_detail_employmentstatus.legend.itemStyle.fontSize = detail_legend;
                    para_detail_employmentstatus.legend.layout = layout_legend;
                    para_detail_employmentstatus.legend.align = align_legend;
                    para_detail_employmentstatus.legend.verticalAlign = vertical_align_legend;
                    para_detail_employmentstatus.legend.y = y_legend_detail_employmentstatus;
                    para_detail_employmentstatus.legend.x = x_legend_detail_employmentstatus;
                    para_detail_employmentstatus.legend.itemMarginBottom = 0;
                    para_detail_employmentstatus.legend.itemMarginTop = 10;
                    para_detail_employmentstatus.legend.symbolPadding = 6;

                    para_detail_employmentstatus.chart.style.fontFamily = chart_fontFamily;
                    para_detail_employmentstatus.chart.style.fontSize = chart_fontSize;
                    para_detail_employmentstatus.chart.style.fontWeight = chart_fontWeight;
                    para_detail_employmentstatus.chart.spacingLeft = spacing_left_detail_employmentstatus;
                    para_detail_employmentstatus.chart.spacingBottom = spacing_bottom_detail_employmentstatus;
                    para_detail_employmentstatus.chart.marginLeft = margin_left_detail_employmentstatus;
                    para_detail_employmentstatus.chart.marginRight = margin_right_detail_employmentstatus;
                    para_detail_employmentstatus.chart.marginBottom = margin_bottom_detail_employmentstatus;
                    para_detail_employmentstatus.chart.marginTop = margin_top_detail_employmentstatus;

                    para_detail_employmentstatus.xAxis.title.offset = offset_x_title_detail_employmentstatus;
                    para_detail_employmentstatus.xAxis.labels.y = y_label_detail_employmentstatus;
                    para_detail_employmentstatus.xAxis.title.margin = margin_title;
                    para_detail_employmentstatus.xAxis.title.style.fontSize = detail_axisLable;
                    para_detail_employmentstatus.xAxis.labels.style.fontSize = detail_axisData;
                    para_detail_employmentstatus.yAxis.title.offset = offset_y_title_detail_employmentstatus;
                    para_detail_employmentstatus.yAxis.labels.style.fontSize = detail_axisData;
                    para_detail_employmentstatus.yAxis.title.style.fontSize = detail_axisLable;
                    para_detail_employmentstatus.tooltip.style.fontSize = font_size_detail_tool_tip;
                    /*var chart_detail_age = new Highcharts.Chart(para_detail_age,function(objChart){});
                    createMenu('detail_3')*/
                } else {
                    $('#link_employmentstatus').addClass('hide');
                    $('#chart-2').highcharts({
                        chart: {
                            style: {
                                color: color,
                            }
                        },
                        credits: {
                            enabled: false
                        },
                        title: {
                            text: '',
                            style: {
                                color: color,
                            }
                        },
                        series: [{
                            type: 'pie',
                            name: 'Random data',
                            data: []
                        }],
                        lang: {
                            noData: "Data not available."
                        },
                        noData: {
                            style: {
                                fontWeight: 'normal',
                                fontSize: '12pt',
                                fontFamily: chart_fontFamily,
                                color: color
                            }
                        },
                        exporting: {
                            enabled: false
                        }
                    }, function () {
                        chartCensus4();
                    });
                }
            }
            return 1;
        },
        error: function (request, status, error) {
            console.log($('#url_employmentstatus').val());
            console.log(request.responseText);
        }
    });
}


// function chartCensus4() means of transportation to work

function chartCensus4() {
    $.ajax({
        url: api_url,
        type: "POST",
        dataType: "json",
        data: { type_chart: "transportation", url_des: $('#url_transportation').val() },
        success: function (res) {
            if (res.length > 0) {
                var categories = [];
                var arrMeans = [];
                var arrMeansPercent = [];
                var arrFemale = [];
                var arrMale = [];
                var arrOMale = [];
                var arrOFemale = [];
                var arrData = [];
                var max_female = 0;
                var max_male = 0;
                var max = 0;
                var colors = [];
                var sum_key = 0;

                // get total
                for (var i = 0; i < res.length; i++) {
                    var obj = res[i];
                   // var cKey = obj.means_of_trans.replace(/ /g, "").toLowerCase();
                    var cKey = obj.means_of_trans.toLowerCase();
                    if (cKey.indexOf("public transportation")>=0)
                        cKey = "transit";
                    categories.push(cKey);
                  
                    arrMeans.push(obj.number);
                  

                    sum_key += obj.number;

                }
                $('#set_location_popuplate').val(sum_key);
           //     $('#total_population').html(Highcharts.numberFormat(sum_key, 0, '.', ','));
                
                var meansPercent = 0;
              
                for (var i = 0; i < arrMeans.length; i++) {
                    if(sum_key > 0 ){
                        meansPercent = precise_round((arrMeans[i] / sum_key) * 100, 1);
                        arrMeansPercent.push(meansPercent);
                        if( meansPercent > max)
                            max = meansPercent;
                    }
                    else{
                        arrMeansPercent.push(0);
                    }
                  
                }
               
                arrData = [
                    {
                        name: 'means',
                        data: arrMeansPercent,
                        data_value: arrMeans,
                        color: fontTransportationCensus['means'][0]
                    }
                       
                    ];
                
                if (sum_key > 0) {
                    arrDataTransportation = arrData;
                    categories_over_transportation = categories;
                    max_over_transportation = max;
                    para_over_transportation.series = arrDataTransportation;
                    para_over_transportation.xAxis.categories = categories_over_transportation;
                    para_over_transportation.yAxis.max = max_over_transportation;

                    para_over_transportation.legend.itemStyle.fontSize = overview_Legend;
                    para_over_transportation.legend.layout = layout_legend;
                    para_over_transportation.legend.align = align_legend;
                    para_over_transportation.legend.verticalAlign = vertical_align_legend;
                    para_over_transportation.legend.y = y_legend_transportation;
                    para_over_transportation.legend.x = x_legend_transportation;
                    para_over_transportation.legend.itemMarginBottom = 5;

                    para_over_transportation.chart.style.fontFamily = chart_fontFamily;
                    para_over_transportation.chart.style.fontSize = chart_fontSize;
                    para_over_transportation.chart.style.fontWeight = chart_fontWeight;
                    para_over_transportation.chart.spacingLeft = spacing_left_transportation;
                    para_over_transportation.chart.spacingBottom = spacing_bottom_transportation;
                    para_over_transportation.chart.marginLeft = margin_left_transportation;
                    para_over_transportation.chart.marginRight = margin_right_transportation;
                    para_over_transportation.chart.marginBottom = margin_bottom_transportation;
                    para_over_transportation.chart.marginTop = margin_top_transportation;

                    para_over_transportation.xAxis.title.offset = offset_x_title_transportation;
                    para_over_transportation.xAxis.title.style.fontSize = overview_axisLable;
                    para_over_transportation.xAxis.labels.style.fontSize = overview_axisData;

                    para_over_transportation.yAxis.title.offset = offset_y_title_transportation;
                    para_over_transportation.yAxis.title.style.fontSize = overview_axisLable;
                    para_over_transportation.yAxis.offset = -8;
                    para_over_transportation.yAxis.labels.style.fontSize = overview_axisData;
                    para_over_transportation.tooltip.style.fontSize = font_size_tool_tip;
                    chartCensus5();
                    var width = $(".chart-list").width();
                    para_detail_transportation.series = arrDataTransportation;
                    para_detail_transportation.plotOptions.series.pointWidth = point_width_transportation;
                    para_detail_transportation.xAxis.categories = categories_over_transportation;
                    para_detail_transportation.yAxis.max = max_over_transportation;
                    para_detail_transportation.chart.width = width;

                    para_detail_transportation.legend.itemStyle.fontSize = detail_legend;
                    para_detail_transportation.legend.layout = layout_legend;
                    para_detail_transportation.legend.align = align_legend;
                    para_detail_transportation.legend.verticalAlign = vertical_align_legend;
                    para_detail_transportation.legend.y = y_legend_detail_transportation;
                    para_detail_transportation.legend.x = x_legend_detail_transportation;
                    para_detail_transportation.legend.itemMarginBottom = 0;
                    para_detail_transportation.legend.itemMarginTop = 10;
                    para_detail_transportation.legend.symbolPadding = 6;

                    para_detail_transportation.chart.style.fontFamily = chart_fontFamily;
                    para_detail_transportation.chart.style.fontSize = chart_fontSize;
                    para_detail_transportation.chart.style.fontWeight = chart_fontWeight;
                    para_detail_transportation.chart.spacingLeft = spacing_left_detail_transportation;
                    para_detail_transportation.chart.spacingBottom = spacing_bottom_detail_transportation;
                    para_detail_transportation.chart.marginLeft = margin_left_detail_transportation;
                    para_detail_transportation.chart.marginRight = margin_right_detail_transportation;
                    para_detail_transportation.chart.marginBottom = margin_bottom_detail_transportation;
                    para_detail_transportation.chart.marginTop = margin_top_detail_transportation;

                    para_detail_transportation.xAxis.title.offset = offset_x_title_detail_transportation;
                    para_detail_transportation.xAxis.labels.y = y_label_detail_transportation;
                    para_detail_transportation.xAxis.title.margin = margin_title;
                    para_detail_transportation.xAxis.title.style.fontSize = detail_axisLable;
                    para_detail_transportation.xAxis.labels.style.fontSize = detail_axisData;
                    para_detail_transportation.yAxis.title.offset = offset_y_title_detail_transportation;
                    para_detail_transportation.yAxis.labels.style.fontSize = detail_axisData;
                    para_detail_transportation.yAxis.title.style.fontSize = detail_axisLable;
                    para_detail_transportation.tooltip.style.fontSize = font_size_detail_tool_tip;

                 
                    /*var chart_detail_transportation = new Highcharts.Chart(para_detail_transportation,function(objChart){});
                    createMenu('detail_3')*/
                } else {
                    $('#link_transportation').addClass('hide');
                    $('#chart-4').highcharts({
                        chart: {
                            style: {
                                color: color,
                            }
                        },
                        credits: {
                            enabled: false
                        },
                        title: {
                            text: '',
                            style: {
                                color: color,
                            }
                        },
                        series: [{
                            type: 'pie',
                            name: 'Random data',
                            data: []
                        }],
                        lang: {
                            noData: "Data not available."
                        },
                        noData: {
                            style: {
                                fontWeight: 'normal',
                                fontSize: '12pt',
                                fontFamily: chart_fontFamily,
                                color: color
                            }
                        },
                        exporting: {
                            enabled: false
                        }
                    }, function () {
                        chartCensus5();
                    });
                }
            }
            return 1;
        },
        error: function (request, status, error) {
            console.log($('#url_transportation').val());
            console.log(request.responseText);
        }
    });
}
// end of functioin chartCensus4() means of transporation to work


//function chartCensus5
function chartCensus5() {
    $.ajax({
        url: api_url,
        type: "POST",
        dataType: "json",
        data: { type_chart: "language", url_des: $('#url_language').val() },
        success: function (res) {
            var tmp_san = [];
            var data_san = [];
            var sum_total = 0;
            var categories = [];
          
            var arrEnglishProficient = [];
            var arrEnglishLimited = [];
            var max = 0;

            if (res.length > 0) {
                for (var i = 0; i < res.length; i++) {
                    var obj = res[i];
                    var str = obj.population;
                    if (obj.population != "") {
                        
                        if (str.indexOf("Speaks only English") >= 0) {
                            if (!isInArray("English only", categories)) {
                                categories.push("English only");
                            }
                            arrEnglishProficient.push(obj.total);
                            arrEnglishLimited.push(0);
                            //if (obj.total > max)
                            //    max = obj.total;

                          
                        }
                        else if (str.indexOf("Speaks Spanish") >= 0) {
                            
                            if (str.indexOf("English well") > 0)
                                arrEnglishProficient.push(obj.total);
                            else if (str.indexOf("limited English") > 0) {
                                arrEnglishLimited.push(obj.total);
                            }
                            if( !isInArray("Spanish", categories )){
                                categories.push("Spanish");
                            }
                            //if (obj.total > max)
                            //    max = obj.total;

                        }
                        else if (str.indexOf("Speaks Asian") >= 0) {
                            
                            if (str.indexOf("English well") > 0)
                                arrEnglishProficient.push(obj.total);
                            else if (str.indexOf("limited English") > 0) {
                                arrEnglishLimited.push(obj.total);
                            }
                            if( !isInArray("Asian/Pacific Islander", categories )){
                                categories.push("Asian/Pacific Islander");
                            }
                            //if (obj.total > max)
                            //    max = obj.total;
                        }
                        else if (str.indexOf("Other language") >= 0) {
                           
                            if (str.indexOf("English well") > 0)
                                arrEnglishProficient.push(obj.total);
                            else if (str.indexOf("limited English") > 0) {
                                arrEnglishLimited.push(obj.total);
                            }
                            if( !isInArray("Other language", categories )){
                                categories.push("Other language");
                               // alert('census-chart.js chartCensus5  categories : ' + obj.population + ' number is: ' + obj.total);
                            }
                            //if (obj.total > max)
                            //    max = obj.total;
                        }

                        if (str.indexOf("Total age 5 and older") >= 0) {
                            $('#set_location_popuplate').val(obj.total);
                          //  $('#total_population').html(Highcharts.numberFormat(obj.total, 0, '.', ','));
                            sum_total = obj.total;
                        }
                    }
                }
                var data_proficient = [];
                var data_limited = [];
                var limited = 0;
                var proficient = 0;

                for (var i = 0; i < categories.length; i++) {
                    if (sum_total > 0) {
                        limited = precise_round((arrEnglishLimited[i] / sum_total) * 100, 1);
                        proficient = precise_round((arrEnglishProficient[i] / sum_total) * 100, 1);

                        data_limited.push( limited );
                        data_proficient.push( proficient );

                        if (limited > max)
                            max = limited;
                        else if (proficient > max)
                            max = proficient;
                    }
                    else {
                        data_limited.push(0);
                        data_proficient.push(0);
                    }
                }
                arrData = [
                    {
                        name: 'EnglishProficient',
                        data: data_proficient,
                        color: fontLanguageCensus['englishproficient'][0],
                        data_value: arrEnglishProficient
                    },
                    {
                        name: 'EnglishLimited',
                        data: data_limited,
                        color: fontLanguageCensus['englishlimited'][0],
                        data_value: arrEnglishLimited
                    }];
                arrData.sort(function (a, b) {
                    return a.name - b.name;
                })

               
                if ($('#set_location_popuplate').val() > 0) {
                    var window_width = $(window).width();
                    arrDataLanguage = arrData;

                    max_over_language = max;
                    categories_over_language = categories;
                    para_over_language.series = arrDataLanguage;
                    para_over_language.xAxis.categories = categories_over_language;
                    para_over_language.yAxis.max = max_over_language;
                    //locationLabellanguage = locationLabel;
                    //sandiegoLabellanguage = sandiegoLabel;

                    para_over_language.legend.itemStyle.fontSize = overview_Legend;
                    para_over_language.legend.layout = layout_legend;
                    para_over_language.legend.align = align_legend;
                    para_over_language.legend.verticalAlign = vertical_align_legend;
                    para_over_language.legend.y = y_legend_language;
                    para_over_language.legend.x = x_legend_language;
                    para_over_language.legend.itemMarginBottom = 5;

                    para_over_language.chart.style.fontFamily = chart_fontFamily;
                    para_over_language.chart.style.fontSize = chart_fontSize;
                    para_over_language.chart.style.fontWeight = chart_fontWeight;

                    para_over_language.chart.spacingLeft = spacing_left_language;
                    para_over_language.chart.spacingBottom = spacing_bottom_language;
                    para_over_language.chart.marginLeft = margin_left_language;
                    para_over_language.chart.marginRight = margin_right_language;
                    para_over_language.chart.marginBottom = margin_bottom_language;
                    para_over_language.chart.marginTop = margin_top_language;

                    para_over_language.xAxis.title.offset = offset_x_title_language;
                    para_over_language.xAxis.title.style.fontSize = overview_axisLable;
                    para_over_language.xAxis.labels.style.fontSize = overview_axisData;
                    para_over_language.yAxis.title.offset = offset_y_title_language;
                    para_over_language.yAxis.title.y = y_title_language;
                    para_over_language.yAxis.offset = -8;
                    para_over_language.yAxis.labels.style.fontSize = overview_axisData;
                    para_over_language.yAxis.title.style.fontSize = overview_axisLable;
                    if (checkInternetExplorer()) {
                        var keyToDelete = "lineHeight";
                        delete para_over_language.legend.itemStyle[keyToDelete];
                    } else {
                        para_over_language.legend.itemStyle.lineHeight = '11%';
                    }
                    if (window_width < 1024) {
                        var keyToDelete = "itemWidth";
                        delete para_over_language.legend.itemStyle[keyToDelete];
                    } else {
                        para_over_language.legend.itemWidth = 80;
                    }
                    para_over_language.tooltip.style.fontSize = font_size_tool_tip;
                    if (window_width < 768) {
                        var flag_education = false;
                        var flag_housing = false;
                        var flag_age = false;
                        var flag_language = false;
                        $('#loading-section').addClass("hide");
                        $('.site-header').css({ "z-index": '' });
                        $('#overview-body').removeClass("visibility");

                        var education = $('#chart-1');
                        var educationHeight = education.offset().top;
                        var housing = $('#chart-2');
                        var housingHeight = housing.offset().top;
                        var age = $('#chart-4');
                        var ageHeight = age.offset().top;
                        var language = $('#chart-5');
                        var languageHeight = language.offset().top;
                        if (isMobile.any()) {
                            $(window).on('scroll', function (e) {
                                var st = $(window).scrollTop();
                                if (st > (educationHeight - conScroll - 50)) {
                                    if (!flag_education) {
                                        drawChart("education");
                                        flag_education = true;
                                    }

                                }
                                if (st > (employmentstatusHeight - conScroll - 50)) {
                                    if (!flag_employmentstatus) {
                                        drawChart("employmentstatus");
                                        flag_employmentstatus = true;
                                    }

                                }
                                if (st > (transportationHeight - conScroll - 50)) {
                                    if (!flag_transportation) {
                                        drawChart("transportation");
                                        flag_transportation = true;
                                    }

                                }
                                if (st > (languageHeight - conScroll)) {
                                    if (!flag_language) {
                                        drawChart("language");
                                        flag_language = true;
                                    }
                                }
                            });
                        } else {
                            $('.st-content').on('scroll', function (e) {
                                var st = $('.st-content').scrollTop();
                                if (st > (educationHeight - conScroll - 50)) {
                                    if (!flag_education) {
                                        drawChart("education");
                                        flag_education = true;
                                    }
                                }
                                if (st > (employmentstatusHeight - conScroll - 50)) {
                                    if (!flag_employmentstatus) {
                                        drawChart("employmentstatus");
                                        flag_employmentstatus = true;
                                    }
                                }
                                if (st > (transportationHeight - conScroll - 50)) {
                                    if (!flag_transportation) {
                                        drawChart("transportation");
                                        flag_transportation = true;
                                    }
                                }
                                if (st > (languageHeight - conScroll - 50)) {
                                    if (!flag_language) {
                                        drawChart("language");
                                        flag_language = true;
                                    }
                                }
                            });
                        }
                    } else {
                        var chart_over_language = new Highcharts.Chart(para_over_language, function (objChart) {
                            $('#loading-section').addClass("hide");
                            $('.site-header').css({ "z-index": '' });
                            $('#overview-body').removeClass("visibility");

                            drawChart("education");
                            drawChart("employmentstatus");
                            drawChart("transportation");
                           
                        });
                    }

                    var width = $(".chart-list").width();
                    if (window_width >= 1024) {
                        para_detail_language.plotOptions.series.pointWidth = point_width_detail_language;
                        para_detail_language.xAxis.labels.rotation = 0;
                    } else {
                        var keyToDelete = "pointWidth";
                        delete para_detail_language.plotOptions.series[keyToDelete];
                        keyToDelete = "rotation";
                        delete para_detail_language.xAxis.labels[keyToDelete];
                    }
                    //para_detail_language.plotOptions.series.pointWidth= point_width_detail_language;
                    para_detail_language.series = arrDataLanguage;
                    para_detail_language.xAxis.categories = categories_over_language;
                    para_detail_language.chart.width = width;

                    para_detail_language.legend.itemStyle.fontSize = detail_legend;
                    para_detail_language.legend.layout = layout_legend;
                    para_detail_language.legend.align = align_legend;
                    para_detail_language.legend.verticalAlign = vertical_align_legend;
                    para_detail_language.legend.y = y_legend_detail_language;
                    para_detail_language.legend.x = x_legend_detail_language;
                    para_detail_language.legend.itemMarginBottom = 0;
                    para_detail_language.legend.itemMarginTop = 10;
                    para_detail_language.legend.symbolPadding = 6;

                    para_detail_language.chart.style.fontFamily = chart_fontFamily;
                    para_detail_language.chart.style.fontSize = chart_fontSize;
                    para_detail_language.chart.style.fontWeight = chart_fontWeight;
                    para_detail_language.chart.spacingLeft = spacing_left_detail_language;
                    para_detail_language.chart.spacingBottom = spacing_bottom_detail_language;
                    para_detail_language.chart.marginLeft = margin_left_detail_language;
                    para_detail_language.chart.marginRight = margin_right_detail_language;
                    para_detail_language.chart.marginBottom = margin_bottom_detail_language;
                    para_detail_language.chart.marginTop = margin_top_detail_language;

                    para_detail_language.xAxis.title.offset = offset_x_title_detail_language;
                    para_detail_language.xAxis.labels.style.fontSize = detail_axisData;
                    para_detail_language.xAxis.labels.y = y_label_detail_language;
                    para_detail_language.xAxis.title.margin = margin_title;
                    para_detail_language.xAxis.title.style.fontSize = detail_axisLable;
                    para_detail_language.yAxis.title.offset = offset_y_title_detail_language;
                    para_detail_language.yAxis.labels.style.fontSize = detail_axisData;
                    para_detail_language.yAxis.title.style.fontSize = detail_axisLable;
                    para_over_language.tooltip.style.fontSize = font_size_detail_tool_tip;
                    /*var chart_detail_language = new Highcharts.Chart(para_detail_language,function(objChart){});
                    createMenu('detail_4')*/
                    if (isMobile.any()) {
                        $('#txtEmailReport').selectmenu('enable');
                        $('#txtEmailReport').selectmenu('refresh');
                        $('#txtDownloadReport').selectmenu('enable');
                        $('#txtDownloadReport').selectmenu('refresh');
                    } else {
                        $('#email_report').prop('disabled', false);
                        $('#email_report').selectpicker('refresh');
                        $('#download_report').prop('disabled', false);
                        $('#download_report').selectpicker('refresh');
                    }
                    //    }
                    // });
                
            }// if population exists
            return 1;
            } // end of res.length
        },
        error: function (request, status, error) {
            console.log($('#url_language').val());
            console.log(request.responseText);
        }
    });

}
//end function chartCensus5
