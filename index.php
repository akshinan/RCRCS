<?php
session_start();

ini_set('session.cookie_secure', true);
ini_set('session.cookie_httponly', true);
ini_set('session.use_strict_mode', true);
session_regenerate_id(true);

$config = require 'config.php';
$conn = new mysqli($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name']);
if ($conn->connect_error) {
    error_log("Connection failed: " . $conn->connect_error);
    die("Service unavailable. Please try again later.");
}

function logUserActivity($conn) {
    $ipAddress = $_SERVER['REMOTE_ADDR'];
    $deviceInfo = getDeviceInfo();
    $geolocation = getGeolocation($ipAddress);
    $referralSource = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'Direct';
    $browserInfo = $_SERVER['HTTP_USER_AGENT'];
    $screenResolution = isset($_POST['screen_resolution']) ? $_POST['screen_resolution'] : 'Unknown';
    $operatingSystem = php_uname('s');
    $languagePreferences = $_SERVER['HTTP_ACCEPT_LANGUAGE'];

    $clickPatterns = 'To be captured via JavaScript';
    $scrollDepth = 'To be captured via JavaScript';
    $conversionData = 'To be defined';
    $utmParameters = isset($_POST['utm_parameters']) ? $_POST['utm_parameters'] : 'None';
    $heatmapData = 'To be captured via tools like Hotjar';
    $sessionReplay = 'To be captured via tools like Hotjar';

    $stmt = $conn->prepare("INSERT INTO user_activity_logs (
        ip_address, device_info, geolocation, referral_source, browser_info,
        screen_resolution, operating_system, language_preferences, click_patterns,
        scroll_depth, conversion_data, utm_parameters, heatmap_data, session_replay
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    $stmt->bind_param("ssssssssssssss", $ipAddress, $deviceInfo, $geolocation, $referralSource, $browserInfo, $screenResolution, $operatingSystem, $languagePreferences, $clickPatterns, $scrollDepth, $conversionData, $utmParameters, $heatmapData, $sessionReplay);
    $stmt->execute();
    $stmt->close();
}

function getDeviceInfo() {
    $userAgent = $_SERVER['HTTP_USER_AGENT'];
    if (preg_match('/mobile/i', $userAgent)) {
        return 'Mobile';
    } elseif (preg_match('/tablet/i', $userAgent)) {
        return 'Tablet';
    } else {
        return 'Desktop';
    }
}

function getGeolocation($ipAddress) {
    $apiUrl = "http://ip-api.com/json/$ipAddress";
    $response = file_get_contents($apiUrl);
    $data = json_decode($response, true);

    if ($data['status'] === 'success') {
        return $data['city'] . ', ' . $data['country'];
    }
    return 'Unknown';
}

logUserActivity($conn);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>Royal College Red Cross Society</title>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport"/>
<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet"/>
<link href="css/open-iconic-bootstrap.min.css" rel="stylesheet"/>
<link href="css/animate.css" rel="stylesheet"/>
<link href="css/owl.carousel.min.css" rel="stylesheet"/>
<link href="css/owl.theme.default.min.css" rel="stylesheet"/>
<link href="css/magnific-popup.css" rel="stylesheet"/>
<link href="css/aos.css" rel="stylesheet"/>
<link href="css/ionicons.min.css" rel="stylesheet"/>
<link href="css/bootstrap-datepicker.css" rel="stylesheet"/>
<link href="css/jquery.timepicker.css" rel="stylesheet"/>
<link href="css/flaticon.css" rel="stylesheet"/>
<link href="css/icomoon.css" rel="stylesheet"/>
<link href="css/style.css" rel="stylesheet"/>
<link href="css/curtain.css" rel="stylesheet"/>
<link href="images/favicon.jpg" rel="icon" type="image/jpg"/>
</head>
<body>
<div id="preloader">
<div class="preloader-content">
<img alt="Loading Image 1" class="preloader-image" src="images/rccrest.png"/>
<img alt="Loading Image 2" class="preloader-image" src="images/Red-Cross.png"/>
</div>
</div>
<nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
  <div class="left-container">
    <a class="navbar-brand navbar-black" href="./">
      <img src="../../../images/Red-Cross-Society.png" style="max-width:100%; height: 50px;">
    </a>
  </div>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="oi oi-menu"></span> Menu
  </button>

  <div class="collapse navbar-collapse" id="ftco-nav">
    <ul class="navbar-nav ml-auto">
      <li class="nav-item active"><a href="/" class="nav-link">Home</a></li>
      <li class="nav-item dropdown">
        <a href="./about" class="nav-link dropdown-toggle" id="aboutDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          About
        </a>
        <div class="dropdown-menu" aria-labelledby="aboutDropdown">
          <a class="dropdown-item" href="/about">About</a>
          <a class="dropdown-item" href="/archives/principals-report/Principal's-Report">Principal's Report</a>
          <a class="dropdown-item" href="/archives/grades/grades">Grading &amp; Awards</a>
        </div>
      </li>
      <li class="nav-item"><a href="/archives/topboards/topboard" class="nav-link">Topboard</a></li>
      <li class="nav-item"><a href="/archives/projects/projects" class="nav-link">Projects</a></li>
      <li class="nav-item"><a href="/publications" class="nav-link">Publications</a></li>
      <li class="nav-item"><a href="/contact" class="nav-link">Contact</a></li>
    </ul>
  </div>
</nav>

<section class="hero-wrap hero-wrap-2 d-flex" style="background-image: url(images/rcrcs\ 1.jpg);">
<div class="overlay"></div>
<div class="container">
<div class="row d-flex align-items-center">
<div class="col-md-7 col-sm-12 ftco-animate text-bread d-flex align-items-center">
<div class="text">
<h1 class="mb-3 mt-5 bread" style="font-family: 'Garamond', serif;">Royal College Red Cross Society</h1>
<p class="breadcrumbs"><span class="mr-2">Founded in 1978</span></p>
</div>
</div>
</div>
</div>
</section>
<section class="ftco-section ftco-project" id="projects-section">
<div class="container-fluid px-4">
<div class="row justify-content-center mb-5 pb-3">
<div class="col-lg-6 heading-section text-center ftco-animate">
<span class="subheading">Projects</span>
<h2 class="mb-4">This Year</h2>
</div>
</div>
<p style="text-align: justify;">The Royal College Red Cross Society is dedicated to promoting health, safety, and community welfare through various impactful initiatives. Since its inception, the society has been integral to the school's identity, championing causes such as first aid preparedness and community health. Key projects like the Royal College First Aid Unit ensure members are rigorously trained in first aid, fostering a safer school environment. The 45th Annual Blood Donation Campaign continues a longstanding tradition, addressing the national blood shortage with drives across multiple regions. Meanwhile, Vision ’24 aims to increase eye donations and expand eye care accessibility, particularly in underserved areas, in collaboration with local hospitals and schools.</p>
<p style="text-align: justify;">Other notable projects include Health First’24, which focuses on documenting first aid interventions and creating educational resources on health and safety, and Infusion ’24, a series of community service projects ranging from food distribution to supporting injured war veterans. Additionally, the First Aid Leadership Training Program prepares participants for emergency response through comprehensive workshops, and Project Orange Elephant seeks to mitigate human-elephant conflicts by planting citrus trees as natural barriers. Finally, Alleviate ’24, a fundraiser carnival, aims to support these community initiatives while showcasing young talents and promoting mental well-being through entertainment. Each of these efforts reflects the RCRCS's commitment to fostering a culture of safety, health, and community service.</p>
<div class="center-button">
<a href="./archives/projects/projects'24">Learn More</a>
</div>
</div>
</section>
<section class="ftco-section ftco-counter" data-stellar-background-ratio="0.5" id="section-counter" style="background-image: url(images/bg_3.jpg);">
<div class="container">
<div class="row d-md-flex align-items-center">
<div class="col-lg-4">
<div class="heading-section pl-md-5 heading-section-white">
<div class="ftco-animate">
<span class="subheading">In</span>
<h2 class="mb-4">2024</h2>
</div>
</div>
</div>
<div class="col-lg-8">
<div class="row d-md-flex align-items-center">
<div class="col-md d-flex justify-content-center counter-wrap ftco-animate">
<div class="block-18 text-center">
<div class="text">
<span>Actively</span>
<strong class="number" data-number="233">0</strong>
<span>Members</span>
</div>
</div>
</div>
<div class="col-md d-flex justify-content-center counter-wrap ftco-animate">
<div class="block-18 text-center">
<div class="text">
<span>First Aid Provided at</span>
<strong class="number" data-number="15">0</strong>
<span>Events</span>
</div>
</div>
</div>
<div class="col-md d-flex justify-content-center counter-wrap ftco-animate">
<div class="block-18 text-center">
<div class="text">
<span>Done</span>
<strong class="number" data-number="8">0</strong>
<span>Projects</span>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</section>
<style>
    @media (max-width: 767px) {
        #section-counter .heading-section {
            text-align: center;
            padding: 0;
        }
        #section-counter h2 {
            font-size: 2rem;
        }
        #section-counter .subheading {
            font-size: 1.2rem; 
        }
    }
</style>
<html><body><footer class="ftco-footer ftco-bg-dark ftco-section">
<div class="container text-center">
<div class="col-md">
<div class="ftco-footer-widget mb-4">
<ul class="ftco-footer-social list-unstyled mt-3">
<li class="ftco-animate d-inline-block"><a href="https://www.facebook.com/rcrcs.org?_rdc=1&amp;_rdr" target="_blank"><span class="icon-facebook"></span></a></li>
<li class="ftco-animate d-inline-block"><a href="https://www.twitter.com/RCRed_Cross/" target="_blank"><span class="icon-twitter"></span></a></li>
<li class="ftco-animate d-inline-block"><a href="https://www.instagram.com/rcredcross/" target="_blank"><span class="icon-instagram"></span></a></li>
<li class="ftco-animate d-inline-block"><a href="https://www.youtube.com/@RoyalCollegeRedCrossSociety" target="_blank"><span class="icon-youtube"></span></a></li>
<li class="ftco-animate d-inline-block"><a href="https://www.linkedin.com/company/rcredcross/" target="_blank"><span class="icon-linkedin"></span></a></li>
</ul>
<h2 style="font-family: 'Garamond', serif;">Royal College Red Cross Society</h2>
<p>© 2025 Royal College Red Cross Society</p>
<a href="https://royalcollegeredcross.com/acknowledgements" style="font-family: 'Garamond', serif;">BUILT BY BOYS IN THE SCHOOL</a>
</div>
</div>
</div>
</footer>
</body></html>
<script src="js/jquery.min.js"></script>
<script src="js/jquery-migrate-3.0.1.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.easing.1.3.js"></script>
<script src="js/jquery.waypoints.min.js"></script>
<script src="js/jquery.stellar.min.js"></script>
<script src="js/owl.carousel.min.js"></script>
<script src="js/jquery.magnific-popup.min.js"></script>
<script src="js/aos.js"></script>
<script src="js/jquery.animateNumber.min.js"></script>
<script src="js/jquery.mb.YTPlayer.min.js"></script>
<script src="js/scrollax.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVWaKrjvy3MaE7SQ74_uJiULgl1JY0H2s&amp;sensor=false"></script>
<script src="js/google-map.js"></script>
<script src="js/main.js"></script>
<script src="js/curtain.js"></script>
</body>
</html>