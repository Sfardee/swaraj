<?php // demo_FormToken.php
/**
 * A client side script that creates an AJAX request for a form token
 * This script injects the form token into the request variables
 */
require_once('security/class_FormToken.php');
include_once('storage/db.php');
session_start();
$success = FALSE;
$csrf_attack = FALSE;

// IF THERE IS A POST-REQUEST
if (!empty($_POST))
{
    $status = FormToken::check();
    if ($status)  {
        // The request is genuine and should be stored in DB.
        // extract user form input

        $name = mysqli_real_escape_string($db, $_POST['name']);
        $phone_number = mysqli_real_escape_string($db, $_POST['phone_number']);
        $interested_model = '';
        if (isset($_POST['interested_model']) && count($_POST['interested_model'])) {
            $interested_model = mysqli_real_escape_string($db, implode(', ', $_POST['interested_model']));
        }
        $state = mysqli_real_escape_string($db, $_POST['state']);
        $city = mysqli_real_escape_string($db, $_POST['city']);
        $district = mysqli_real_escape_string($db, $_POST['district']);
        $tehsil = mysqli_real_escape_string($db, $_POST['tehsil']);
        $village = mysqli_real_escape_string($db, $_POST['village']);
        $pincode = mysqli_real_escape_string($db, $_POST['pincode']);

        // extract utm information (if any)
        $url = (isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] == 'on') ? "https://" : "http://";
        $url .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
        $query_str = parse_url($url, PHP_URL_QUERY);
        parse_str($query_str, $query_params);
        $utm_source = isset($query_params['utm_source'])? $query_params['utm_source']: '';
        $utm_medium  = isset($query_params['utm_medium'])? $query_params['utm_medium']: '';
        $utm_campaign  = isset($query_params['utm_campaign'])? $query_params['utm_campaign']: '';

        // insert the data 
        $query = "INSERT INTO survey_response (name, phone_number, interested_model, state, city, district, tehsil, village, pincode, referrer, utm_source, utm_medium, utm_campaign) VALUES
        ('$name', $phone_number, '$interested_model', '$state', '$city', '$district', '$tehsil', '$village', $pincode, '$url', '$utm_source', '$utm_medium', '$utm_campaign');
        ";
        mysqli_query($db, $query);
        $cookie_name = "swarajtractors_enquiryform";
        $data = array('name=' . $name, 'phone number=' . $phone_number, 'pincode=' .  $pincode);
        $cookie_value = implode('&', $data);
        setcookie($cookie_name, $cookie_value, time() + (86400 * 365), "/");
        $success = TRUE;
    } else {
        // ignore request. It is CSRF attack
        $csrf_attack = TRUE;
    }
}
?>
<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Swaraj</title>
    <link rel="shortcut icon" href="favicon.ico">
    <link rel="stylesheet" type="text/css" href="css/jquery.dropdown.css">
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
    <!--[if lt IE 9]>
        <script type="text/javascript" src="js/html5shiv.js"></script>
    <![endif]-->
    <!-- Global site tag (gtag.js) - Google Ads: 753611611 --> 
    <script async src="https://www.googletagmanager.com/gtag/js?id=AW-753611611"></script> 
    <script> window.dataLayer = window.dataLayer || []; function gtag(){dataLayer.push(arguments);} gtag('js', new Date()); gtag('config', 'AW-753611611'); </script>
    <?php if ($success) { ?>
        <!-- Event snippet for MCMS form submissiion conversion page --> 
        <script> gtag('event', 'conversion', {'send_to': 'AW-753611611/l7vNCOmQhpkBENvmrOcC'}); </script>
    <?php } ?>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-70320457-3"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    
    gtag('config', 'UA-70320457-3');
    </script>
</head>

<body>
    <header>
        <div class="hero-banner owl-carousel">
            <div class="item">
                <figure class="hero-desk"><img src="images/landing-page-banner1.jpg" class="resize-img"></figure>
                <figure class="hero-mobile"><img src="images/landing-page-mobile-banner1.jpg" class="resize-img"></figure>
            </div>
            <div class="item">
                <figure class="hero-desk"><img src="images/landing-page-banner2.jpg" class="resize-img"></figure>
                <figure class="hero-mobile"><img src="images/landing-page-mobile-banner2.jpg" class="resize-img"></figure>
            </div>
        </div> 
    </header>
    <?php if ($success) { ?>
        <iframe src="https://adgebra.co.in/Tracker/Conversion?p1=2985&p2=[order_Id]&p3=[product_Id]&p4=[cartvalue]&p5=[flag~custom_values]"width="0" height="0" frameborder="0"></iframe>
    <?php } ?>
    <div class="container">
        <div class="CTR">
            <h1>तो देर किस बात की?</h1>
            <p class="titile-info">तुरंत अपनी जानकारी भरें और हम जल्द ही आप से संपर्क करेंगे।</p>
            <p class="validation-info">नाम, फ़ोन नंबर और पिन कोड भरना अनिवार्य है।</p>
            <form id="formsubmit" method="post">
                <div class="form-box">
                    <ul>
                        <li>
                            <input type="text"  name="name" minlength="2" maxlength="50" placeholder="Name / नाम *" pattern="[A-za-z][A-Za-z '.]+" class="text-box" required oninvalid="setCustomValidity('Will Accept Alphabets Only')" oninput="setCustomValidity('')">
                        </li>
                        <li>
                            <input type="text" name="phone_number" minlength="6" maxlength="13" pattern="^(?!0+$)\d{6,}$" placeholder="Phone Number / फ़ोन नंबर *" class="text-box" required oninvalid="setCustomValidity('Will accept numbers only, minimum 6 digits. Cannot be all Zeros')" oninput="setCustomValidity('')">
                        </li>
                        <li>
                            <div class="dropdown-mul-2">
                                <a class="close-dropdown" href="javascript:void(0)">×</a>
                                <select style="display:none" name="interested_model[]" id="mul-2" multiple placeholder="Model Interested / मॉडल इच्छुक हैं">
                                    <option value="717">717</option>
                                    <option value="724XM">724XM</option>
                                    <option value="724XM ORCH">724XM ORCH</option>
                                    <option value="724XM ORCH NT">724XM ORCH NT</option>
                                    <option value="825XM">825XM</option>
                                    <option value="834XM">834XM</option>
                                    <option value="735FE">735FE</option>
                                    <option value="735XM">735XM</option>
                                    <option value="735XT">735XT</option>
                                    <option value="843XM">843XM</option>
                                    <option value="843XM - OSM">843XM - OSM</option>
                                    <option value="744FE">744FE</option>
                                    <option value="744FE Potato Xpert">744FE Potato Xpert</option>
                                    <option value="744XM">744XM</option>
                                    <option value="841XM">841XM</option>
                                    <option value="855FE">855FE</option>
                                    <option value="855XM">855XM</option>
                                    <option value="960FE">960FE</option>                
                                </select>
                            </div>
                        </li>
                        <li>
                            <div class="select-dropdown">
                                <select id="state" name="state" placeholder="State / राज्य *" required oninvalid="setCustomValidity('Please select the state')" oninput="setCustomValidity('')"> 
                                    <option value="">State / राज्य *</option>
                                    <option value="Assam">Assam</option>
                                    <option value="Goa">Goa</option>
                                    <option value="Madhya Pradesh">Madhya Pradesh</option>
                                    <option value="Manipur">Manipur</option>
                                    <option value="Meghalaya">Meghalaya</option>
                                    <option value="Mizoram">Mizoram</option>
                                    <option value="National Capital Territory of Delhi">National Capital Territory of Delhi</option>
                                    <option value="Sikkim">Sikkim</option>
                                    <option value="Andhra Pradesh">Andhra Pradesh</option>
                                    <option value="Arunachal Pradesh">Arunachal Pradesh</option>
                                    <option value="Bihar">Bihar</option>
                                    <option value="Chhattisgarh">Chhattisgarh</option>
                                    <option value="Gujarat">Gujarat</option>
                                    <option value="Haryana">Haryana</option>
                                    <option value="Himachal Pradesh">Himachal Pradesh</option>
                                    <option value="Jammu and Kashmir">Jammu and Kashmir</option>
                                    <option value="Jharkhand">Jharkhand</option>
                                    <option value="Karnataka">Karnataka</option>
                                    <option value="Kerala">Kerala</option>
                                    <option value="Maharashtra">Maharashtra</option>
                                    <option value="Nagaland">Nagaland</option>
                                    <option value="Odisha">Odisha</option>
                                    <option value="Punjab">Punjab</option>
                                    <option value="Rajasthan">Rajasthan</option>
                                    <option value="Tamil Nadu">Tamil Nadu</option>
                                    <option value="Uttarakhand">Uttarakhand</option>
                                    <option value="Telangana">Telangana</option>
                                    <option value="Tripura">Tripura</option>
                                    <option value="Andaman and Nicobar">Andaman and Nicobar</option>
                                    <option value="Chandigarh">Chandigarh</option>
                                    <option value="Dadra and Nagar Haveli">Dadra and Nagar Haveli</option>
                                    <option value="Daman and Diu">Daman and Diu</option>
                                    <option value="Lakshadweep">Lakshadweep</option>
                                    <option value="Puducherry">Puducherry</option>
                                    <option value="Uttar Pradesh">Uttar Pradesh</option>
                                    <option value="West Bengal">West Bengal</option>
                                </select>
                            </div>
                        </li>
                        <li>
                            <div class="select-dropdown">
                                <select id="city" name="city" placeholder="City / शहर">
                                    <option rel="empty" value="" selected>City / शहर</option>
                                    <option rel="Assam" value="" selected>City / शहर</option>
                                    <option rel="Assam" value="Baksa">Baksa</option>
                                    <option rel="Assam" value="Barpeta">Barpeta</option>
                                    <option rel="Assam" value="Bongaigaon">Bongaigaon</option>
                                    <option rel="Assam" value="Cachar">Cachar</option>
                                    <option rel="Assam" value="Chirang">Chirang</option>
                                    <option rel="Assam" value="Darrang">Darrang</option>
                                    <option rel="Assam" value="Dhemaji">Dhemaji</option>
                                    <option rel="Assam" value="Dhubri">Dhubri</option>
                                    <option rel="Assam" value="Dibrugarh">Dibrugarh</option>
                                    <option rel="Assam" value="Dima Hasao District">Dima Hasao District</option>
                                    <option rel="Assam" value="Goalpara">Goalpara</option>
                                    <option rel="Assam" value="Golaghat">Golaghat</option>
                                    <option rel="Assam" value="Hailakandi">Hailakandi</option>
                                    <option rel="Assam" value="Jorhat">Jorhat</option>
                                    <option rel="Assam" value="Kamrup">Kamrup</option>
                                    <option rel="Assam" value="Kamrup Metropolitan">Kamrup Metropolitan</option>
                                    <option rel="Assam" value="Karbi Anglong">Karbi Anglong</option>
                                    <option rel="Assam" value="Karimganj">Karimganj</option>
                                    <option rel="Assam" value="Kokrajhar">Kokrajhar</option>
                                    <option rel="Assam" value="Lakhimpur">Lakhimpur</option>
                                    <option rel="Assam" value="Morigaon">Morigaon</option>
                                    <option rel="Assam" value="Nagaon">Nagaon</option>
                                    <option rel="Assam" value="Nalbari">Nalbari</option>
                                    <option rel="Assam" value="Sibsagar">Sibsagar</option>
                                    <option rel="Assam" value="Sonitpur">Sonitpur</option>
                                    <option rel="Assam" value="Tinsukia">Tinsukia</option>
                                    <option rel="Assam" value="Udalguri">Udalguri</option>
                                    <option rel="Assam" value="Other">Other</option>
                                    <option rel="Goa" value="" selected>City / शहर</option>
                                    <option rel="Goa" value="North Goa">North Goa</option>
                                    <option rel="Goa" value="South Goa">South Goa</option>
                                    <option rel="Goa" value="Other">Other</option>
                                    <option rel="Madhya Pradesh" value="" selected>City / शहर</option>
                                    <option rel="Madhya Pradesh" value="Alirajpur">Alirajpur</option>
                                    <option rel="Madhya Pradesh" value="Anuppur">Anuppur</option>
                                    <option rel="Madhya Pradesh" value="Ashoknagar">Ashoknagar</option>
                                    <option rel="Madhya Pradesh" value="Balaghat">Balaghat</option>
                                    <option rel="Madhya Pradesh" value="Barwani">Barwani</option>
                                    <option rel="Madhya Pradesh" value="Betul">Betul</option>
                                    <option rel="Madhya Pradesh" value="Bhind">Bhind</option>
                                    <option rel="Madhya Pradesh" value="Bhopal">Bhopal</option>
                                    <option rel="Madhya Pradesh" value="Burhanpur">Burhanpur</option>
                                    <option rel="Madhya Pradesh" value="Chhatarpur">Chhatarpur</option>
                                    <option rel="Madhya Pradesh" value="Chhindwara">Chhindwara</option>
                                    <option rel="Madhya Pradesh" value="Damoh">Damoh</option>
                                    <option rel="Madhya Pradesh" value="Datia">Datia</option>
                                    <option rel="Madhya Pradesh" value="Dewas">Dewas</option>
                                    <option rel="Madhya Pradesh" value="Dhar">Dhar</option>
                                    <option rel="Madhya Pradesh" value="Dindori">Dindori</option>
                                    <option rel="Madhya Pradesh" value="East Nimar">East Nimar</option>
                                    <option rel="Madhya Pradesh" value="Guna">Guna</option>
                                    <option rel="Madhya Pradesh" value="Gwalior">Gwalior</option>
                                    <option rel="Madhya Pradesh" value="Harda">Harda</option>
                                    <option rel="Madhya Pradesh" value="Hoshangabad">Hoshangabad</option>
                                    <option rel="Madhya Pradesh" value="Indore">Indore</option>
                                    <option rel="Madhya Pradesh" value="Jabalpur">Jabalpur</option>
                                    <option rel="Madhya Pradesh" value="Jhabua">Jhabua</option>
                                    <option rel="Madhya Pradesh" value="Katni">Katni</option>
                                    <option rel="Madhya Pradesh" value="Khargone">Khargone</option>
                                    <option rel="Madhya Pradesh" value="Mandla">Mandla</option>
                                    <option rel="Madhya Pradesh" value="Mandsaur">Mandsaur</option>
                                    <option rel="Madhya Pradesh" value="Morena">Morena</option>
                                    <option rel="Madhya Pradesh" value="Narsimhapur">Narsimhapur</option>
                                    <option rel="Madhya Pradesh" value="Neemuch">Neemuch</option>
                                    <option rel="Madhya Pradesh" value="Panna">Panna</option>
                                    <option rel="Madhya Pradesh" value="Raisen">Raisen</option>
                                    <option rel="Madhya Pradesh" value="Rajgarh">Rajgarh</option>
                                    <option rel="Madhya Pradesh" value="Ratlam">Ratlam</option>
                                    <option rel="Madhya Pradesh" value="Rewa">Rewa</option>
                                    <option rel="Madhya Pradesh" value="Sagar">Sagar</option>
                                    <option rel="Madhya Pradesh" value="Satna">Satna</option>
                                    <option rel="Madhya Pradesh" value="Sehore">Sehore</option>
                                    <option rel="Madhya Pradesh" value="Seoni">Seoni</option>
                                    <option rel="Madhya Pradesh" value="Shahdol">Shahdol</option>
                                    <option rel="Madhya Pradesh" value="Shajapur">Shajapur</option>
                                    <option rel="Madhya Pradesh" value="Sheopur">Sheopur</option>
                                    <option rel="Madhya Pradesh" value="Shivpuri">Shivpuri</option>
                                    <option rel="Madhya Pradesh" value="Sidhi">Sidhi</option>
                                    <option rel="Madhya Pradesh" value="Singrauli">Singrauli</option>
                                    <option rel="Madhya Pradesh" value="Tikamgarh">Tikamgarh</option>
                                    <option rel="Madhya Pradesh" value="Ujjain">Ujjain</option>
                                    <option rel="Madhya Pradesh" value="Umaria District">Umaria District</option>
                                    <option rel="Madhya Pradesh" value="Vidisha">Vidisha</option>
                                    <option rel="Madhya Pradesh" value="Other">Other</option>
                                    <option rel="Manipur" value="" selected>City / शहर</option>
                                    <option rel="Manipur" value="Bishnupur">Bishnupur</option>
                                    <option rel="Manipur" value="Chandel">Chandel</option>
                                    <option rel="Manipur" value="Churachandpur">Churachandpur</option>
                                    <option rel="Manipur" value="Imphal East">Imphal East</option>
                                    <option rel="Manipur" value="Imphal West">Imphal West</option>
                                    <option rel="Manipur" value="Senapati">Senapati</option>
                                    <option rel="Manipur" value="Tamenglong">Tamenglong</option>
                                    <option rel="Manipur" value="Thoubal">Thoubal</option>
                                    <option rel="Manipur" value="Ukhrul">Ukhrul</option>
                                    <option rel="Manipur" value="Other">Other</option>
                                    <option rel="Meghalaya" value="" selected>City / शहर</option>
                                    <option rel="Meghalaya" value="East Garo Hills">East Garo Hills</option>
                                    <option rel="Meghalaya" value="East Khasi Hills">East Khasi Hills</option>
                                    <option rel="Meghalaya" value="Jaintia Hills">Jaintia Hills</option>
                                    <option rel="Meghalaya" value="Ri-Bhoi">Ri-Bhoi</option>
                                    <option rel="Meghalaya" value="South Garo Hills">South Garo Hills</option>
                                    <option rel="Meghalaya" value="West Garo Hills">West Garo Hills</option>
                                    <option rel="Meghalaya" value="West Khasi Hills">West Khasi Hills</option>
                                    <option rel="Meghalaya" value="Other">Other</option>
                                    <option rel="Mizoram" value="" selected>City / शहर</option>
                                    <option rel="Mizoram" value="Aizawl">Aizawl</option>
                                    <option rel="Mizoram" value="Champhai">Champhai</option>
                                    <option rel="Mizoram" value="Kolasib district">Kolasib district</option>
                                    <option rel="Mizoram" value="Lawngtlai">Lawngtlai</option>
                                    <option rel="Mizoram" value="Lunglei">Lunglei</option>
                                    <option rel="Mizoram" value="Mamit">Mamit</option>
                                    <option rel="Mizoram" value="Saiha">Saiha</option>
                                    <option rel="Mizoram" value="Serchhip">Serchhip</option>
                                    <option rel="Mizoram" value="Other">Other</option>
                                    <option rel="National Capital Territory of Delhi" value="" selected>City / शहर</option>
                                    <option rel="National Capital Territory of Delhi" value="Central Delhi">Central Delhi</option>
                                    <option rel="National Capital Territory of Delhi" value="East Delhi">East Delhi</option>
                                    <option rel="National Capital Territory of Delhi" value="New Delhi">New Delhi</option>
                                    <option rel="National Capital Territory of Delhi" value="North Delhi">North Delhi</option>
                                    <option rel="National Capital Territory of Delhi" value="North East Delhi">North East Delhi</option>
                                    <option rel="National Capital Territory of Delhi" value="North West Delhi">North West Delhi</option>
                                    <option rel="National Capital Territory of Delhi" value="South Delhi">South Delhi</option>
                                    <option rel="National Capital Territory of Delhi" value="South West Delhi">South West Delhi</option>
                                    <option rel="National Capital Territory of Delhi" value="West Delhi">West Delhi</option>
                                    <option rel="National Capital Territory of Delhi" value="Other">Other</option>
                                    <option rel="Sikkim" value="" selected>City / शहर</option>
                                    <option rel="Sikkim" value="East District">East District</option>
                                    <option rel="Sikkim" value="North District">North District</option>
                                    <option rel="Sikkim" value="South District">South District</option>
                                    <option rel="Sikkim" value="West District">West District</option>
                                    <option rel="Sikkim" value="Other">Other</option>
                                    <option rel="Andhra Pradesh" value="" selected>City / शहर</option>
                                    <option rel="Andhra Pradesh" value="Anantapur">Anantapur</option>
                                    <option rel="Andhra Pradesh" value="Chittoor">Chittoor</option>
                                    <option rel="Andhra Pradesh" value="Cuddapah">Cuddapah</option>
                                    <option rel="Andhra Pradesh" value="East Godavari">East Godavari</option>
                                    <option rel="Andhra Pradesh" value="Guntur">Guntur</option>
                                    <option rel="Andhra Pradesh" value="Krishna">Krishna</option>
                                    <option rel="Andhra Pradesh" value="Kurnool">Kurnool</option>
                                    <option rel="Andhra Pradesh" value="Nellore">Nellore</option>
                                    <option rel="Andhra Pradesh" value="Prakasam">Prakasam</option>
                                    <option rel="Andhra Pradesh" value="Srikakulam">Srikakulam</option>
                                    <option rel="Andhra Pradesh" value="Vishakhapatnam">Vishakhapatnam</option>
                                    <option rel="Andhra Pradesh" value="Vizianagaram District">Vizianagaram District</option>
                                    <option rel="Andhra Pradesh" value="West Godavari">West Godavari</option>
                                    <option rel="Andhra Pradesh" value="Other">Other</option>
                                    <option rel="Arunachal Pradesh" value="" selected>City / शहर</option>
                                    <option rel="Arunachal Pradesh" value="Anjaw">Anjaw</option>
                                    <option rel="Arunachal Pradesh" value="Changlang">Changlang</option>
                                    <option rel="Arunachal Pradesh" value="Dibang Valley">Dibang Valley</option>
                                    <option rel="Arunachal Pradesh" value="East Kameng">East Kameng</option>
                                    <option rel="Arunachal Pradesh" value="East Siang">East Siang</option>
                                    <option rel="Arunachal Pradesh" value="Kurung Kumey">Kurung Kumey</option>
                                    <option rel="Arunachal Pradesh" value="Lohit District">Lohit District</option>
                                    <option rel="Arunachal Pradesh" value="Lower Dibang Valley">Lower Dibang Valley</option>
                                    <option rel="Arunachal Pradesh" value="Lower Subansiri">Lower Subansiri</option>
                                    <option rel="Arunachal Pradesh" value="Papum Pare">Papum Pare</option>
                                    <option rel="Arunachal Pradesh" value="Tawang">Tawang</option>
                                    <option rel="Arunachal Pradesh" value="Tirap">Tirap</option>
                                    <option rel="Arunachal Pradesh" value="Upper Siang">Upper Siang</option>
                                    <option rel="Arunachal Pradesh" value="Upper Subansiri">Upper Subansiri</option>
                                    <option rel="Arunachal Pradesh" value="West Kameng">West Kameng</option>
                                    <option rel="Arunachal Pradesh" value="West Siang">West Siang</option>
                                    <option rel="Arunachal Pradesh" value="Other">Other</option>
                                    <option rel="Bihar" value="" selected>City / शहर</option>
                                    <option rel="Bihar" value="Araria">Araria</option>
                                    <option rel="Bihar" value="Arwal">Arwal</option>
                                    <option rel="Bihar" value="Aurangabad">Aurangabad</option>
                                    <option rel="Bihar" value="Banka">Banka</option>
                                    <option rel="Bihar" value="Begusarai">Begusarai</option>
                                    <option rel="Bihar" value="Bhagalpur">Bhagalpur</option>
                                    <option rel="Bihar" value="Bhojpur">Bhojpur</option>
                                    <option rel="Bihar" value="Buxar">Buxar</option>
                                    <option rel="Bihar" value="Darbhanga">Darbhanga</option>
                                    <option rel="Bihar" value="Gaya">Gaya</option>
                                    <option rel="Bihar" value="Gopalganj">Gopalganj</option>
                                    <option rel="Bihar" value="Jamui">Jamui</option>
                                    <option rel="Bihar" value="Jehanabad">Jehanabad</option>
                                    <option rel="Bihar" value="Kaimur District">Kaimur District</option>
                                    <option rel="Bihar" value="Katihar">Katihar</option>
                                    <option rel="Bihar" value="Khagaria">Khagaria</option>
                                    <option rel="Bihar" value="Kishanganj">Kishanganj</option>
                                    <option rel="Bihar" value="Lakhisarai">Lakhisarai</option>
                                    <option rel="Bihar" value="Madhepura">Madhepura</option>
                                    <option rel="Bihar" value="Madhubani">Madhubani</option>
                                    <option rel="Bihar" value="Munger">Munger</option>
                                    <option rel="Bihar" value="Muzaffarpur">Muzaffarpur</option>
                                    <option rel="Bihar" value="Nalanda">Nalanda</option>
                                    <option rel="Bihar" value="Nawada">Nawada</option>
                                    <option rel="Bihar" value="Pashchim Champaran">Pashchim Champaran</option>
                                    <option rel="Bihar" value="Patna">Patna</option>
                                    <option rel="Bihar" value="Purba Champaran">Purba Champaran</option>
                                    <option rel="Bihar" value="Purnia">Purnia</option>
                                    <option rel="Bihar" value="Rohtas">Rohtas</option>
                                    <option rel="Bihar" value="Saharsa">Saharsa</option>
                                    <option rel="Bihar" value="Samastipur">Samastipur</option>
                                    <option rel="Bihar" value="Saran">Saran</option>
                                    <option rel="Bihar" value="Sheikhpura">Sheikhpura</option>
                                    <option rel="Bihar" value="Sheohar">Sheohar</option>
                                    <option rel="Bihar" value="Sitamarhi">Sitamarhi</option>
                                    <option rel="Bihar" value="Siwan">Siwan</option>
                                    <option rel="Bihar" value="Supaul">Supaul</option>
                                    <option rel="Bihar" value="Vaishali">Vaishali</option>
                                    <option rel="Bihar" value="Other">Other</option>
                                    <option rel="Chhattisgarh" value="" selected>City / शहर</option>
                                    <option rel="Chhattisgarh" value="Bastar">Bastar</option>
                                    <option rel="Chhattisgarh" value="Bijapur">Bijapur</option>
                                    <option rel="Chhattisgarh" value="Bilaspur">Bilaspur</option>
                                    <option rel="Chhattisgarh" value="Dakshin Bastar Dantewada">Dakshin Bastar Dantewada</option>
                                    <option rel="Chhattisgarh" value="Dhamtari">Dhamtari</option>
                                    <option rel="Chhattisgarh" value="Durg">Durg</option>
                                    <option rel="Chhattisgarh" value="Janjgir-Champa">Janjgir-Champa</option>
                                    <option rel="Chhattisgarh" value="Jashpur">Jashpur</option>
                                    <option rel="Chhattisgarh" value="Kabeerdham">Kabeerdham</option>
                                    <option rel="Chhattisgarh" value="Korba">Korba</option>
                                    <option rel="Chhattisgarh" value="Koriya">Koriya</option>
                                    <option rel="Chhattisgarh" value="Mahasamund">Mahasamund</option>
                                    <option rel="Chhattisgarh" value="Narayanpur">Narayanpur</option>
                                    <option rel="Chhattisgarh" value="Raigarh">Raigarh</option>
                                    <option rel="Chhattisgarh" value="Raipur">Raipur</option>
                                    <option rel="Chhattisgarh" value="Raj Nandgaon">Raj Nandgaon</option>
                                    <option rel="Chhattisgarh" value="Surguja">Surguja</option>
                                    <option rel="Chhattisgarh" value="Uttar Bastar Kanker">Uttar Bastar Kanker</option>
                                    <option rel="Chhattisgarh" value="Other">Other</option>
                                    <option rel="Gujarat" value="" selected>City / शहर</option>
                                    <option rel="Gujarat" value="Ahmadabad">Ahmadabad</option>
                                    <option rel="Gujarat" value="Amreli">Amreli</option>
                                    <option rel="Gujarat" value="Anand">Anand</option>
                                    <option rel="Gujarat" value="Banas Kantha">Banas Kantha</option>
                                    <option rel="Gujarat" value="Bharuch">Bharuch</option>
                                    <option rel="Gujarat" value="Bhavnagar">Bhavnagar</option>
                                    <option rel="Gujarat" value="Dohad">Dohad</option>
                                    <option rel="Gujarat" value="Gandhinagar">Gandhinagar</option>
                                    <option rel="Gujarat" value="Jamnagar">Jamnagar</option>
                                    <option rel="Gujarat" value="Junagadh">Junagadh</option>
                                    <option rel="Gujarat" value="Kachchh">Kachchh</option>
                                    <option rel="Gujarat" value="Kheda">Kheda</option>
                                    <option rel="Gujarat" value="Mahesana">Mahesana</option>
                                    <option rel="Gujarat" value="Narmada District">Narmada District</option>
                                    <option rel="Gujarat" value="Navsari">Navsari</option>
                                    <option rel="Gujarat" value="Panch Mahals">Panch Mahals</option>
                                    <option rel="Gujarat" value="Patan">Patan</option>
                                    <option rel="Gujarat" value="Porbandar">Porbandar</option>
                                    <option rel="Gujarat" value="Rajkot">Rajkot</option>
                                    <option rel="Gujarat" value="Sabar Kantha">Sabar Kantha</option>
                                    <option rel="Gujarat" value="Surat">Surat</option>
                                    <option rel="Gujarat" value="Surendranagar">Surendranagar</option>
                                    <option rel="Gujarat" value="Tapi">Tapi</option>
                                    <option rel="Gujarat" value="The Dangs">The Dangs</option>
                                    <option rel="Gujarat" value="Vadodara">Vadodara</option>
                                    <option rel="Gujarat" value="Valsad">Valsad</option>
                                    <option rel="Gujarat" value="Other">Other</option>
                                    <option rel="Haryana" value="" selected>City / शहर</option>
                                    <option rel="Haryana" value="Ambala">Ambala</option>
                                    <option rel="Haryana" value="Bhiwani">Bhiwani</option>
                                    <option rel="Haryana" value="Faridabad District">Faridabad District</option>
                                    <option rel="Haryana" value="Fatehabad District">Fatehabad District</option>
                                    <option rel="Haryana" value="Gurgaon">Gurgaon</option>
                                    <option rel="Haryana" value="Hisar">Hisar</option>
                                    <option rel="Haryana" value="Jhajjar">Jhajjar</option>
                                    <option rel="Haryana" value="Jind">Jind</option>
                                    <option rel="Haryana" value="Kaithal">Kaithal</option>
                                    <option rel="Haryana" value="Karnal">Karnal</option>
                                    <option rel="Haryana" value="Kurukshetra">Kurukshetra</option>
                                    <option rel="Haryana" value="Mahendragarh">Mahendragarh</option>
                                    <option rel="Haryana" value="Mewat">Mewat</option>
                                    <option rel="Haryana" value="Palwal">Palwal</option>
                                    <option rel="Haryana" value="Panchkula">Panchkula</option>
                                    <option rel="Haryana" value="Panipat">Panipat</option>
                                    <option rel="Haryana" value="Rewari District">Rewari District</option>
                                    <option rel="Haryana" value="Rohtak">Rohtak</option>
                                    <option rel="Haryana" value="Sirsa">Sirsa</option>
                                    <option rel="Haryana" value="Sonipat">Sonipat</option>
                                    <option rel="Haryana" value="Yamunanagar">Yamunanagar</option>
                                    <option rel="Haryana" value="Other">Other</option>
                                    <option rel="Himachal Pradesh" value="" selected>City / शहर</option>
                                    <option rel="Himachal Pradesh" value="Bilaspur">Bilaspur</option>
                                    <option rel="Himachal Pradesh" value="Chamba">Chamba</option>
                                    <option rel="Himachal Pradesh" value="Hamirpur">Hamirpur</option>
                                    <option rel="Himachal Pradesh" value="Kangra">Kangra</option>
                                    <option rel="Himachal Pradesh" value="Kinnaur">Kinnaur</option>
                                    <option rel="Himachal Pradesh" value="Kulu">Kulu</option>
                                    <option rel="Himachal Pradesh" value="Lahul and Spiti">Lahul and Spiti</option>
                                    <option rel="Himachal Pradesh" value="Mandi">Mandi</option>
                                    <option rel="Himachal Pradesh" value="Shimla">Shimla</option>
                                    <option rel="Himachal Pradesh" value="Sirmaur">Sirmaur</option>
                                    <option rel="Himachal Pradesh" value="Solan">Solan</option>
                                    <option rel="Himachal Pradesh" value="Una">Una</option>
                                    <option rel="Himachal Pradesh" value="Other">Other</option>
                                    <option rel="Jammu and Kashmir" value="" selected>City / शहर</option>
                                    <option rel="Jammu and Kashmir" value="Anantnag">Anantnag</option>
                                    <option rel="Jammu and Kashmir" value="Badgam">Badgam</option>
                                    <option rel="Jammu and Kashmir" value="Bandipore">Bandipore</option>
                                    <option rel="Jammu and Kashmir" value="Baramula">Baramula</option>
                                    <option rel="Jammu and Kashmir" value="Doda">Doda</option>
                                    <option rel="Jammu and Kashmir" value="Ganderbal">Ganderbal</option>
                                    <option rel="Jammu and Kashmir" value="Jammu">Jammu</option>
                                    <option rel="Jammu and Kashmir" value="Kargil">Kargil</option>
                                    <option rel="Jammu and Kashmir" value="Kathua">Kathua</option>
                                    <option rel="Jammu and Kashmir" value="Kishtwar">Kishtwar</option>
                                    <option rel="Jammu and Kashmir" value="Kulgam">Kulgam</option>
                                    <option rel="Jammu and Kashmir" value="Kupwara">Kupwara</option>
                                    <option rel="Jammu and Kashmir" value="Ladakh">Ladakh</option>
                                    <option rel="Jammu and Kashmir" value="Pulwama">Pulwama</option>
                                    <option rel="Jammu and Kashmir" value="Punch">Punch</option>
                                    <option rel="Jammu and Kashmir" value="Rajauri">Rajauri</option>
                                    <option rel="Jammu and Kashmir" value="Ramban">Ramban</option>
                                    <option rel="Jammu and Kashmir" value="Reasi">Reasi</option>
                                    <option rel="Jammu and Kashmir" value="Samba">Samba</option>
                                    <option rel="Jammu and Kashmir" value="Shupiyan">Shupiyan</option>
                                    <option rel="Jammu and Kashmir" value="Srinagar">Srinagar</option>
                                    <option rel="Jammu and Kashmir" value="Udhampur">Udhampur</option>
                                    <option rel="Jammu and Kashmir" value="Other">Other</option>
                                    <option rel="Jharkhand" value="" selected>City / शहर</option>
                                    <option rel="Jharkhand" value="Bokaro">Bokaro</option>
                                    <option rel="Jharkhand" value="Chatra">Chatra</option>
                                    <option rel="Jharkhand" value="Deogarh">Deogarh</option>
                                    <option rel="Jharkhand" value="Dhanbad">Dhanbad</option>
                                    <option rel="Jharkhand" value="Dumka">Dumka</option>
                                    <option rel="Jharkhand" value="Garhwa">Garhwa</option>
                                    <option rel="Jharkhand" value="Giridih">Giridih</option>
                                    <option rel="Jharkhand" value="Godda">Godda</option>
                                    <option rel="Jharkhand" value="Gumla">Gumla</option>
                                    <option rel="Jharkhand" value="Hazaribag">Hazaribag</option>
                                    <option rel="Jharkhand" value="Jamtara">Jamtara</option>
                                    <option rel="Jharkhand" value="Khunti">Khunti</option>
                                    <option rel="Jharkhand" value="Kodarma">Kodarma</option>
                                    <option rel="Jharkhand" value="Latehar">Latehar</option>
                                    <option rel="Jharkhand" value="Lohardaga">Lohardaga</option>
                                    <option rel="Jharkhand" value="Pakur">Pakur</option>
                                    <option rel="Jharkhand" value="Palamu">Palamu</option>
                                    <option rel="Jharkhand" value="Pashchim Singhbhum">Pashchim Singhbhum</option>
                                    <option rel="Jharkhand" value="Purba Singhbhum">Purba Singhbhum</option>
                                    <option rel="Jharkhand" value="Ramgarh">Ramgarh</option>
                                    <option rel="Jharkhand" value="Ranchi">Ranchi</option>
                                    <option rel="Jharkhand" value="Sahibganj">Sahibganj</option>
                                    <option rel="Jharkhand" value="Saraikela">Saraikela</option>
                                    <option rel="Jharkhand" value="Simdega">Simdega</option>
                                    <option rel="Jharkhand" value="Other">Other</option>
                                    <option rel="Karnataka" value="" selected>City / शहर</option>
                                    <option rel="Karnataka" value="Bagalkot">Bagalkot</option>
                                    <option rel="Karnataka" value="Bangalore Rural">Bangalore Rural</option>
                                    <option rel="Karnataka" value="Bangalore Urban">Bangalore Urban</option>
                                    <option rel="Karnataka" value="Belgaum">Belgaum</option>
                                    <option rel="Karnataka" value="Bellary">Bellary</option>
                                    <option rel="Karnataka" value="Bidar">Bidar</option>
                                    <option rel="Karnataka" value="Bijapur">Bijapur</option>
                                    <option rel="Karnataka" value="Chamrajnagar">Chamrajnagar</option>
                                    <option rel="Karnataka" value="Chikkaballapur">Chikkaballapur</option>
                                    <option rel="Karnataka" value="Chikmagalur">Chikmagalur</option>
                                    <option rel="Karnataka" value="Chitradurga">Chitradurga</option>
                                    <option rel="Karnataka" value="Dakshina Kannada">Dakshina Kannada</option>
                                    <option rel="Karnataka" value="Davanagere">Davanagere</option>
                                    <option rel="Karnataka" value="Dharwad">Dharwad</option>
                                    <option rel="Karnataka" value="Gadag">Gadag</option>
                                    <option rel="Karnataka" value="Gulbarga">Gulbarga</option>
                                    <option rel="Karnataka" value="Hassan">Hassan</option>
                                    <option rel="Karnataka" value="Haveri">Haveri</option>
                                    <option rel="Karnataka" value="Kodagu">Kodagu</option>
                                    <option rel="Karnataka" value="Kolar">Kolar</option>
                                    <option rel="Karnataka" value="Koppal">Koppal</option>
                                    <option rel="Karnataka" value="Mandya">Mandya</option>
                                    <option rel="Karnataka" value="Mysore">Mysore</option>
                                    <option rel="Karnataka" value="Raichur">Raichur</option>
                                    <option rel="Karnataka" value="Ramanagara">Ramanagara</option>
                                    <option rel="Karnataka" value="Shimoga">Shimoga</option>
                                    <option rel="Karnataka" value="Tumkur">Tumkur</option>
                                    <option rel="Karnataka" value="Udupi">Udupi</option>
                                    <option rel="Karnataka" value="Uttar Kannada">Uttar Kannada</option>
                                    <option rel="Karnataka" value="Yadgir">Yadgir</option>
                                    <option rel="Karnataka" value="Other">Other</option>
                                    <option rel="Kerala" value="" selected>City / शहर</option>
                                    <option rel="Kerala" value="Alappuzha">Alappuzha</option>
                                    <option rel="Kerala" value="Ernakulam">Ernakulam</option>
                                    <option rel="Kerala" value="Idukki">Idukki</option>
                                    <option rel="Kerala" value="Kannur">Kannur</option>
                                    <option rel="Kerala" value="Kasaragod District">Kasaragod District</option>
                                    <option rel="Kerala" value="Kollam">Kollam</option>
                                    <option rel="Kerala" value="Kottayam">Kottayam</option>
                                    <option rel="Kerala" value="Kozhikode">Kozhikode</option>
                                    <option rel="Kerala" value="Malappuram">Malappuram</option>
                                    <option rel="Kerala" value="Palakkad district">Palakkad district</option>
                                    <option rel="Kerala" value="Pattanamtitta">Pattanamtitta</option>
                                    <option rel="Kerala" value="Thiruvananthapuram">Thiruvananthapuram</option>
                                    <option rel="Kerala" value="Thrissur District">Thrissur District</option>
                                    <option rel="Kerala" value="Wayanad">Wayanad</option>
                                    <option rel="Kerala" value="Other">Other</option>
                                    <option rel="Maharashtra" value="" selected>City / शहर</option>
                                    <option rel="Maharashtra" value="Ahmadnagar">Ahmadnagar</option>
                                    <option rel="Maharashtra" value="Akola">Akola</option>
                                    <option rel="Maharashtra" value="Amravati Division">Amravati Division</option>
                                    <option rel="Maharashtra" value="Aurangabad Division">Aurangabad Division</option>
                                    <option rel="Maharashtra" value="Bhandara">Bhandara</option>
                                    <option rel="Maharashtra" value="Bid">Bid</option>
                                    <option rel="Maharashtra" value="Buldana">Buldana</option>
                                    <option rel="Maharashtra" value="Chandrapur">Chandrapur</option>
                                    <option rel="Maharashtra" value="Dhule">Dhule</option>
                                    <option rel="Maharashtra" value="Gadchiroli">Gadchiroli</option>
                                    <option rel="Maharashtra" value="Gondiya">Gondiya</option>
                                    <option rel="Maharashtra" value="Hingoli">Hingoli</option>
                                    <option rel="Maharashtra" value="Jalgaon">Jalgaon</option>
                                    <option rel="Maharashtra" value="Jalna">Jalna</option>
                                    <option rel="Maharashtra" value="Kolhapur">Kolhapur</option>
                                    <option rel="Maharashtra" value="Latur">Latur</option>
                                    <option rel="Maharashtra" value="Mumbai">Mumbai</option>
                                    <option rel="Maharashtra" value="Mumbai Suburban">Mumbai Suburban</option>
                                    <option rel="Maharashtra" value="Nagpur Division">Nagpur Division</option>
                                    <option rel="Maharashtra" value="Nanded">Nanded</option>
                                    <option rel="Maharashtra" value="Nandurbar">Nandurbar</option>
                                    <option rel="Maharashtra" value="Nashik Division">Nashik Division</option>
                                    <option rel="Maharashtra" value="Osmanabad">Osmanabad</option>
                                    <option rel="Maharashtra" value="Parbhani">Parbhani</option>
                                    <option rel="Maharashtra" value="Pune Division">Pune Division</option>
                                    <option rel="Maharashtra" value="Raigarh">Raigarh</option>
                                    <option rel="Maharashtra" value="Ratnagiri">Ratnagiri</option>
                                    <option rel="Maharashtra" value="Sangli">Sangli</option>
                                    <option rel="Maharashtra" value="Satara Division">Satara Division</option>
                                    <option rel="Maharashtra" value="Sindhudurg">Sindhudurg</option>
                                    <option rel="Maharashtra" value="Solapur">Solapur</option>
                                    <option rel="Maharashtra" value="Thane">Thane</option>
                                    <option rel="Maharashtra" value="Wardha">Wardha</option>
                                    <option rel="Maharashtra" value="Washim">Washim</option>
                                    <option rel="Maharashtra" value="Yavatmal">Yavatmal</option>
                                    <option rel="Maharashtra" value="Other">Other</option>
                                    <option rel="Nagaland" value="" selected>City / शहर</option>
                                    <option rel="Nagaland" value="Dimapur">Dimapur</option>
                                    <option rel="Nagaland" value="Kiphire">Kiphire</option>
                                    <option rel="Nagaland" value="Kohima">Kohima</option>
                                    <option rel="Nagaland" value="Longleng">Longleng</option>
                                    <option rel="Nagaland" value="Mokokchung">Mokokchung</option>
                                    <option rel="Nagaland" value="Mon">Mon</option>
                                    <option rel="Nagaland" value="Peren">Peren</option>
                                    <option rel="Nagaland" value="Phek">Phek</option>
                                    <option rel="Nagaland" value="Tuensang District">Tuensang District</option>
                                    <option rel="Nagaland" value="Wokha">Wokha</option>
                                    <option rel="Nagaland" value="Zunheboto">Zunheboto</option>
                                    <option rel="Nagaland" value="Other">Other</option>
                                    <option rel="Odisha" value="" selected>City / शहर</option>
                                    <option rel="Odisha" value="Angul District">Angul District</option>
                                    <option rel="Odisha" value="Balangir">Balangir</option>
                                    <option rel="Odisha" value="Baleshwar">Baleshwar</option>
                                    <option rel="Odisha" value="Baragarh">Baragarh</option>
                                    <option rel="Odisha" value="Baudh">Baudh</option>
                                    <option rel="Odisha" value="Bhadrak">Bhadrak</option>
                                    <option rel="Odisha" value="Cuttack">Cuttack</option>
                                    <option rel="Odisha" value="Debagarh">Debagarh</option>
                                    <option rel="Odisha" value="Dhenkanal">Dhenkanal</option>
                                    <option rel="Odisha" value="Gajapati">Gajapati</option>
                                    <option rel="Odisha" value="Ganjam">Ganjam</option>
                                    <option rel="Odisha" value="Jagatsinghpur">Jagatsinghpur</option>
                                    <option rel="Odisha" value="Jajpur">Jajpur</option>
                                    <option rel="Odisha" value="Jharsuguda">Jharsuguda</option>
                                    <option rel="Odisha" value="Kalahandi">Kalahandi</option>
                                    <option rel="Odisha" value="Kandhamal">Kandhamal</option>
                                    <option rel="Odisha" value="Kendrapara">Kendrapara</option>
                                    <option rel="Odisha" value="Kendujhar">Kendujhar</option>
                                    <option rel="Odisha" value="Khordha">Khordha</option>
                                    <option rel="Odisha" value="Koraput">Koraput</option>
                                    <option rel="Odisha" value="Malkangiri">Malkangiri</option>
                                    <option rel="Odisha" value="Mayurbhanj">Mayurbhanj</option>
                                    <option rel="Odisha" value="Nabarangpur">Nabarangpur</option>
                                    <option rel="Odisha" value="Nayagarh District">Nayagarh District</option>
                                    <option rel="Odisha" value="Nuapada">Nuapada</option>
                                    <option rel="Odisha" value="Puri">Puri</option>
                                    <option rel="Odisha" value="Rayagada">Rayagada</option>
                                    <option rel="Odisha" value="Sambalpur">Sambalpur</option>
                                    <option rel="Odisha" value="Subarnapur">Subarnapur</option>
                                    <option rel="Odisha" value="Sundargarh">Sundargarh</option>
                                    <option rel="Odisha" value="Other">Other</option>
                                    <option rel="Panjab" value="" selected>City / शहर</option>
                                    <option rel="Panjab" value="Ajitgarh">Ajitgarh</option>
                                    <option rel="Panjab" value="Amritsar">Amritsar</option>
                                    <option rel="Panjab" value="Barnala">Barnala</option>
                                    <option rel="Panjab" value="Bathinda">Bathinda</option>
                                    <option rel="Panjab" value="Faridkot">Faridkot</option>
                                    <option rel="Panjab" value="Fatehgarh Sahib">Fatehgarh Sahib</option>
                                    <option rel="Panjab" value="Fazilka">Fazilka</option>
                                    <option rel="Panjab" value="Ferozepur">Ferozepur</option>
                                    <option rel="Panjab" value="Gurdaspur">Gurdaspur</option>
                                    <option rel="Panjab" value="Hoshiarpur">Hoshiarpur</option>
                                    <option rel="Panjab" value="Jalandhar">Jalandhar</option>
                                    <option rel="Panjab" value="Kapurthala">Kapurthala</option>
                                    <option rel="Panjab" value="Ludhiana">Ludhiana</option>
                                    <option rel="Panjab" value="Mansa">Mansa</option>
                                    <option rel="Panjab" value="Moga">Moga</option>
                                    <option rel="Panjab" value="Muktsar">Muktsar</option>
                                    <option rel="Panjab" value="Pathankot">Pathankot</option>
                                    <option rel="Panjab" value="Patiala">Patiala</option>
                                    <option rel="Panjab" value="Rupnagar">Rupnagar</option>
                                    <option rel="Panjab" value="Sangrur">Sangrur</option>
                                    <option rel="Panjab" value="Shahid Bhagat Singh Nagar">Shahid Bhagat Singh Nagar</option>
                                    <option rel="Panjab" value="Tarn Taran">Tarn Taran</option>
                                    <option rel="Panjab" value="Other">Other</option>
                                    <option rel="Rajasthan" value="" selected>City / शहर</option>
                                    <option rel="Rajasthan" value="Ajmer">Ajmer</option>
                                    <option rel="Rajasthan" value="Alwar">Alwar</option>
                                    <option rel="Rajasthan" value="Banswara">Banswara</option>
                                    <option rel="Rajasthan" value="Baran">Baran</option>
                                    <option rel="Rajasthan" value="Barmer">Barmer</option>
                                    <option rel="Rajasthan" value="Bharatpur">Bharatpur</option>
                                    <option rel="Rajasthan" value="Bhilwara">Bhilwara</option>
                                    <option rel="Rajasthan" value="Bikaner">Bikaner</option>
                                    <option rel="Rajasthan" value="Bundi">Bundi</option>
                                    <option rel="Rajasthan" value="Chittaurgarh">Chittaurgarh</option>
                                    <option rel="Rajasthan" value="Churu">Churu</option>
                                    <option rel="Rajasthan" value="Dausa">Dausa</option>
                                    <option rel="Rajasthan" value="Dhaulpur">Dhaulpur</option>
                                    <option rel="Rajasthan" value="Dungarpur">Dungarpur</option>
                                    <option rel="Rajasthan" value="Ganganagar">Ganganagar</option>
                                    <option rel="Rajasthan" value="Hanumangarh">Hanumangarh</option>
                                    <option rel="Rajasthan" value="Jaipur">Jaipur</option>
                                    <option rel="Rajasthan" value="Jaisalmer">Jaisalmer</option>
                                    <option rel="Rajasthan" value="Jalore">Jalore</option>
                                    <option rel="Rajasthan" value="Jhalawar">Jhalawar</option>
                                    <option rel="Rajasthan" value="Jhunjhunun">Jhunjhunun</option>
                                    <option rel="Rajasthan" value="Jodhpur">Jodhpur</option>
                                    <option rel="Rajasthan" value="Karauli">Karauli</option>
                                    <option rel="Rajasthan" value="Kota">Kota</option>
                                    <option rel="Rajasthan" value="Nagaur">Nagaur</option>
                                    <option rel="Rajasthan" value="Pali">Pali</option>
                                    <option rel="Rajasthan" value="Pratapgarh">Pratapgarh</option>
                                    <option rel="Rajasthan" value="Rajsamand">Rajsamand</option>
                                    <option rel="Rajasthan" value="Sawai Madhopur">Sawai Madhopur</option>
                                    <option rel="Rajasthan" value="Sikar">Sikar</option>
                                    <option rel="Rajasthan" value="Sirohi">Sirohi</option>
                                    <option rel="Rajasthan" value="Tonk">Tonk</option>
                                    <option rel="Rajasthan" value="Udaipur">Udaipur</option>
                                    <option rel="Rajasthan" value="Other">Other</option>
                                    <option rel="Tamil Nadu" value="" selected>City / शहर</option>
                                    <option rel="Tamil Nadu" value="Ariyalur">Ariyalur</option>
                                    <option rel="Tamil Nadu" value="Chennai">Chennai</option>
                                    <option rel="Tamil Nadu" value="Coimbatore">Coimbatore</option>
                                    <option rel="Tamil Nadu" value="Cuddalore">Cuddalore</option>
                                    <option rel="Tamil Nadu" value="Dharmapuri">Dharmapuri</option>
                                    <option rel="Tamil Nadu" value="Dindigul">Dindigul</option>
                                    <option rel="Tamil Nadu" value="Erode">Erode</option>
                                    <option rel="Tamil Nadu" value="Kancheepuram">Kancheepuram</option>
                                    <option rel="Tamil Nadu" value="Kanniyakumari">Kanniyakumari</option>
                                    <option rel="Tamil Nadu" value="Karur">Karur</option>
                                    <option rel="Tamil Nadu" value="Krishnagiri">Krishnagiri</option>
                                    <option rel="Tamil Nadu" value="Madurai">Madurai</option>
                                    <option rel="Tamil Nadu" value="Nagapattinam">Nagapattinam</option>
                                    <option rel="Tamil Nadu" value="Namakkal">Namakkal</option>
                                    <option rel="Tamil Nadu" value="Nilgiris District">Nilgiris District</option>
                                    <option rel="Tamil Nadu" value="Perambalur">Perambalur</option>
                                    <option rel="Tamil Nadu" value="Pudukkottai">Pudukkottai</option>
                                    <option rel="Tamil Nadu" value="Ramanathapuram">Ramanathapuram</option>
                                    <option rel="Tamil Nadu" value="Salem">Salem</option>
                                    <option rel="Tamil Nadu" value="Sivaganga">Sivaganga</option>
                                    <option rel="Tamil Nadu" value="Thanjavur">Thanjavur</option>
                                    <option rel="Tamil Nadu" value="Theni">Theni</option>
                                    <option rel="Tamil Nadu" value="Thiruvallur">Thiruvallur</option>
                                    <option rel="Tamil Nadu" value="Thiruvarur">Thiruvarur</option>
                                    <option rel="Tamil Nadu" value="Thoothukkudi">Thoothukkudi</option>
                                    <option rel="Tamil Nadu" value="Tiruchchirappalli">Tiruchchirappalli</option>
                                    <option rel="Tamil Nadu" value="Tirunelveli Kattabo">Tirunelveli Kattabo</option>
                                    <option rel="Tamil Nadu" value="Tiruppur">Tiruppur</option>
                                    <option rel="Tamil Nadu" value="Tiruvannamalai">Tiruvannamalai</option>
                                    <option rel="Tamil Nadu" value="Vellore">Vellore</option>
                                    <option rel="Tamil Nadu" value="Villupuram">Villupuram</option>
                                    <option rel="Tamil Nadu" value="Virudhunagar">Virudhunagar</option>
                                    <option rel="Tamil Nadu" value="Other">Other</option>
                                    <option rel="Uttarakhand" value="" selected>City / शहर</option>
                                    <option rel="Uttarakhand" value="Almora">Almora</option>
                                    <option rel="Uttarakhand" value="Bageshwar">Bageshwar</option>
                                    <option rel="Uttarakhand" value="Chamoli">Chamoli</option>
                                    <option rel="Uttarakhand" value="Champawat">Champawat</option>
                                    <option rel="Uttarakhand" value="Dehradun">Dehradun</option>
                                    <option rel="Uttarakhand" value="Garhwal">Garhwal</option>
                                    <option rel="Uttarakhand" value="Haridwar">Haridwar</option>
                                    <option rel="Uttarakhand" value="Naini Tal">Naini Tal</option>
                                    <option rel="Uttarakhand" value="Pithoragarh">Pithoragarh</option>
                                    <option rel="Uttarakhand" value="Rudraprayag">Rudraprayag</option>
                                    <option rel="Uttarakhand" value="Tehri-Garhwal">Tehri-Garhwal</option>
                                    <option rel="Uttarakhand" value="Udham Singh Nagar">Udham Singh Nagar</option>
                                    <option rel="Uttarakhand" value="Uttarkashi">Uttarkashi</option>
                                    <option rel="Uttarakhand" value="Other">Other</option>
                                    <option rel="Telangana" value="" selected>City / शहर</option>
                                    <option rel="Telangana" value="Adilabad">Adilabad</option>
                                    <option rel="Telangana" value="Hyderabad">Hyderabad</option>
                                    <option rel="Telangana" value="Karimnagar">Karimnagar</option>
                                    <option rel="Telangana" value="Khammam">Khammam</option>
                                    <option rel="Telangana" value="Mahbubnagar">Mahbubnagar</option>
                                    <option rel="Telangana" value="Medak">Medak</option>
                                    <option rel="Telangana" value="Nalgonda">Nalgonda</option>
                                    <option rel="Telangana" value="Nizamabad District">Nizamabad District</option>
                                    <option rel="Telangana" value="Rangareddi">Rangareddi</option>
                                    <option rel="Telangana" value="Warangal">Warangal</option>
                                    <option rel="Telangana" value="Other">Other</option>
                                    <option rel="Tripura" value="" selected>City / शहर</option>
                                    <option rel="Tripura" value="Dhalai">Dhalai</option>
                                    <option rel="Tripura" value="North Tripura">North Tripura</option>
                                    <option rel="Tripura" value="South Tripura">South Tripura</option>
                                    <option rel="Tripura" value="West Tripura">West Tripura</option>
                                    <option rel="Tripura" value="Other">Other</option>
                                    <option rel="Andaman and Nicobar" value="" selected>City / शहर</option>
                                    <option rel="Andaman and Nicobar" value="Nicobar">Nicobar</option>
                                    <option rel="Andaman and Nicobar" value="North  &amp; Middle Andaman">North &amp; Middle Andaman</option>
                                    <option rel="Andaman and Nicobar" value="South Andaman">South Andaman</option>
                                    <option rel="Andaman and Nicobar" value="Other">Other</option>
                                    <option rel="Chandigarh" value="" selected>City / शहर</option>
                                    <option rel="Chandigarh" value="Chandigarh">Chandigarh</option>
                                    <option rel="Chandigarh" value="Other">Other</option>
                                    <option rel="Dadra and Nagar Haveli" value="" selected>City / शहर</option>
                                    <option rel="Dadra and Nagar Haveli" value="Dadra &amp; Nagar Haveli">Dadra &amp; Nagar Haveli</option>
                                    <option rel="Dadra and Nagar Haveli" value="Other">Other</option>
                                    <option rel="Daman and Diu" value="" selected>City / शहर</option>
                                    <option rel="Daman and Diu" value="Daman District">Daman District</option>
                                    <option rel="Daman and Diu" value="Diu">Diu</option>
                                    <option rel="Daman and Diu" value="Other">Other</option>
                                    <option rel="Lakshadweep" value="" selected>City / शहर</option>
                                    <option rel="Lakshadweep" value="Lakshadweep">Lakshadweep</option>
                                    <option rel="Lakshadweep" value="Other">Other</option>
                                    <option rel="Puducherry" value="" selected>City / शहर</option>
                                    <option rel="Puducherry" value="Karaikal">Karaikal</option>
                                    <option rel="Puducherry" value="Mahe">Mahe</option>
                                    <option rel="Puducherry" value="Puducherry">Puducherry</option>
                                    <option rel="Puducherry" value="Yanam">Yanam</option>
                                    <option rel="Puducherry" value="Other">Other</option>
                                    <option rel="Uttar Pradesh" value="" selected>City / शहर</option>
                                    <option rel="Uttar Pradesh" value="Agra">Agra</option>
                                    <option rel="Uttar Pradesh" value="Aligarh">Aligarh</option>
                                    <option rel="Uttar Pradesh" value="Allahabad">Allahabad</option>
                                    <option rel="Uttar Pradesh" value="Ambedkar Nagar">Ambedkar Nagar</option>
                                    <option rel="Uttar Pradesh" value="Auraiya">Auraiya</option>
                                    <option rel="Uttar Pradesh" value="Azamgarh">Azamgarh</option>
                                    <option rel="Uttar Pradesh" value="Baghpat">Baghpat</option>
                                    <option rel="Uttar Pradesh" value="Bahraich">Bahraich</option>
                                    <option rel="Uttar Pradesh" value="Ballia">Ballia</option>
                                    <option rel="Uttar Pradesh" value="Balrampur">Balrampur</option>
                                    <option rel="Uttar Pradesh" value="Banda">Banda</option>
                                    <option rel="Uttar Pradesh" value="Bara Banki">Bara Banki</option>
                                    <option rel="Uttar Pradesh" value="Bareilly">Bareilly</option>
                                    <option rel="Uttar Pradesh" value="Basti">Basti</option>
                                    <option rel="Uttar Pradesh" value="Bijnor">Bijnor</option>
                                    <option rel="Uttar Pradesh" value="Budaun">Budaun</option>
                                    <option rel="Uttar Pradesh" value="Bulandshahr">Bulandshahr</option>
                                    <option rel="Uttar Pradesh" value="Chandauli District">Chandauli District</option>
                                    <option rel="Uttar Pradesh" value="Chitrakoot">Chitrakoot</option>
                                    <option rel="Uttar Pradesh" value="Deoria">Deoria</option>
                                    <option rel="Uttar Pradesh" value="Etah">Etah</option>
                                    <option rel="Uttar Pradesh" value="Etawah">Etawah</option>
                                    <option rel="Uttar Pradesh" value="Faizabad">Faizabad</option>
                                    <option rel="Uttar Pradesh" value="Farrukhabad">Farrukhabad</option>
                                    <option rel="Uttar Pradesh" value="Fatehpur">Fatehpur</option>
                                    <option rel="Uttar Pradesh" value="Firozabad">Firozabad</option>
                                    <option rel="Uttar Pradesh" value="Gautam Buddha Nagar">Gautam Buddha Nagar</option>
                                    <option rel="Uttar Pradesh" value="Ghaziabad">Ghaziabad</option>
                                    <option rel="Uttar Pradesh" value="Ghazipur">Ghazipur</option>
                                    <option rel="Uttar Pradesh" value="Gonda">Gonda</option>
                                    <option rel="Uttar Pradesh" value="Gorakhpur">Gorakhpur</option>
                                    <option rel="Uttar Pradesh" value="Hamirpur">Hamirpur</option>
                                    <option rel="Uttar Pradesh" value="Hardoi">Hardoi</option>
                                    <option rel="Uttar Pradesh" value="Hathras">Hathras</option>
                                    <option rel="Uttar Pradesh" value="Jalaun">Jalaun</option>
                                    <option rel="Uttar Pradesh" value="Jaunpur">Jaunpur</option>
                                    <option rel="Uttar Pradesh" value="Jhansi">Jhansi</option>
                                    <option rel="Uttar Pradesh" value="Jyotiba Phule Nagar">Jyotiba Phule Nagar</option>
                                    <option rel="Uttar Pradesh" value="Kannauj">Kannauj</option>
                                    <option rel="Uttar Pradesh" value="Kanpur">Kanpur</option>
                                    <option rel="Uttar Pradesh" value="Kanpur Dehat">Kanpur Dehat</option>
                                    <option rel="Uttar Pradesh" value="Kasganj">Kasganj</option>
                                    <option rel="Uttar Pradesh" value="Kaushambi District">Kaushambi District</option>
                                    <option rel="Uttar Pradesh" value="Kheri">Kheri</option>
                                    <option rel="Uttar Pradesh" value="Kushinagar">Kushinagar</option>
                                    <option rel="Uttar Pradesh" value="Lalitpur">Lalitpur</option>
                                    <option rel="Uttar Pradesh" value="Lucknow District">Lucknow District</option>
                                    <option rel="Uttar Pradesh" value="Maharajganj">Maharajganj</option>
                                    <option rel="Uttar Pradesh" value="Mahoba">Mahoba</option>
                                    <option rel="Uttar Pradesh" value="Mainpuri">Mainpuri</option>
                                    <option rel="Uttar Pradesh" value="Mathura">Mathura</option>
                                    <option rel="Uttar Pradesh" value="Mau">Mau</option>
                                    <option rel="Uttar Pradesh" value="Meerut">Meerut</option>
                                    <option rel="Uttar Pradesh" value="Mirzapur">Mirzapur</option>
                                    <option rel="Uttar Pradesh" value="Moradabad">Moradabad</option>
                                    <option rel="Uttar Pradesh" value="Muzaffarnagar">Muzaffarnagar</option>
                                    <option rel="Uttar Pradesh" value="Pilibhit">Pilibhit</option>
                                    <option rel="Uttar Pradesh" value="Pratapgarh">Pratapgarh</option>
                                    <option rel="Uttar Pradesh" value="Rae Bareli">Rae Bareli</option>
                                    <option rel="Uttar Pradesh" value="Rampur">Rampur</option>
                                    <option rel="Uttar Pradesh" value="Saharanpur">Saharanpur</option>
                                    <option rel="Uttar Pradesh" value="Sant Kabir Nagar">Sant Kabir Nagar</option>
                                    <option rel="Uttar Pradesh" value="Sant Ravi Das Nagar">Sant Ravi Das Nagar</option>
                                    <option rel="Uttar Pradesh" value="Shahjahanpur">Shahjahanpur</option>
                                    <option rel="Uttar Pradesh" value="Shrawasti">Shrawasti</option>
                                    <option rel="Uttar Pradesh" value="Siddharthangar">Siddharthangar</option>
                                    <option rel="Uttar Pradesh" value="Sitapur">Sitapur</option>
                                    <option rel="Uttar Pradesh" value="Sonbhadra">Sonbhadra</option>
                                    <option rel="Uttar Pradesh" value="Sultanpur">Sultanpur</option>
                                    <option rel="Uttar Pradesh" value="Unnao">Unnao</option>
                                    <option rel="Uttar Pradesh" value="Varanasi">Varanasi</option>
                                    <option rel="Uttar Pradesh" value="Other">Other</option>
                                    <option rel="West Bengal" value="" selected>City / शहर</option>
                                    <option rel="West Bengal" value="Bankura">Bankura</option>
                                    <option rel="West Bengal" value="Barddhaman">Barddhaman</option>
                                    <option rel="West Bengal" value="Birbhum">Birbhum</option>
                                    <option rel="West Bengal" value="Dakshin Dinajpur">Dakshin Dinajpur</option>
                                    <option rel="West Bengal" value="Darjiling">Darjiling</option>
                                    <option rel="West Bengal" value="Haora">Haora</option>
                                    <option rel="West Bengal" value="Hugli">Hugli</option>
                                    <option rel="West Bengal" value="Jalpaiguri">Jalpaiguri</option>
                                    <option rel="West Bengal" value="Koch Bihar">Koch Bihar</option>
                                    <option rel="West Bengal" value="Kolkata">Kolkata</option>
                                    <option rel="West Bengal" value="Maldah">Maldah</option>
                                    <option rel="West Bengal" value="Murshidabad">Murshidabad</option>
                                    <option rel="West Bengal" value="Nadia">Nadia</option>
                                    <option rel="West Bengal" value="North 24 Parganas">North 24 Parganas</option>
                                    <option rel="West Bengal" value="Paschim Medinipur">Paschim Medinipur</option>
                                    <option rel="West Bengal" value="Purba Medinipur">Purba Medinipur</option>
                                    <option rel="West Bengal" value="Puruliya">Puruliya</option>
                                    <option rel="West Bengal" value="South 24 Parganas">South 24 Parganas</option>
                                    <option rel="West Bengal" value="Uttar Dinajpur">Uttar Dinajpur</option>
                                    <option rel="West Bengal" value="Other">Other</option>
                                </select>
                            </div>
                        </li>
                        <li>
                            <input type="text" name="district" maxlength="60" placeholder="District / जिला *" pattern="[A-za-z][A-Za-z0-9 '.-]+" required oninvalid="setCustomValidity('Please enter the district')" oninput="setCustomValidity('')" class="text-box">
                        </li>
                        <li>
                            <input type="text" name="tehsil" placeholder="Tehsil / तहसील" pattern="[A-Za-z0-9 '.-]+" class="text-box">
                        </li>
                        <li>
                            <input type="text" name="village" placeholder="Village / गाँव" pattern="[A-Za-z0-9 '.-]+" class="text-box">
                        </li>
                        <li>
                            <input type="text" name="pincode"  pattern="^(\d{6})$"  placeholder="Pin code / पिन कोड" class="text-box" oninvalid="setCustomValidity('Will accept numbers only, minimum 6 digits')" oninput="setCustomValidity('')">
                        </li>
                    </ul>
                    <p class="submit-box">
                        <button type="submit" value="Submit" class="submit-button">Submit / जमा करें</button>
                    </p>
                </div>
            </form>
            <div class="more-info MT80">
                <h1>SWARAJ PURPOSE</h1>
                <p class="titile-info">Established in 1974, Swaraj Tractors is India’s first indigenous tractor brand. A rapidly growing brand with a wide range of tractors from 15 HP to 65 HP, Swaraj stands firmly among the top tractor brands in India. Select from our range: 717 | 724 | 825 | 834 | 735 | 841 | 843 | 744 | 742 | 855 | 960 | 963. Book now to get the best offers and services in store.</p>
            </div>
            <div class="MT50">
                <a href="https://www.swarajtractors.com/products" target="_blank" class="submit-button">View Products</a>
            </div>
        </div>
    </div>
    <div class="popupBox2" id="dialog"> 
        <p class="close-btn"><a href="#" onClick="hide_album()"></a></p>
        <div class="CTR">
            <figure><img src="images/popup-img.png" alt=""></figure>
            <p>फॉर्म जमा करने के लिए धन्यवाद।<br>हम आपसे जल्द ही संपर्क करेंगे।</p>
        </div>
    </div>
    <div class="popupBox2" id="dialog_new"> 
        <p class="close-btn"><a href="#" onClick="hide_album_new()"></a></p>
        <div class="CTR">
            <p>कुछ गलत हो गया।<br>कृपया फिर से फॉर्म जमा करें।</p>
        </div>
    </div>
    <footer><a href="https://www.swarajtractors.com/" target="_blank">Visit website</a><br/>Copyright 2020 Mahindra & Mahindra Ltd., Swaraj Division.</footer>
    <script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>
    <script src="js/jquery.dropdown.js"></script>
    <script type="text/javascript" src="js/owl.carousel.min.js"></script>
    <script type="text/javascript" src="js/main.js"></script>
    <script>
        $(document).ready(function(){
            $('.hero-banner').owlCarousel({
                loop: true,
                nav: false,
                dots: true,
                autoplay:true,
                autoplayTimeout:5000,
                autoplayHoverPause:true,
                items: 1
            });
            $.get("security/server_FormToken.php", function(response){
                var json    = JSON.parse(response);
                var myForm  = document.forms['formsubmit'];
                var input   = document.createElement('input');
                input.type  = 'hidden';
                input.name  = json.name;
                input.value = json.token;
                myForm.appendChild(input);
            });
            var $select1 = $('#state'),
                $select2 = $('#city'),
                $options = $select2.find('option');
                
            $select1.on('change', function() {
                var selectedCountry = $(this).children("option:selected").val();
                if (selectedCountry == '') {
                    $select2.html($options.filter('[rel="empty"]'));
                } else {
                    $select2.html($options.filter('[rel="' + this.value + '"]'));
                }
            } ).trigger('change');

            <?php if ($success) { ?>
                show_album();
                $('#dialog').fadeIn();
            <?php } ?>    
            <?php if ($csrf_attack) { ?>
                show_album_new();
                $('#dialog_new').fadeIn();
            <?php } ?> 

            if ( window.history.replaceState ) {
                window.history.replaceState( null, null, window.location.href );
            }
        });
    </script>
</body>

</html>
