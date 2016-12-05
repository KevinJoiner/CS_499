<?php include 'config/header.php';
require_once('../sql_connector.php');?>

<?php
if(isset($_POST['submit'])) {
	
	//define function to retrieve JSON string from numverify.com
	//credit for this function is given to http://stackoverflow.com/questions/6516902/how-to-get-response-using-curl-in-php
	function get_web_page($url) {
		$options = array(
			CURLOPT_RETURNTRANSFER => true,   // return web page
			CURLOPT_HEADER         => false,  // don't return headers
			CURLOPT_FOLLOWLOCATION => true,   // follow redirects
			CURLOPT_MAXREDIRS      => 10,     // stop after 10 redirects
			CURLOPT_ENCODING       => "",     // handle compressed
			CURLOPT_USERAGENT      => "test", // name of client
			CURLOPT_AUTOREFERER    => true,   // set referrer on redirect
			CURLOPT_CONNECTTIMEOUT => 120,    // time-out on connect
			CURLOPT_TIMEOUT        => 120,    // time-out on response
		); 

		$ch = curl_init($url);
		curl_setopt_array($ch, $options);

		$content  = curl_exec($ch);

		curl_close($ch);

		return $content;
	}
	
    $NumberInputError = False;
	$PhoneNumber = "";
	$CountryCode = $mysqli->real_escape_string(trim($_POST['countrycode']));
	
	//build the phone number string from the fields the user filled out
	//raise an error flag if anything they type is invalid (not a number)
    if (preg_match('%[0-9]%', stripslashes(trim($_POST['areacode'])))) {
        $PhoneNumber .= $mysqli->real_escape_string(trim($_POST['areacode']));
    }
    else {
        $NumberInputError = True;
    }
	if (preg_match('%[0-9]%', stripslashes(trim($_POST['numberpart1'])))) {
        $PhoneNumber .= $mysqli->real_escape_string(trim($_POST['numberpart1']));
    }
    else {
        $NumberInputError = True;
    }
	if (preg_match('%[0-9]%', stripslashes(trim($_POST['numberpart2'])))) {
        $PhoneNumber .= $mysqli->real_escape_string(trim($_POST['numberpart2']));
    }
    else {
        $NumberInputError = True;
    }
	
    if ($NumberInputError == False) {
		/////////get the user's carrier
		//set up API call to numverify.com
		$key = "e0e509665acabff0ffbe18d2942681c4";
		$webpage = "http://apilayer.net/api/validate?access_key=".$key."&number=".$PhoneNumber."&country_code=".$CountryCode."&format=1";
		
		//grab JSON result string and check to see if it's valid
		$response = get_web_page($webpage);
		$response_array = array();
		$response_array = json_decode($response,true);
		$valid = $response_array['valid'];
		
		//if valid, send a text to the number given by the user
		if ($valid == true){
			$Carrier = strtoupper($response_array['carrier']);
			$email_prefix = $response_array['local_format'];
			$email_suffix = "";
			//currently only checks top five carriers in the United States/Canada.
			//could be expanded to check for ALL carriers in US and internationally
			if (strpos($Carrier, 'VERIZON') == true){
				$email_suffix .= 'vtext.com';
			} else if (strpos($Carrier, 'AT&T') == true) {
				$email_suffix .= 'txt.att.net';
			} else if (strpos($Carrier, 'T-MOBILE') == true) {
				$email_suffix .= 'tmomail.net';
			} else if (strpos($Carrier, 'US CELL') == true) {
				$email_suffix .= 'email.uscc.net';
			} else if (strpos($Carrier, 'SPRINT') == true) {
				$email_suffix .= 'messaging.sprintpcs.com';
			}
			$text_email = $email_prefix.'@'.$email_suffix;
			
			//send an introductory text
			$message = 'Welcome to the SQS text subscription list! From now on, you will receive important updates about the site.';
			$headers = "From: SQS Training\r\n";
			mail($text_email,'SQS Subscription Confirmation',$message,$headers);
		}
		else {
			echo "Phone number/country code combination not valid. Check number and try again.";
		}
		
		//then, add the number and carrier to the database
        $stmt = $mysqli->prepare('INSERT INTO subscriber VALUES (?,?)');
		$PhoneNumber = $response_array['number'];
        $stmt->bind_param("ss", $PhoneNumber,$Carrier);
        $stmt->execute();
		$stmt->close();
    }
    else {
        echo "Phone number enetered was invalid. Please enter numbers only!";
    }
}
?>

<html>
<div id="home_page">
  <body>
    <div>
		<?php
			if (isset($_SESSION['user'])){	//prompts user with following messages if/if not logged in
				echo '<h2>Hey there! Click the links on the header to navigate the site.</h2>';
			}
			else{
				echo '<h2>Welcome! Please login or sign up using the navigation bar at the top of the screen.</h2>';
			}
			
		?>
		<br/><br/>
		<h3>Sign up for text alerts to stay updated:</h3>
		<form  class= "form-horizontal"action="" method="post">
			<div class="form-group">
				<label class="control-label col-sm-6">Country (defaults to United States)</label>
				<div class="col-sm-6" style="width: initial">
					<select type="countrycode" name="countrycode" style="color: #000000">
						<option value="AF">Afghanistan</option>
						<option value="AX">Åland Islands</option>
						<option value="AL">Albania</option>
						<option value="DZ">Algeria</option>
						<option value="AS">American Samoa</option>
						<option value="AD">Andorra</option>
						<option value="AO">Angola</option>
						<option value="AI">Anguilla</option>
						<option value="AQ">Antarctica</option>
						<option value="AG">Antigua and Barbuda</option>
						<option value="AR">Argentina</option>
						<option value="AM">Armenia</option>
						<option value="AW">Aruba</option>
						<option value="AU">Australia</option>
						<option value="AT">Austria</option>
						<option value="AZ">Azerbaijan</option>
						<option value="BS">Bahamas</option>
						<option value="BH">Bahrain</option>
						<option value="BD">Bangladesh</option>
						<option value="BB">Barbados</option>
						<option value="BY">Belarus</option>
						<option value="BE">Belgium</option>
						<option value="BZ">Belize</option>
						<option value="BJ">Benin</option>
						<option value="BM">Bermuda</option>
						<option value="BT">Bhutan</option>
						<option value="BO">Bolivia, Plurinational State of</option>
						<option value="BQ">Bonaire, Sint Eustatius and Saba</option>
						<option value="BA">Bosnia and Herzegovina</option>
						<option value="BW">Botswana</option>
						<option value="BV">Bouvet Island</option>
						<option value="BR">Brazil</option>
						<option value="IO">British Indian Ocean Territory</option>
						<option value="BN">Brunei Darussalam</option>
						<option value="BG">Bulgaria</option>
						<option value="BF">Burkina Faso</option>
						<option value="BI">Burundi</option>
						<option value="KH">Cambodia</option>
						<option value="CM">Cameroon</option>
						<option value="CA">Canada</option>
						<option value="CV">Cape Verde</option>
						<option value="KY">Cayman Islands</option>
						<option value="CF">Central African Republic</option>
						<option value="TD">Chad</option>
						<option value="CL">Chile</option>
						<option value="CN">China</option>
						<option value="CX">Christmas Island</option>
						<option value="CC">Cocos (Keeling) Islands</option>
						<option value="CO">Colombia</option>
						<option value="KM">Comoros</option>
						<option value="CG">Congo</option>
						<option value="CD">Congo, the Democratic Republic of the</option>
						<option value="CK">Cook Islands</option>
						<option value="CR">Costa Rica</option>
						<option value="CI">Côte d'Ivoire</option>
						<option value="HR">Croatia</option>
						<option value="CU">Cuba</option>
						<option value="CW">Curaçao</option>
						<option value="CY">Cyprus</option>
						<option value="CZ">Czech Republic</option>
						<option value="DK">Denmark</option>
						<option value="DJ">Djibouti</option>
						<option value="DM">Dominica</option>
						<option value="DO">Dominican Republic</option>
						<option value="EC">Ecuador</option>
						<option value="EG">Egypt</option>
						<option value="SV">El Salvador</option>
						<option value="GQ">Equatorial Guinea</option>
						<option value="ER">Eritrea</option>
						<option value="EE">Estonia</option>
						<option value="ET">Ethiopia</option>
						<option value="FK">Falkland Islands (Malvinas)</option>
						<option value="FO">Faroe Islands</option>
						<option value="FJ">Fiji</option>
						<option value="FI">Finland</option>
						<option value="FR">France</option>
						<option value="GF">French Guiana</option>
						<option value="PF">French Polynesia</option>
						<option value="TF">French Southern Territories</option>
						<option value="GA">Gabon</option>
						<option value="GM">Gambia</option>
						<option value="GE">Georgia</option>
						<option value="DE">Germany</option>
						<option value="GH">Ghana</option>
						<option value="GI">Gibraltar</option>
						<option value="GR">Greece</option>
						<option value="GL">Greenland</option>
						<option value="GD">Grenada</option>
						<option value="GP">Guadeloupe</option>
						<option value="GU">Guam</option>
						<option value="GT">Guatemala</option>
						<option value="GG">Guernsey</option>
						<option value="GN">Guinea</option>
						<option value="GW">Guinea-Bissau</option>
						<option value="GY">Guyana</option>
						<option value="HT">Haiti</option>
						<option value="HM">Heard Island and McDonald Islands</option>
						<option value="VA">Holy See (Vatican City State)</option>
						<option value="HN">Honduras</option>
						<option value="HK">Hong Kong</option>
						<option value="HU">Hungary</option>
						<option value="IS">Iceland</option>
						<option value="IN">India</option>
						<option value="ID">Indonesia</option>
						<option value="IR">Iran, Islamic Republic of</option>
						<option value="IQ">Iraq</option>
						<option value="IE">Ireland</option>
						<option value="IM">Isle of Man</option>
						<option value="IL">Israel</option>
						<option value="IT">Italy</option>
						<option value="JM">Jamaica</option>
						<option value="JP">Japan</option>
						<option value="JE">Jersey</option>
						<option value="JO">Jordan</option>
						<option value="KZ">Kazakhstan</option>
						<option value="KE">Kenya</option>
						<option value="KI">Kiribati</option>
						<option value="KP">Korea, Democratic People's Republic of</option>
						<option value="KR">Korea, Republic of</option>
						<option value="KW">Kuwait</option>
						<option value="KG">Kyrgyzstan</option>
						<option value="LA">Lao People's Democratic Republic</option>
						<option value="LV">Latvia</option>
						<option value="LB">Lebanon</option>
						<option value="LS">Lesotho</option>
						<option value="LR">Liberia</option>
						<option value="LY">Libya</option>
						<option value="LI">Liechtenstein</option>
						<option value="LT">Lithuania</option>
						<option value="LU">Luxembourg</option>
						<option value="MO">Macao</option>
						<option value="MK">Macedonia, the former Yugoslav Republic of</option>
						<option value="MG">Madagascar</option>
						<option value="MW">Malawi</option>
						<option value="MY">Malaysia</option>
						<option value="MV">Maldives</option>
						<option value="ML">Mali</option>
						<option value="MT">Malta</option>
						<option value="MH">Marshall Islands</option>
						<option value="MQ">Martinique</option>
						<option value="MR">Mauritania</option>
						<option value="MU">Mauritius</option>
						<option value="YT">Mayotte</option>
						<option value="MX">Mexico</option>
						<option value="FM">Micronesia, Federated States of</option>
						<option value="MD">Moldova, Republic of</option>
						<option value="MC">Monaco</option>
						<option value="MN">Mongolia</option>
						<option value="ME">Montenegro</option>
						<option value="MS">Montserrat</option>
						<option value="MA">Morocco</option>
						<option value="MZ">Mozambique</option>
						<option value="MM">Myanmar</option>
						<option value="NA">Namibia</option>
						<option value="NR">Nauru</option>
						<option value="NP">Nepal</option>
						<option value="NL">Netherlands</option>
						<option value="NC">New Caledonia</option>
						<option value="NZ">New Zealand</option>
						<option value="NI">Nicaragua</option>
						<option value="NE">Niger</option>
						<option value="NG">Nigeria</option>
						<option value="NU">Niue</option>
						<option value="NF">Norfolk Island</option>
						<option value="MP">Northern Mariana Islands</option>
						<option value="NO">Norway</option>
						<option value="OM">Oman</option>
						<option value="PK">Pakistan</option>
						<option value="PW">Palau</option>
						<option value="PS">Palestinian Territory, Occupied</option>
						<option value="PA">Panama</option>
						<option value="PG">Papua New Guinea</option>
						<option value="PY">Paraguay</option>
						<option value="PE">Peru</option>
						<option value="PH">Philippines</option>
						<option value="PN">Pitcairn</option>
						<option value="PL">Poland</option>
						<option value="PT">Portugal</option>
						<option value="PR">Puerto Rico</option>
						<option value="QA">Qatar</option>
						<option value="RE">Réunion</option>
						<option value="RO">Romania</option>
						<option value="RU">Russian Federation</option>
						<option value="RW">Rwanda</option>
						<option value="BL">Saint Barthélemy</option>
						<option value="SH">Saint Helena, Ascension and Tristan da Cunha</option>
						<option value="KN">Saint Kitts and Nevis</option>
						<option value="LC">Saint Lucia</option>
						<option value="MF">Saint Martin (French part)</option>
						<option value="PM">Saint Pierre and Miquelon</option>
						<option value="VC">Saint Vincent and the Grenadines</option>
						<option value="WS">Samoa</option>
						<option value="SM">San Marino</option>
						<option value="ST">Sao Tome and Principe</option>
						<option value="SA">Saudi Arabia</option>
						<option value="SN">Senegal</option>
						<option value="RS">Serbia</option>
						<option value="SC">Seychelles</option>
						<option value="SL">Sierra Leone</option>
						<option value="SG">Singapore</option>
						<option value="SX">Sint Maarten (Dutch part)</option>
						<option value="SK">Slovakia</option>
						<option value="SI">Slovenia</option>
						<option value="SB">Solomon Islands</option>
						<option value="SO">Somalia</option>
						<option value="ZA">South Africa</option>
						<option value="GS">South Georgia and the South Sandwich Islands</option>
						<option value="SS">South Sudan</option>
						<option value="ES">Spain</option>
						<option value="LK">Sri Lanka</option>
						<option value="SD">Sudan</option>
						<option value="SR">Suriname</option>
						<option value="SJ">Svalbard and Jan Mayen</option>
						<option value="SZ">Swaziland</option>
						<option value="SE">Sweden</option>
						<option value="CH">Switzerland</option>
						<option value="SY">Syrian Arab Republic</option>
						<option value="TW">Taiwan, Province of China</option>
						<option value="TJ">Tajikistan</option>
						<option value="TZ">Tanzania, United Republic of</option>
						<option value="TH">Thailand</option>
						<option value="TL">Timor-Leste</option>
						<option value="TG">Togo</option>
						<option value="TK">Tokelau</option>
						<option value="TO">Tonga</option>
						<option value="TT">Trinidad and Tobago</option>
						<option value="TN">Tunisia</option>
						<option value="TR">Turkey</option>
						<option value="TM">Turkmenistan</option>
						<option value="TC">Turks and Caicos Islands</option>
						<option value="TV">Tuvalu</option>
						<option value="UG">Uganda</option>
						<option value="UA">Ukraine</option>
						<option value="AE">United Arab Emirates</option>
						<option value="GB">United Kingdom</option>
						<option selected="selected" value="US">United States</option>
						<option value="UM">United States Minor Outlying Islands</option>
						<option value="UY">Uruguay</option>
						<option value="UZ">Uzbekistan</option>
						<option value="VU">Vanuatu</option>
						<option value="VE">Venezuela, Bolivarian Republic of</option>
						<option value="VN">Viet Nam</option>
						<option value="VG">Virgin Islands, British</option>
						<option value="VI">Virgin Islands, U.S.</option>
						<option value="WF">Wallis and Futuna</option>
						<option value="EH">Western Sahara</option>
						<option value="YE">Yemen</option>
						<option value="ZM">Zambia</option>
						<option value="ZW">Zimbabwe</option>
					</select>
				</div>
			</div>
			
			<div class="form-group" style="padding-top: 5px">
				<label class="control-label col-sm-6">Phone Number</label>
				<div class="col-sm-6" style="width: initial">
					<input type="areacode" name="areacode" size="3" maxlength="3"/>
					<input type="numberpart1" name="numberpart1" size="3" maxlength="3"/>
					<input type="numberpart2" name="numberpart2" size="4" maxlength="4"/>
				</div>
			</div>
			<br/>

			<div class="form-group" style="padding-bottom: 5px">
				<div class="control-label col-sm-6">
				<input class="btn btn-default" type="submit" name="submit" value="Subscribe"/>
				</div>
			</div>
		</form>
	</div>
  </body>
</div>
</html>

