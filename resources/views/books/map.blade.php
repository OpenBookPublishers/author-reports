<style>
  #chartdiv {
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
    width: 100%;
    height:100%;
  }
</style>

<div id="chartdiv"></div>

<script src="https://www.amcharts.com/lib/4/core.js"></script>
<script src="https://www.amcharts.com/lib/4/charts.js"></script>
<script src="https://www.amcharts.com/lib/4/maps.js"></script>
<script src="https://www.amcharts.com/lib/4/geodata/worldLow.js"></script>
<script src="https://www.amcharts.com/lib/4/themes/animated.js"></script>
<script>

// Themes begin
am4core.useTheme(am4themes_animated);
// Themes end

var chart = am4core.create("chartdiv", am4maps.MapChart);


try {
    chart.geodata = am4geodata_worldLow;
}
catch (e) {
    chart.raiseCriticalError(new Error("Map geodata could not be loaded. Please download the latest <a href=\"https://www.amcharts.com/download/download-v4/\">amcharts geodata</a> and extract its contents into the same directory as your amCharts files."));
}

chart.projection = new am4maps.projections.Mercator();

// zoomout on background click
chart.chartContainer.background.events.on("hit", function () { zoomOut() });

var colorSet = new am4core.ColorSet();
var morphedPolygon;

// map polygon series (countries)
var polygonSeries = chart.series.push(new am4maps.MapPolygonSeries());
polygonSeries.useGeodata = true;
// Remove Antarctica
polygonSeries.exclude = ["AQ"];

// country label
var countryLabel = chart.chartContainer.createChild(am4core.Label);
countryLabel.text = "";
countryLabel.fill = am4core.color("#7678a0");
countryLabel.fontSize = 20;

countryLabel.hiddenState.properties.dy = 1000;
countryLabel.defaultState.properties.dy = 0;
countryLabel.valign = "top";
countryLabel.align = "left";
countryLabel.paddingRight = 50;
countryLabel.hide(0);

var totalReadership = 0;
var totalNonGeographical = 0;
var knownPercentage = 0.00;
chart.dataSource.url = apiEndp + '/events?filter=work_uri:' + workUri + '&aggregation=country_uri,measure_uri';
chart.dataSource.events.on("parseended", function(ev) {
    var result = ev.target.data.data;
    chart.dataSource.data = data;
    var data = [];
    var measureData = [];
    for (var country in result) {
        var countryTotal = 0;
        var measures = [];
        var countryCode = result[country]['country_code'];
        for (var measure in result[country]['data']) {
            var value = result[country]['data'][measure]['value'];
            var source = result[country]['data'][measure]['source'];
            source = source.replace('Open Book Publishers', 'OBP');
            var type = result[country]['data'][measure]['type'];
            var category = source + ' ' + type;
            measures.push({value: value, category: category});
            countryTotal += value;
        }
        totalReadership += countryTotal;
        if (!countryCode) {
            totalNonGeographical += countryTotal;
            continue;
        }
        measureData[countryCode] = measures;
        data.push({id: countryCode, value: countryTotal});
    }
    chart.dataSource.data = measureData;
    polygonSeries.data = data;
    knownPercentage = (((totalReadership - totalNonGeographical) * 100) / totalReadership).toFixed(2);
    countryLabel.text = "Geographical data is available for " + knownPercentage + "% of our total figures for this book.";
    countryLabel.show();

    return data;
});

chart.exporting.menu = new am4core.ExportMenu();

// country area look and behavior
var polygonTemplate = polygonSeries.mapPolygons.template;
polygonTemplate.strokeOpacity = 1;
polygonTemplate.stroke = am4core.color("#fff");
polygonTemplate.fill = am4core.color("#ccc");
polygonTemplate.fillOpacity = 0.9;
polygonTemplate.tooltipText = "{name}: {value}";

// Add heat rule
polygonSeries.heatRules.push({
  "property": "fill",
  "target": polygonTemplate,
  "min": am4core.color("#058eff"),
  "max": am4core.color("#012a4c")
})

// desaturate filter for countries
var desaturateFilter = new am4core.DesaturateFilter();
desaturateFilter.saturation = 0.25;
polygonTemplate.filters.push(desaturateFilter);

// set fillOpacity to 1 when hovered
var hoverState = polygonTemplate.states.create("hover");
hoverState.properties.fillOpacity = 1;

// what to do when country is clicked
chart.seriesContainer.events.on("hit", function (event) {
    zoomOut();
})

// allow closing chart when pressing escape
document.onkeydown = function(evt) {
    evt = evt || window.event;
    var isEscape = false;
    if ("key" in evt) {
        isEscape = (evt.key === "Escape" || evt.key === "Esc");
    } else {
        isEscape = (evt.keyCode === 27);
    }
    if (isEscape) {
        zoomOut();
    }
};

// what to do when country is clicked
polygonTemplate.events.on("hit", function (event) {
    zoomOut();
    event.target.zIndex = 1000000;
    selectPolygon(event.target);
})

// Pie chart
var pieChart = chart.seriesContainer.createChild(am4charts.PieChart);
// Set width/heigh of a pie chart for easier positioning only
pieChart.width = 100;
pieChart.height = 100;
pieChart.hidden = true; // can't use visible = false!

// because defauls are 50, and it's not good with small countries
pieChart.chartContainer.minHeight = 1;
pieChart.chartContainer.minWidth = 1;

var pieSeries = pieChart.series.push(new am4charts.PieSeries());
pieSeries.dataFields.value = "value";
pieSeries.dataFields.category = "category";
pieSeries.data = [{ value: 100, category: "First" }, { value: 20, category: "Second" }, { value: 10, category: "Third" }];

var dropShadowFilter = new am4core.DropShadowFilter();
dropShadowFilter.blur = 4;
pieSeries.filters.push(dropShadowFilter);

var sliceTemplate = pieSeries.slices.template;
sliceTemplate.fillOpacity = 1;
sliceTemplate.strokeOpacity = 0;

var activeState = sliceTemplate.states.getKey("active");
activeState.properties.shiftRadius = 0; // no need to pull on click, as country circle under the pie won't make it good

var sliceHoverState = sliceTemplate.states.getKey("hover");
sliceHoverState.properties.shiftRadius = 0; // no need to pull on click, as country circle under the pie won't make it good

// we don't need default pie chart animation, so change defaults
var hiddenState = pieSeries.hiddenState;
hiddenState.properties.startAngle = pieSeries.startAngle;
hiddenState.properties.endAngle = pieSeries.endAngle;
hiddenState.properties.opacity = 0;
hiddenState.properties.visible = false;

// series labels
var labelTemplate = pieSeries.labels.template;
labelTemplate.nonScaling = true;
labelTemplate.fill = am4core.color("#FFFFFF");
labelTemplate.fontSize = 10;
labelTemplate.background = new am4core.RoundedRectangle();
labelTemplate.background.fillOpacity = 0.9;
labelTemplate.padding(4, 9, 4, 9);
labelTemplate.background.fill = am4core.color("#7678a0");

// we need pie series to hide faster to avoid strange pause after country is clicked
pieSeries.hiddenState.transitionDuration = 200;

// select polygon
function selectPolygon(polygon) {
    // do not transform if we don't have data for this country
    readership = polygon.dataItem.dataContext.value;
    if (morphedPolygon != polygon && readership) {
        var animation = pieSeries.hide();
        if (animation) {
            animation.events.on("animationended", function () {
                morphToCircle(polygon);
            })
        }
        else {
            morphToCircle(polygon);
        }
    }
}

// fade out all countries except selected
function fadeOut(exceptPolygon) {
    for (var i = 0; i < polygonSeries.mapPolygons.length; i++) {
        var polygon = polygonSeries.mapPolygons.getIndex(i);
        if (polygon != exceptPolygon) {
            polygon.defaultState.properties.fillOpacity = 0.5;
            polygon.animate([{ property: "fillOpacity", to: 0.5 }, { property: "strokeOpacity", to: 1 }], polygon.polygon.morpher.morphDuration);
        }
    }
}

function zoomOut() {
    if (morphedPolygon) {
        pieSeries.hide();
        morphBack();
        fadeOut();
        morphedPolygon = undefined;
    }
}

function morphBack() {
    if (morphedPolygon) {
        morphedPolygon.polygon.morpher.morphBack();
        var dsf = morphedPolygon.filters.getIndex(0);
        dsf.animate({ property: "saturation", to: 0.25 }, morphedPolygon.polygon.morpher.morphDuration);
    }
}

function morphToCircle(polygon) {


    var animationDuration = polygon.polygon.morpher.morphDuration;
    // if there is a country already morphed to circle, morph it back
    morphBack();
    // morph polygon to circle
    polygon.toFront();
    polygon.polygon.morpher.morphToSingle = true;
    var morphAnimation = polygon.polygon.morpher.morphToCircle();

    polygon.strokeOpacity = 0; // hide stroke for lines not to cross countries

    polygon.defaultState.properties.fillOpacity = 1;
    polygon.animate({ property: "fillOpacity", to: 1 }, animationDuration);

    // animate desaturate filter
    var filter = polygon.filters.getIndex(0);
    filter.animate({ property: "saturation", to: 1 }, animationDuration);

    // save currently morphed polygon
    morphedPolygon = polygon;

    // fade out all other
    fadeOut(polygon);

    if (morphAnimation) {
        morphAnimation.events.on("animationended", function () {
            zoomToCountry(polygon);
        })
    }
    else {
        zoomToCountry(polygon);
    }
}

function zoomToCountry(polygon) {
    var zoomAnimation = chart.zoomToMapObject(polygon, 2.2, true);
    if (zoomAnimation) {
        zoomAnimation.events.on("animationended", function () {
            showPieChart(polygon)
        })
    }
    else {
        showPieChart(polygon)
    }
}


function showPieChart(polygon) {
    polygon.polygon.measure();
    var radius = polygon.polygon.measuredWidth / 2 * polygon.globalScale / chart.seriesContainer.scale;
    pieChart.width = radius * 2;
    pieChart.height = radius * 2;
    pieChart.radius = radius;

    var centerPoint = am4core.utils.spritePointToSvg(polygon.polygon.centerPoint, polygon.polygon);
    centerPoint = am4core.utils.svgPointToSprite(centerPoint, chart.seriesContainer);

    pieChart.x = centerPoint.x - radius;
    pieChart.y = centerPoint.y - radius;

    var fill = polygon.fill;
    var desaturated = fill.saturate(0.3);

    var countryId = polygon.dataItem.dataContext.id;
    pieSeries.data = chart.dataSource.data[countryId];

    for (var i = 0; i < pieSeries.dataItems.length; i++) {
        var dataItem = pieSeries.dataItems.getIndex(i);
        dataItem.value = Math.round(Math.random() * 100);
        dataItem.slice.fill = am4core.color(am4core.colors.interpolate(
            fill.rgb,
            am4core.color("#ffffff").rgb,
            0.2 * i
        ));

        dataItem.label.background.fill = desaturated;
        dataItem.tick.stroke = fill;
    }

    pieSeries.show();
    pieChart.show();
}
</script>
