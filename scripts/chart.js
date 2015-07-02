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
function drawChart(type){
    if(type=="ethnicity"){
        var chart_ethnicity = new Highcharts.Chart(para_over_ethnicity,function(objChart){});
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
    }else if(type=="age"){
        var chart_over_age = new Highcharts.Chart(para_over_age,function(objChart){});
    }else if(type=="income"){
        var chart_over_income = new Highcharts.Chart(para_over_income,function(objChart){});
    } 
}

function loadChart()
{
    $('#chart-1').addClass("chart-frame-248");
    $('#chart-detail').addClass("chart-frame-248");
    $('#chart-2').addClass("chart-frame-248");
    $('#chart-detail_2').addClass("chart-frame-248");
    
    $('#chart-4').addClass("chart-frame-380");
    $('#chart-detail_3').addClass("chart-frame-380");
    $('#chart-5').addClass("chart-frame-402");
    $('#chart-detail_4').addClass("chart-frame-402");
    
    $.ajax({
        url: api_url,
        type: "POST",
        dataType: "json",
        data: {type_chart: "ethnicity", url_des: $('#url_ethnicity').val()},
        success: function(res) {
            if (res.length > 0) {
                var categories = [];
                var keys = [];
                var sum_key = 0;
                var arr_tmp=[];
                var colors = [];

                var arrEthnicity = [];
                var dataDetailArray = [];
                
                for (var i = 0; i < res.length; i++) {
                    var obj = res[i]; // console.log(obj[key]);
			
					if(obj.ethnicity != 'Total Population')
					    sum_key += obj.population;
                }
                
				var i_counter = 0;
				
                for (var i = 0; i < res.length; i++) {
                    var obj = res[i];
                    if (obj.ethnicity != 'Total Population')
					{
                        categories.push(obj.ethnicity);
                        var cKey = obj.ethnicity.replace(/ /g, "").toLowerCase();
                        colors.push(colorEthnicityCensus[cKey][0]);
                        keys.push(obj.population);

                        datalen = keys[i_counter].length;
                        var brightness = 0.2 - (i_counter) / res.length;
                        arrEthnicity.push({
                            name: categories[i_counter],
                            y: precise_round((keys[i_counter] / sum_key) * 100, 0),
                            color: colors[i_counter],
                            fy:precise_round((keys[i_counter] / sum_key) * 100, 1),
                        });
                    
                        dataDetailArray.push({
                            name: categories[i_counter],
                            y: precise_round((keys[i_counter] / sum_key) * 100, 1),
                            color: colors[i_counter]
                        });
						
						i_counter += 1;
                    }
                }
                
                arrEthnicity.sort(function(a,b){
                    return b.y - a.y;
                })
                for(var i=0;i<arrEthnicity.length;i++){
                    arr_tmp.push(arrEthnicity[i].y);
                }
                arr_tmp_ethinicity=arr_tmp;
                dataDetailArray.sort(function(a,b){
                    return b.y - a.y;
                })
                var temp=arrEthnicity[1];
                arrEthnicity[1]=arrEthnicity[4];
                arrEthnicity[4]=temp;
                
                var temp=dataDetailArray[1];
                dataDetailArray[1]=dataDetailArray[4];
                dataDetailArray[4]=temp;
                var sAg = -arrEthnicity[0].y*9/5;
                
                if(sum_key > 0){
                    sum_key_ethnicity=sum_key;
                    dataArrayEthnicity = arrEthnicity
                    dataDetailArrayEthnicity= dataDetailArray;
                    sAgEthnicity = sAg;
                    para_over_ethnicity.series[0].data = dataArrayEthnicity;
                    para_over_ethnicity.series[0].size = chart_size_ethnicity;
                    para_over_ethnicity.series[0].innerSize = chart_size_inner_ethnicity;
                    para_over_ethnicity.series[0].startAngle = sAg;
                    para_over_ethnicity.plotOptions.pie.dataLabels.style.fontSize=pie_chart_data;
                    para_over_ethnicity.tooltip.style.fontSize=font_size_tool_tip;
                    chart2();
                    var width = $(".chart-list").width();
                    para_detail_ethnicity.series[0].data = dataDetailArrayEthnicity;
                    para_detail_ethnicity.series[0].startAngle = sAg;
                    para_detail_ethnicity.series[0].size = chart_size_ethnicity;
                    para_detail_ethnicity.series[0].innerSize = chart_size_inner_ethnicity;
                    para_detail_ethnicity.chart.width=width;
                    para_detail_ethnicity.plotOptions.pie.dataLabels.style.fontSize=detail_pie_chart_data;
                    para_detail_ethnicity.plotOptions.pie.dataLabels.distance = pie_distance;
                    para_detail_ethnicity.tooltip.style.fontSize=font_size_detail_tool_tip;
                    //var chart_detail_ethnicity = new Highcharts.Chart(para_detail_ethnicity,function(objChart){});
                    //createMenu('detail')
                }else{
                    $('#link_ethnicity').addClass('hide');
                    $('#chart-1').highcharts({
                        chart: {
                            style: {
                                color:color,
                            },
                        },
                        credits: {
                            enabled: false
                        },
                        title: {
                            text: '',
                            style:{
                                color:color,
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
                    },function(){
                        chart2();
                    });
                }
            }
            return 1;
        },
        error: function(request, status, error) {
            console.log($('#url_ethnicity').val());
            console.log(request.responseText);
        }
    });
}


function chart2()
{
    $('#chart-2').addClass("chart-frame-cencus");
    $('#chart-detail_2').addClass("chart-frame-cencus");
    $.ajax({
        url: api_url,
        type: "POST",
        dataType: "json",
        data: {type_chart: "housing", url_des: $('#url_housing').val()},
        success: function(res) {
            if (res.length > 0) {
                var categories = [];
                var keys = [];
                var occupieds = [];
                var vacants = [];
                var sum_key = 0;
                //var colors = Highcharts.getOptions().colors;
                var colors = [];
                var colorDownState = [];
                var dataArray = [];
                for (var i = 0; i < res.length; i++) {
                    var obj = res[i];
                    if (obj['unit_type'] == 'Total Units') {
                        sum_key = obj["units"];
                        res.splice(i, 1);
                    }

                }
                var arrData2 = [];
                var sumHousing = 0;
                for (var i = 0; i < res.length; i++) {
                    var obj = res[i];
                    var units = (obj.units > 0) ? obj.units : 0;
                    for (var key in obj) {
                        if (key == "unit_type") {
                            categories.push(obj[key]);
                        } else if (key == "units") {
                            keys.push(obj[key]);
                        } else {
                            var str_color = "";
                            if (key != "vacancy_rate") {
                                var str_name = "";
                                if (key == "occupied") {
                                    str_name = key;
                                    str_color = colorHousingTypeCensus[str_name][0];
                                    occupieds.push(obj[key]);
                                } else {
                                    str_name = "vacant";
                                    str_color = colorHousingTypeCensus[str_name][0];
                                    vacants.push(obj[key]);
                                }

                                arrData2.push({name: str_name,
                                    y: obj[key],
                                    x: units,
                                    color: str_color,
                                    rate: 4
                                });
                            }

                        }


                    }
                    var cKey = categories[i].replace(/ |-/g, "").toLowerCase();
                    dataArray.push({
                        name: categories[i],
                        y: keys[i],
                        vacant: vacants[i],
                        occupieds: occupieds[i],
                        color: colorHousingTypeCensus[cKey][0],
                        downstate: colorHousingTypeCensus[cKey][1],
                    });
                    sumHousing += keys[i];
                }

                //end data demo
                var key_rotation = 0;
                if(sum_key > 0){
                    dataArray.sort(function(a,b){
                        return a.name > b.name;
                    })
                    var sAg = 0;
                    key_rotation = 100*(dataArray[dataArray.length-1].y/sumHousing);
                    if(key_rotation >90){
                        var sAg = -dataArray[dataArray.length-1].y*9/5;
                    }
                    
                    para_over_housing.series[0].data = dataArray;
                    para_over_housing.series[0].size = chart_size_housing;
                    para_over_housing.plotOptions.pie.dataLabels.style.fontSize=pie_chart_data;
                    sum_key_housing = sum_key;
                    keys_housing = keys;
                    dataArrayHousing = dataArray;
                    para_over_housing.series[0].startAngle = sAg;
                    para_over_housing.tooltip.style.fontSize=font_size_tool_tip;
                    //console.log(dataArrayHousing);
                    chart4();
                    var width = $(".chart-list").width();
                    para_detail_housing.series[0].data = dataArray;
                    para_detail_housing.chart.width=width;
                    para_detail_housing.series[0].size = chart_size_housing;
                    para_detail_housing.plotOptions.pie.dataLabels.distance = pie_distance
                    para_detail_housing.plotOptions.pie.dataLabels.style.fontSize = detail_pie_chart_data;
                    sum_key_housing = sum_key;
                    keys_housing = keys;
                    occupieds_housing = occupieds;
                    vacants_housing = vacants;
                    dataArrayHousing = dataArray;
                    sAgHousing = sAg;
                    para_detail_housing.series[0].startAngle = sAg;
                    para_detail_housing.tooltip.style.fontSize=font_size_detail_tool_tip;
                    /*var chart_detail_housing = new Highcharts.Chart(para_detail_housing,function(obj){
                        var xr1=0,xr2=0,yr1=0,yr2=0;
                        var window_width = $(window).width();
                        var heigh_chart = $('#chart-detail_2').height();
                        var width_chart= width;
                        var size_chart=chart_size_housing.replace('%','');    

                        $.each(obj.series[0].data, function(i, point) {
                            //console.log(point);
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
                            //console.log('xr1:'+xr1+',xr2:'+xr2+',yr1:'+yr1+'yr2:'+yr2);
                            if(point.name=="Multifamily" || point.name=="Single Family - Multiple Unit"){
                                if(point.dataLabel.y <yr1 && (point.dataLabel.y+35) > yr1){
                                    var new_y = point.dataLabel.y -15;
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
                                if(point.dataLabel.x-100 < 0 && point.dataLabel.y < yr1){
                                    var new_x = point.dataLabel.x +12;
                                    var new_y = point.dataLabel.y +10;
                                    point.dataLabel.attr({x: new_x});
                                    point.dataLabel.attr({y: new_y});
                                }else if(point.dataLabel.x-100 < 0 && point.dataLabel.y > yr1){
                                    var new_x = point.dataLabel.x +25;
                                    point.dataLabel.attr({x: new_x});
                                }
                            }
                        })
                    });
                    createMenu('detail_2');*/
                }else{
                    $('#link_housing').addClass('hide');
                    $('#chart-2').highcharts({
                        chart:{
                            style:{
                                color:color,
                            }
                        },
                        credits: {
                            enabled: false
                        },
                        title: {
                            text: '',
                            style:{
                                color:color,
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
                    },function(){
                        chart4();
                    });
                }
                return 1;
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
        data: {type_chart: "age", url_des: $('#url_age').val()},
        success: function(res) {
            if (res.length > 0) {
                var categories = [];
                var arrFemale = [];
                var arrMale = [];
                var arrOMale = [];
                var arrOFemale = [];
                var arrData = [];
                var max_female=0;
                var max_male=0;
                var max = 0;
                // get total
                for (var i = 0; i < res.length; i++) {
                    var obj = res[i];
                    if (obj.group_10yr.indexOf("+") >= 0) {
                        res[i].group_10yr = obj.group_10yr.replace("+", ".99"); 
                    }else if (obj.group_10yr.indexOf("to") >= 0) {
                        res[i].group_10yr = obj.group_10yr.replace(" to ", ".");
                    } else if (obj.group_10yr.indexOf("Under") >= 0) {
                        res[i].group_10yr = obj.group_10yr.replace("Under ", "1.");
                    }  else {
                        res[i].group_10yr = obj.group_10yr;
                    }
                    if ('sex' in obj && obj.sex == "Female"
                            && obj.group_10yr != "Total Population"
                            && obj.group_10yr != "Total") {
                        arrOFemale.push(obj);
                    }
                    if ('sex' in obj && obj.sex == "Male"
                            && obj.group_10yr != "Total Population"
                            && obj.group_10yr != "Total") {
                        arrOMale.push(obj);
                    }
                }
                
                arrOFemale.sort(function(a,b){
                    return a.group_10yr - b.group_10yr;
                }) 
                arrOMale.sort(function(a,b){
                    return a.group_10yr - b.group_10yr;
                })
                for (var i = 0; i < arrOFemale.length; i++) {
                    var obj = arrOFemale[i];
                    if (obj.group_10yr.indexOf(".99") >= 0) {
                        var age = '≥' + obj.group_10yr.replace(".99", "");
                    }else if (obj.group_10yr.indexOf("1.") >= 0) {
                        var age = obj.group_10yr.replace("1.", "<");
                    }else if (obj.group_10yr.indexOf(".") >= 0) {
                        var age = obj.group_10yr.replace(".", " - ");
                    }else {
                        var age = obj.group_10yr;
                    }
                    
                    if ('sex' in obj && obj.sex == "Female"
                            && obj.group_10yr != "Total Population"
                            && obj.group_10yr != "Total") {
                        categories.push(age);
                    }
                    if ('sex' in obj && obj.sex == "Female"
                            && obj.group_10yr != "Total Population"
                            && obj.group_10yr != "Total") {
                        if(obj.population > max_female){
                            max_female = obj.population;
                        }
                        arrFemale.push(obj.population);
                    }
                }
                for (var i = 0; i < arrOMale.length; i++) {
                    var obj = arrOMale[i];
                    if (obj.group_10yr.indexOf(".99") >= 0) {
                        var age = '≥' + obj.group_10yr.replace(".99", "");
                    }else if (obj.group_10yr.indexOf("1.") >= 0) {
                        var age = obj.group_10yr.replace("1.", "<");
                    }else if (obj.group_10yr.indexOf(".") >= 0) {
                        var age = obj.group_10yr.replace(".", " - ");
                    }else {
                        var age = obj.group_10yr;
                    }
                    if ('sex' in obj && obj.sex == "Male"
                            && obj.group_10yr != "Total Population"
                            && obj.group_10yr != "Total") {
                        if(obj.population > max_male){
                            max_male = obj.population;
                        }
                        arrMale.push(obj.population);
                    }
                }
                for (var i = 0; i < res.length; i++) {
                    var obj = res[i];
                    if ('sex' in obj && obj.sex == "Total"
                            && obj.group_10yr == "Total Population") {
                        $('#set_location_popuplate').val(obj.population);
                        $('#total_population').html(Highcharts.numberFormat(obj.population, 0, '.', ','));
                    }

                }
                arrData = [
                    {
                        name: 'Men',
                        data: arrMale,
                        color: fontAgeCensus['men'][0]
                    },
                    {
                        name: 'Women',
                        data: arrFemale,
                        color: fontAgeCensus['women'][0]
                    }];
                arrData.sort(function(a,b){
                    return a.name - b.name;
                }) 
                max = max_female + max_male;
                
                if($('#set_location_popuplate').val()>0){
                    arrDataAge = arrData;
                    categories_over_age = categories;
                    max_over_age = max;
                    para_over_age.series = arrDataAge;
                    para_over_age.xAxis.categories = categories_over_age;
                    para_over_age.yAxis.max = max_over_age;
                    
                    para_over_age.legend.itemStyle.fontSize = overview_Legend;
                    para_over_age.legend.layout = layout_legend;
                    para_over_age.legend.align = align_legend;
                    para_over_age.legend.verticalAlign = vertical_align_legend;
                    para_over_age.legend.y = y_legend_age;
                    para_over_age.legend.x = x_legend_age;
                    para_over_age.legend.itemMarginBottom = 5;

                    para_over_age.chart.style.fontFamily = chart_fontFamily;
                    para_over_age.chart.style.fontSize = chart_fontSize;
                    para_over_age.chart.style.fontWeight = chart_fontWeight;
                    para_over_age.chart.spacingLeft = spacing_left_age;
                    para_over_age.chart.spacingBottom = spacing_bottom_age;
                    para_over_age.chart.marginLeft = margin_left_age;
                    para_over_age.chart.marginRight = margin_right_age;
                    para_over_age.chart.marginBottom = margin_bottom_age;
                    para_over_age.chart.marginTop = margin_top_age;

                    para_over_age.xAxis.title.offset = offset_x_title_age;
                    para_over_age.xAxis.title.style.fontSize = overview_axisLable;
                    para_over_age.xAxis.labels.style.fontSize = overview_axisData;
                    
                    para_over_age.yAxis.title.offset = offset_y_title_age;
                    para_over_age.yAxis.title.style.fontSize = overview_axisLable;
                    para_over_age.yAxis.offset =-8;
                    para_over_age.yAxis.labels.style.fontSize = overview_axisData;
                    para_over_age.tooltip.style.fontSize=font_size_tool_tip;
                    chart5();
                    var width = $(".chart-list").width();
                    para_detail_age.series= arrDataAge;
                    para_detail_age.plotOptions.series.pointWidth= point_width_age;
                    para_detail_age.xAxis.categories = categories_over_age;
                    para_detail_age.yAxis.max = max_over_age;
                    para_detail_age.chart.width=width;
                    
                    para_detail_age.legend.itemStyle.fontSize = detail_legend;
                    para_detail_age.legend.layout = layout_legend;
                    para_detail_age.legend.align = align_legend;
                    para_detail_age.legend.verticalAlign = vertical_align_legend;
                    para_detail_age.legend.y = y_legend_detail_age;
                    para_detail_age.legend.x = x_legend_detail_age;
                    para_detail_age.legend.itemMarginBottom = 0;
                    para_detail_age.legend.itemMarginTop = 10;
                    para_detail_age.legend.symbolPadding = 6;

                    para_detail_age.chart.style.fontFamily=chart_fontFamily;
                    para_detail_age.chart.style.fontSize=chart_fontSize;
                    para_detail_age.chart.style.fontWeight=chart_fontWeight;
                    para_detail_age.chart.spacingLeft = spacing_left_detail_age;
                    para_detail_age.chart.spacingBottom = spacing_bottom_detail_age;
                    para_detail_age.chart.marginLeft = margin_left_detail_age;
                    para_detail_age.chart.marginRight = margin_right_detail_age;
                    para_detail_age.chart.marginBottom = margin_bottom_detail_age;
                    para_detail_age.chart.marginTop = margin_top_detail_age;

                    para_detail_age.xAxis.title.offset = offset_x_title_detail_age;
                    para_detail_age.xAxis.labels.y = y_label_detail_age;
                    para_detail_age.xAxis.title.margin = margin_title;
                    para_detail_age.xAxis.title.style.fontSize = detail_axisLable;
                    para_detail_age.xAxis.labels.style.fontSize = detail_axisData;
                    para_detail_age.yAxis.title.offset = offset_y_title_detail_age;
                    para_detail_age.yAxis.labels.style.fontSize = detail_axisData;
                    para_detail_age.yAxis.title.style.fontSize = detail_axisLable;
                    para_detail_age.tooltip.style.fontSize=font_size_detail_tool_tip;
                    /*var chart_detail_age = new Highcharts.Chart(para_detail_age,function(objChart){});
                    createMenu('detail_3')*/
                }else{
                    $('#link_age').addClass('hide');
                    $('#chart-4').highcharts({
                        chart:{
                            style:{
                                color:color,
                            }
                        },
                        credits: {
                            enabled: false
                        },
                        title: {
                            text: '',
                            style:{
                                color:color,
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
                    },function(){
                        chart5();
                    });
                }
            }
            return 1;
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
            var tmp_san = [];
            var data_san = [];
            var sum_total_san = 0;
            if (res.length > 0) {
                for (var i = 0; i < res.length; i++) {
                    var obj = res[i];
                    var str = obj.income_group;
                    if (obj.income_group != "") {
                        if (str.indexOf("Less than") >= 0) {
                            res[i].index=0;
                        }
                        if (str.indexOf("or more") >= 0) {
                            res[i].index = res.length-1;
                        }
                    }
                }
                for(var i=1;i<=res.length-2;i++){
                    var max_res = 0;
                    var ind=-1;
                    for (var j = 0; j < res.length; j++) {
                        var obj = res[j];
                        var str = obj.income_group;
                        if (typeof(obj.index)=='undefined'){
                            var from = str.indexOf('$')+1;
                            var to = str.indexOf(',');
                            if(parseInt(str.substring(from, to)) > max_res){
                                max_res = str.substring(from, to);
                                ind = j;
                            }
                        } 
                    }
                    res[ind].index = (res.length-1)-i;
                }
                res.sort(function(a,b){
                    return a.index - b.index;
                })
                
                var location = $('#pck_location').val();
                var locationLabel = location;
                var sandiegoLabel = 'San Diego Region';
                var categories = [];
                var data = [];
                var data_value_one = [];
                var data_value_two = [];
                var tmp = [];
                var sum_total = 0;
                for (var i = 0; i < res.length; i++) {
                    var obj = res[i];
                    if (obj.income_group != "") {
                        var str = obj.income_group;
                        if (str.indexOf("Less than") >= 0) {
                            str = str.replace("Less than ", "<")
                        }
                        if (str.indexOf("or more") >= 0) {
                            str = "≥" + str;
                            str = str.replace("or more", "")
                        }
                        if (str.indexOf(" to ") >= 0) {
                            str = str.replace(" to ", "-")
                        }
                        while (str.indexOf(",000") >= 0) {
                            str = str.replace(",000", "K")
                        }
                        while (str.indexOf(",999") >= 0) {
                            str = str.replace(",999", ".9K")
                        }
                        categories.push(str.toUpperCase());
                    }
                    if (obj.households>=0) {
                        tmp.push(obj.households);
                        sum_total += obj.households;
                    }

                }
                var max=0;
                var max_san = 0;
                for (var i = 0; i < tmp.length; i++) {
                    if(sum_total > 0) {
                        var val = precise_round((tmp[i] / sum_total) * 100, 1);
                        data.push(precise_round((tmp[i] / sum_total) * 100, 1));
                    }else{
                        var val=0;
                        data.push(0);
                    }
                    if(val>max){
                        max = val;
                    }
                    
                    data_value_one.push(tmp[i]);
                }
                
                $.ajax({
                    url: api_url,
                    type: "POST",
                    dataType: "json",
                    data: {type_chart: "income", url_des: $('#url_san_income').val()},
                    success: function(res_san) {
                        if (res_san.length > 0) {
                            for (var i = 0; i < res_san.length; i++) {
                                var obj = res_san[i];
                                if (obj.households) {
                                    tmp_san.push(obj.households);
                                    sum_total_san += obj.households;
                                }

                            }
                            
                            for (var i = 0; i < tmp_san.length; i++) {
                                if(sum_total_san > 0){
                                    var val_san = precise_round((tmp_san[i] / sum_total_san) * 100, 1);
                                    if(val_san > max){
                                        max_san = val_san;
                                    }
                                    data_san.push(precise_round((tmp_san[i] / sum_total_san) * 100, 1));
                                }else{
                                    data_san.push(0);
                                }
                                data_value_two.push(tmp_san[i]);
                            }
                        }
                        var arrData = [{
                                name: locationLabel.toUpperCase(),
                                data: data,
                                color: fontIncomeCensus['location'][0],
                                data_value:data_value_one
                            },
                            {
                                name: sandiegoLabel,
                                data: data_san,
                                color: fontIncomeCensus['sandiego'][0],
                                data_value:data_value_two
                            }];
                        arrData.sort(function(a,b){
                            return a.name - b.name;
                        })
                        //console.log(arrData);
                        if(max_san > max){
                            max = max_san;
                        }
                        var window_width = $(window).width();
                        arrDataIncome = arrData;
                        max_over_income = max;
                        categories_over_income = categories;
                        para_over_income.series = arrDataIncome;
                        para_over_income.xAxis.categories = categories_over_income;
                        para_over_income.yAxis.max = max_over_income;
                        locationLabelIncome = locationLabel;
                        sandiegoLabelIncome = sandiegoLabel;
                        
                        para_over_income.legend.itemStyle.fontSize = overview_Legend;
                        para_over_income.legend.layout = layout_legend;
                        para_over_income.legend.align = align_legend;
                        para_over_income.legend.verticalAlign = vertical_align_legend;
                        para_over_income.legend.y = y_legend_income;
                        para_over_income.legend.x = x_legend_income;
                        para_over_income.legend.itemMarginBottom = 5;

                        para_over_income.chart.style.fontFamily=chart_fontFamily;
                        para_over_income.chart.style.fontSize=chart_fontSize;
                        para_over_income.chart.style.fontWeight=chart_fontWeight;
                        
                        para_over_income.chart.spacingLeft = spacing_left_income;
                        para_over_income.chart.spacingBottom = spacing_bottom_income;
                        para_over_income.chart.marginLeft = margin_left_income;
                        para_over_income.chart.marginRight = margin_right_income;
                        para_over_income.chart.marginBottom = margin_bottom_income;
                        para_over_income.chart.marginTop = margin_top_income;

                        para_over_income.xAxis.title.offset = offset_x_title_income;
                        para_over_income.xAxis.title.style.fontSize = overview_axisLable;
                        para_over_income.xAxis.labels.style.fontSize = overview_axisData;
                        para_over_income.yAxis.title.offset = offset_y_title_income;
                        para_over_income.yAxis.title.y = y_title_income;
                        para_over_income.yAxis.offset =-8;
                        para_over_income.yAxis.labels.style.fontSize = overview_axisData;
                        para_over_income.yAxis.title.style.fontSize = overview_axisLable;
                        if(checkInternetExplorer()){
                            var keyToDelete = "lineHeight";
                            delete  para_over_income.legend.itemStyle[keyToDelete];
                        }else{
                            para_over_income.legend.itemStyle.lineHeight='11%';
                        }
                        if(window_width < 1024){
                            var keyToDelete = "itemWidth";
                            delete  para_over_income.legend.itemStyle[keyToDelete];
                        }else{
                            para_over_income.legend.itemWidth=80;
                        }
                        para_over_income.tooltip.style.fontSize=font_size_tool_tip;
                        if(window_width < 768){
                            var flag_ethnicity = false;
                            var flag_housing = false;
                            var flag_age = false;
                            var flag_income = false;
                            $('#loading-section').addClass("hide");
                            $('.site-header').css({"z-index":''});
                            $('#overview-body').removeClass("visibility");
                            
                            var ethnicity = $('#chart-1');
                            var ethnicityHeight = ethnicity.offset().top;
                            var housing = $('#chart-2');
                            var housingHeight = housing.offset().top;
                            var age = $('#chart-4');
                            var ageHeight = age.offset().top;
                            var income = $('#chart-5');
                            var incomeHeight = income.offset().top;
                            if(isMobile.any()){
								$(window).on('scroll',function(e){
									var st = $(window).scrollTop();
									if(st > (ethnicityHeight-conScroll-50)){
										if(!flag_ethnicity){
											drawChart("ethnicity");
											flag_ethnicity = true;
										}
                                        
									}
									if(st > (housingHeight-conScroll-50)){
										if(!flag_housing){
											drawChart("housing");
											flag_housing = true;
										}
									}
									if(st > (ageHeight-conScroll)){
										if(!flag_age){
											drawChart("age");
											flag_age = true;
										}
									}
									if(st > (incomeHeight-conScroll)){
										if(!flag_income){
											drawChart("income");
											flag_income = true;
										}
									}
								});
							}else{
								$('.st-content').on('scroll',function(e){
									var st = $('.st-content').scrollTop();
									if(st > (ethnicityHeight-conScroll-50)){
										if(!flag_ethnicity){
											drawChart("ethnicity");
											flag_ethnicity = true;
										}
									}
									if(st > (housingHeight-conScroll-50)){
										if(!flag_housing){
											drawChart("housing");
											flag_housing = true;
										}
									}
									if(st > (ageHeight-conScroll)){
										if(!flag_age){
											drawChart("age");
											flag_age = true;
										}
									}
									if(st > (incomeHeight-conScroll)){
										if(!flag_income){
											drawChart("income");
											flag_income = true;
										}
									}
								});
							}
                        }else{
                            var chart_over_income = new Highcharts.Chart(para_over_income,function(objChart){  
                                $('#loading-section').addClass("hide");
                                $('.site-header').css({"z-index":''});
                                $('#overview-body').removeClass("visibility");

                                drawChart("ethnicity");
                                drawChart("housing");
                                drawChart("age");
                            });
                        }
                        
                        var width = $(".chart-list").width();
                        if(window_width >=1024){
                            para_detail_income.plotOptions.series.pointWidth = point_width_detail_income;
                            para_detail_income.xAxis.labels.rotation=0;
                        }else{
                            var keyToDelete = "pointWidth";
                            delete  para_detail_income.plotOptions.series[keyToDelete];
                            keyToDelete = "rotation";
                            delete  para_detail_income.xAxis.labels[keyToDelete];
                        }
                        //para_detail_income.plotOptions.series.pointWidth= point_width_detail_income;
                        para_detail_income.series = arrDataIncome;
                        para_detail_income.xAxis.categories = categories_over_income;
                        para_detail_income.chart.width=width;
                        
                        para_detail_income.legend.itemStyle.fontSize = detail_legend;
                        para_detail_income.legend.layout = layout_legend;
                        para_detail_income.legend.align = align_legend;
                        para_detail_income.legend.verticalAlign = vertical_align_legend;
                        para_detail_income.legend.y = y_legend_detail_income;
                        para_detail_income.legend.x = x_legend_detail_income;
                        para_detail_income.legend.itemMarginBottom = 0;
                        para_detail_income.legend.itemMarginTop = 10;
                        para_detail_income.legend.symbolPadding = 6;

                        para_detail_income.chart.style.fontFamily=chart_fontFamily;
                        para_detail_income.chart.style.fontSize=chart_fontSize;
                        para_detail_income.chart.style.fontWeight=chart_fontWeight;
                        para_detail_income.chart.spacingLeft = spacing_left_detail_income;
                        para_detail_income.chart.spacingBottom = spacing_bottom_detail_income;
                        para_detail_income.chart.marginLeft = margin_left_detail_income;
                        para_detail_income.chart.marginRight = margin_right_detail_income;
                        para_detail_income.chart.marginBottom = margin_bottom_detail_income;
                        para_detail_income.chart.marginTop = margin_top_detail_income;

                        para_detail_income.xAxis.title.offset = offset_x_title_detail_income;
                        para_detail_income.xAxis.labels.style.fontSize = detail_axisData;
                        para_detail_income.xAxis.labels.y = y_label_detail_income;
                        para_detail_income.xAxis.title.margin = margin_title;
                        para_detail_income.xAxis.title.style.fontSize = detail_axisLable;
                        para_detail_income.yAxis.title.offset = offset_y_title_detail_income;
                        para_detail_income.yAxis.labels.style.fontSize = detail_axisData;
                        para_detail_income.yAxis.title.style.fontSize = detail_axisLable;
                        para_over_income.tooltip.style.fontSize=font_size_detail_tool_tip;
                        /*var chart_detail_income = new Highcharts.Chart(para_detail_income,function(objChart){});
                        createMenu('detail_4')*/
                        if(isMobile.any()){
                            $('#txtEmailReport').selectmenu('enable');
                            $('#txtEmailReport').selectmenu('refresh');
                            $('#txtDownloadReport').selectmenu('enable');
                            $('#txtDownloadReport').selectmenu('refresh');
                        }else{
                            $('#email_report').prop('disabled',false);
                            $('#email_report').selectpicker('refresh');
                            $('#download_report').prop('disabled',false);
                            $('#download_report').selectpicker('refresh');
                        }
                    }
                });
                return 1;
            }
        },
        error: function(request, status, error) {
            console.log($('#url_income').val());
            console.log(request.responseText);
        }
    });

}
                