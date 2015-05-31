<!DOCTYPE html>
<head>
  <meta charset='utf-8'>
  <meta content='IE=edge,chrome=1' http-equiv='X-UA-Compatible'>
  <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
  <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300' rel='stylesheet' type='text/css'>
  <link rel="stylesheet" type="text/css" href="/style.css" media="screen" />
  <script src="/skycons.js"></script>
  <script>
    function startTime() {
        var today=new Date();
        var h=today.getHours();
        var m=today.getMinutes();
        var s=today.getSeconds();
        m = checkTime(m);
        s = checkTime(s);
        document.getElementById('clock').innerHTML = h+":"+m+":"+s;
        var t = setTimeout(function(){startTime()},500);
    }
    function checkTime(i) {
        if (i<10) {i = "0" + i};  // add zero in front of numbers < 10
        return i;
    }
  </script>

  <?php $WEATHER_API_KEY = "dd4adf301215e0ec1def6b9eba5107d8"; ?>
  <?php $GUARDIAN_API_KEY = "uz4qna4uwg5vwhya5xmk37wg"; ?>
</head>

<body onload="startTime()">

<?php // WEATHER
  $forecast_json = file_get_contents('https://api.forecast.io/forecast/'.$WEATHER_API_KEY.'/53.5361435,2.0936774?units=si');
  $forecast_php = json_decode($forecast_json, true);

  $current_summary = $forecast_php["currently"]["summary"];
  $temp = $forecast_php["currently"]["temperature"];
  $feels_like = $forecast_php["currently"]["apparentTemperature"];
  $precip_prob = $forecast_php["currently"]["precipProbability"];
  $precip_inten = $forecast_php["currently"]["precipIntensity"];
  $cloud_cover = $forecast_php["currently"]["cloudCover"];
  $pressure = $forecast_php["currently"]["pressure"];
  $detailed_summary = $forecast_php["minutely"]["summary"];
?>


<?php // NEWS
  $no_articles = 1;

  show_news('world', $no_articles, $GUARDIAN_API_KEY);
  show_news('technology', $no_articles, $GUARDIAN_API_KEY);
  show_news('politics', $no_articles, $GUARDIAN_API_KEY);

  function show_news($section, $no_articles, $GUARDIAN_API_KEY) {
    $news_json = file_get_contents('http://content.guardianapis.com/'. $section .'?show-editors-picks=true&page-size='.$no_articles.'&api-key='.$GUARDIAN_API_KEY);
    $news_array = json_decode($news_json, true);
  
    foreach ($news_array['response']['results'] as &$article) {
      echo "<p>" . $article['webTitle'] . "</p>";
    }
    echo "<br><br>";
  }

?>


<div class="table">

  <div class="row clock-temp">
    <div class="left" id="clock">
    </div>

    <div class="right">
      <canvas id="weather-icon" width="128" height="128"></canvas>

      <script>
        var skycons = new Skycons({"color": "white"});
        skycons.add("weather-icon", Skycons.PARTLY_CLOUDY_DAY);
        skycons.play();
      </script>

      <div class="temp">
        <?php echo round($temp, 1); ?>°C
      </div>
    </div>

  </div>

  <div class="row">
    <!-- <p><?php echo $current_summary; ?></p> -->
    <p><?php echo $detailed_summary; ?></p>
    <!-- <p>Feels like <?php echo $feels_like; ?>°C</p> -->
    <p><?php echo $precip_prob*100; ?>% chance of rain</p>
    <!-- <p>How heavy is it? <?php echo $precip_inten; ?></p> -->
    <p><?php echo $cloud_cover*100; ?>% cloud cover</p>
    <!-- <p>Pressure: <?php echo $pressure; ?></p> -->
  </div>

  
  <div class="row">
    <div class="left">
    </div>

    <div class="right">
    </div>
  </div>


  <div class="row">
    <div class="left">
    </div>

    <div class="right">
    </div>
  </div>

</div>


</body>





