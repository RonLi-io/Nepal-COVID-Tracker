<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>
<!DOCTYPE html>
<html>

<head>

	<meta charset="utf-8">
	<title>COVID-19 Tracker</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<script type="text/javascript" src="scripts/jquery-1.8.2.min.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="css/nunito-font.css">
	<link rel="stylesheet" type="text/css" href="css/style.css" />
	<link rel="stylesheet" type="text/css" href="css/autocompleteStyle.css" />

	<!--Loading Spinner-->
	<link rel='stylesheet' type="text/css" href='nprogress/nprogress.css' />

	<script src='nprogress/nprogress.js'></script>

	<!--AutoComplete-->
	<script type="text/javascript" src="scripts/jquery.autocomplete.js"></script>
	<!--Chart -->
	<script src="http://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.2/raphael-min.js"></script>
	<script src="http://cdnjs.cloudflare.com/ajax/libs/prettify/r224/prettify.min.js"></script>
	<script src="morris/morris.js"></script>
	<link rel="stylesheet" type="text/css" src="morris/morris.css" />

	<script src="scripts/util.js"></script>

</head>

<body class="form-v9">
	<div class="page-content">
		<div class="form-v9-content">
			<form class="form-detail" onsubmit="return false;">

				<h2>COVID-19 Tracker<sub style="font-size:10px;">🇳🇵</sub></h2>
				<b id="bannerInfo"></b>
				<div class="form-row-total">
					<div class="form-row">
						<input type="text" style="padding-left: 20px;" name="country" id="autocomplete-StateName" class="input-text" placeholder="District Name" required onfocus="clearDist()">
					</div>
				</div>
				<div class="form-row-last" style="height:auto;">
					<div id="selction-ajax" style="margin-bottom:10px;"></div>
					<div id="graph"></div>
				</div>
				<div class="form-row-last" style="margin-top: 20px;">
					<br />
					Powered By <a href="https://www.ronli.xyz/" style="text-decoration:none">
						<b style="color: white; line-height: normal; font-size: 30px; text-shadow: rgb(0, 0, 0) 0px 14px 24px; -webkit-text-fill-color: transparent; -webkit-text-stroke-width: 1px;">
							R<i class="fa fa-cog fa-spin" style="font-size:20px;text-shadow:none;"></i>nLi
						</b>
					</a>
				</div>

			</form>
		</div>
	</div>
	<div class="container">
		<pre> <a style="color: #b3e0ff;" href="https://www.who.int/emergencies/diseases/novel-coronavirus-2019" target="_blank">COVID-19?</a></pre>
		<pre> <a style="color: #b3e0ff;" href="https://www.who.int/emergencies/diseases/novel-coronavirus-2019/advice-for-public" target="_blank">Advice for Public</a></pre>
		<pre> <a style="color: #b3e0ff;" href="https://covid19.mohp.gov.np/#/covidMap" target="_blank">Testing Centres</a></pre>
		<br />
		<pre><i>Data Source: <a style="color: #007acc;" href="https://en.wikipedia.org/wiki/2020_coronavirus_pandemic_in_Nepal" target="_blank" title="Wikipedia">Wikipedia</a></i></pre>
	</div>

	<script>
		var returnRef = null;
		var globalResult = [];
		var defaultStartColor = "#86C232";
		var testingCentreUrl = "https://covid19.mohp.gov.np/#/covidMap";
		NProgress.start();
		var isMobile = false;

		if (/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent) ||
			/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0, 4))) {
			isMobile = true;
		}
		$(document).ready(function() {
			initSetup();

		});

		function setupLookUp(localDist){
			$('#autocomplete-StateName').autocomplete({
			lookup: localDist,
			onSelect: function(suggestion) {
				getTotalofStates(suggestion.value); //suggestion.data
			}
		});
		}

		function initSetup() {
			$.getJSON("assets/response.json", function(data) {
				NProgress.done();
				setupLookUp( data.morris );
				globalResult = data;
				items = globalResult.districts;
				createDonut(JSON.stringify(items), defaultStartColor, null, true);
				$("#bannerInfo").html("\
				<span id='mobReact'><span style='color:#a9c680;'> Total Case(s) : </span> <span style='color:#86c232;font-size:30px;'>" + globalResult.totalCases + "</span>,</span> \
				<span id='mobReact'><span style='color:#a9c680;'> Active Case(s): </span> <span style='color:#86c232;font-size:30px;' >" + globalResult.activeCases + "</span>,</span> \
				<span id='mobReact'><span style='color:#a9c680;'> Recovered Case(s): </span> <span style='color:#86c232;font-size:30px;'>" + globalResult.curedCases + "</span>,</span> \
				<span style='display: block;'><span style='color:#a9c680;'> Death Case(s): </span> <span style='color:#86c232;font-size:30px;'>" + globalResult.deathCases + "</span></span> \
				<br/><br/> ");
				setHTML("Nepal",globalResult.totalCases);
			});
		}

		function getTotalofStates(district) {
			let totalPositive = "Unknwon";
			items = globalResult.districts;
			items.every(function(element, i) {
				if (element.District === district) {
					totalPositive = element.Cases
					createDonut(JSON.stringify(items), defaultStartColor, i, true);
					return false;
				}
				return true;
			});
			setHTML(district, totalPositive);
		}

		function replaceNA(str) {
			if (str == "n/a")
				str = "Unknown";

			return str;
		}

		function clearDist() {
			$("#autocomplete-StateName").val("");
			$('#selction-ajax').css('visibility', 'hidden');
		}

		function createDonut(dataArray, startColorHex = defaultStartColor, indexHighlight, repairRequired = false) {

			$('#selction-ajax').css('visibility', '');
			dataArray = JSON.parse(dataArray);
			tempDataArray = []
			colorsArray = [];
			for (i = 0; i < dataArray.length; i++) {
				if (repairRequired) {
					tempDataArray.push({
						value: dataArray[i]['Cases'],
						label: dataArray[i]['District']
					});
				} else {
					tempDataArray.push({
						value: dataArray[i]['data'],
						label: dataArray[i]['value']
					});
				}
				colorsArray.push(colorIncrementer(startColorHex, 25 * i));
			}

			$('#graph').html(""); //Clearing the graph first;

			returnRef = Morris.Donut({
				element: 'graph',
				data: tempDataArray,
				colors: colorsArray,
				labelColor: "#fff",
				resize: false,
				formatter: function(x) {
					return x
				}
			}).on('click', function(i, row) {
				if (!repairRequired) {
					setHTML(replaceNA(row.label), row.value, true, $("#autocomplete-StateName").val());
				} else {
					setHTML(replaceNA(row.label), row.value);
				}
				if (isMobile) {
					startTick(i);
				}
			});

			if (indexHighlight != null) {
				returnRef.select(indexHighlight);
				if (isMobile) {
					startTick(indexHighlight);
				}
			}

		}
		var tickVar;

		function startTick(index) {
			clearInterval(tickVar);
			tickVar = window.setInterval(function() {
				returnRef.select(index);
			}, 500);
		}

		function setHTML(stateName, totalPositive, showHelp = true, distStateName = stateName) {
			if (showHelp) {
				$('#selction-ajax').html("Total Case(s) in " + stateName + " : " + totalPositive + " <br/> 📞 Helpline: <a style='color:#0399f7;' href='tel:+9779851255834'>+9779851255834</a>");
			} else {
				tempx = $("#selction-ajax").html();
				$('#selction-ajax').html("Total Case(s) in " + stateName + " : " + totalPositive + "  <br/> " + tempx.split('<br>')[1]);
			}
		}

	</script>
</body>

</html>
