<?php
// Report All PHP Errors
//error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

$title = "Attys - Checkout";

include("../header.php");

?>

<script
    src="https://www.paypal.com/sdk/js?client-id=XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX&currency=GBP">
</script>

<?php

$uk = "United Kingdom";
$currency = "GBP";

$shipping = array(
  $uk => array(9.99,true,true),
  "Republic of Ireland" => array(14.99,true,true),
  "Belgium" => array(14.99,true,true),
  "Luxembourg" => array(14.99,true,true),
  "Netherlands" => array(14.99,true,true),
  "Denmark" => array(14.99,true,true),
  "France" => array(14.99,true,true),
  "Germany" => array(14.99,true,true),
  "Greece" => array(19.99,true,true),
  "Italy" => array(19.99,true,true),
  "Spain" => array(19.99,true,true),
  "Sweden" => array(19.99,true,true),
  "Estonia" => array(19.99,true,true),
  "Latvia" => array(19.99,true,true),
  "Lithuania" => array(19.99,true,true),
  "Bulgaria" => array(39.99,true,true),
  "Croatia" => array(39.99,true,true),
  "Romania" => array(39.99,true,true),
  "Austria" => array(19.99,true,true),
  "Czech Republic" => array(19.99,true,true),
  "Finland" => array(19.99,true,true),
  "Hungary" => array(19.99,true,true),
  "Norway" => array(19.99,true,true),
  "Poland" => array(19.99,true,true),
  "Slovakia" => array(19.99,true,true),
  "Turkey" => array(39.99,false,false),
  "Switzerland" => array(19.99,false,false),
  "USA" => array(29.99,false,false),
  "Canada" => array(24.99,false,false),
  "Australia" => array(39.99,false,false),
  "Indonesia" => array(39.99,false,false),
  "Japan" => array(39.99,false,false),
  "Malaysia" => array(39.99,false,false),
  "Philippines" => array(39.99,false,false),
  "Singapore" => array(39.99,false,false),
  "South Korea" => array(39.99,false,false),
  "Taiwan" => array(39.99,false,false),
  "Thailand" => array(39.99,false,false),
  "India" => array(39.99,false,false),
  "South Africa" => array(39.99,false,false),
  "United Arab Emirates" => array(39.99,false,false)
);

$vatrate = 20;


function getVal($name) {
  $a = " name=\"".$name."\" ";
  $a = $a." id=\"".$name."\" ";
  if (isset($_POST[$name])) {
    $_SESSION[$name] = $_POST[$name];
  }
  if(isset($_SESSION[$name])) {
    $a = $a." value=\"".$_SESSION[$name]."\" ";
  }
  echo $a;
}


function getTextArea($name) {
  $a = "<textarea class=\"form-control\" style=\"height:50px;\"";
  $a = $a." name=\"".$name."\" ";
  $a = $a." id=\"".$name."\" ";
  if (isset($_POST[$name])) {
    $_SESSION[$name] = $_POST[$name];
  }
  $a = $a.">";
  if(isset($_SESSION[$name])) {
    $a = $a.$_SESSION[$name];
  }
  $a = $a."</textarea>";
  echo $a;
}


function getCountry() {
  global $shipping;
  $name ="country";
  if (!empty($_POST[$name])) {
    $_SESSION[$name] = $_POST[$name];
  }
  echo "<select class=\"form-control selectEleman\" name=\"".$name."\">\n";
  $srtc = $shipping;
  ksort($srtc);
  foreach($srtc as $key => $val) {
    $selected="";
    if (isset($_SESSION[$name])) {
      if (strcmp($key,$_SESSION[$name])==0) {
        $selected = " selected";
      }
    }
    echo "<option value=\"".$key."\"".$selected.">".$key."</option>\n";
  }
  echo "</select>\n";
}

function calcVat($v) {
  global $vatrate;
  $v = round($v * $vatrate / 100.0,2);
  return $v;
}

?>

<div class="container">
  <h2>Checkout</h2>


  <!-- Address -->
  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title"><span class="glyphicon glyphicon-phone-alt"></span> Address</h3>
    </div>
    <div class="panel-body">
      <form role="form" method="post" action="?">
        <div class="form-group">
          <label for="inputEmail">Email</label>
          <div>
            <input type="email" class="form-control" <?php getVal("email"); ?> placeholder="mail@domain.com" >
          </div>
        </div>
        <div class="form-group">
          <label for="phone">Phone</label>
          <div>
            <input type="text" class="form-control" <?php getVal("phone"); ?> placeholder="+12 3456 78901239" >
          </div>
        </div>
        <div class="form-group">
          <label for="name">Recipient Name</label>
          <div>
            <input type="text" class="form-control" <?php getVal("name"); ?> placeholder="Name">
          </div>
        </div>
        <div class="form-group">
          <label for="business">Business Name</label>
          <div>
            <input type="text" class="form-control" <?php getVal("business"); ?> >
          </div>
        </div>
        <div class="form-group">
          <label for="address">Address</label>
          <div>
            <?php getTextArea("address"); ?>
          </div>
        </div>
        <div class="form-group">
          <label for="town">Town</label>
          <div>
            <input type="text" class="form-control" <?php getVal("town"); ?> >
          </div>
        </div>
        <div class="form-group">
          <label for="postcode">Post Code</label>
          <div>
            <input type="text" class="form-control" <?php getVal("postcode"); ?> >
          </div>
        </div>
        <div class="form-group">
          <label for="euvat">EU VAT number</label>
          <div>
            <input type="text" <?php getVal("euvat"); ?>  class="form-control">
          </div>
        </div>
        <div class="form-group">
          <label for="country">Country</label>
          <div style="margin-top: 6px;">
            <?php getCountry(); ?>
          </div>
        </div>
        <div class="form-group">
          <div>
            <button type="submit" class="btn btn-success pull-right">Calculate Postage and VAT</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- // Address -->

  <?php

  echo "
  <div class=\"panel panel-default\">
  <div class=\"panel-heading\" style=\"margin-bottom:0;\">
  <h3 class=\"panel-title\">My Cart</h3>
  <p>Invoice No.: ".$_SESSION['invoice_id']."</p>
  </div>
  <div class=\"table-responsive\">
  <table class=\"table\">
  <tr class=\"tableactive\">
  <td><b>Product</b></td><td align=\"right\"><b>Price</b></td><td align=\"right\"><b>Quantity</b></td><td align=\"right\"><b>Total</b>.</td>
  </tr>
  ";

  $notaxtotal = 0;
  $vat = 0;
  $addVat = false;
  $isInEU = false;
  $shippingcost = 0;
  $isUK = false;
  $hasEUVAT = false;

  $purchase_unit = array(
    'invoice_id' => $_SESSION['invoice_id'],
    'soft_descriptor' => 'Glasgow Neuro LTD',
    'amount' => array(),
    'items' => array(),
  );

  // can only calc tax with country
  if (!empty($_SESSION['country'])) {
    $c = $shipping[$_SESSION['country']];
    $addVat = $c[1];
    $isInEU = $c[2];
    $isUK = strcmp($uk,$_SESSION['country']) == 0;
  }
  if (!empty($_SESSION['euvat'])) {
    $hasEUVAT = strlen($_SESSION['euvat']) > 7;
    $addVat = ($isUK || ($isInEU && (!$hasEUVAT)) );
  }

  // For finding session elements line number
  $linenumber = 0;
  $qtydecimaltotal = 0;
  $items = array();

  // Run loop for cart array
  foreach($_SESSION['SBCScart'] as $SBCSitem)
  {
    // Don't list items with 0 qty
    if($SBCSitem['quantity']!=0) {

      $pricedecimal = (float)$SBCSitem['unitprice'];
      $qtydecimal = (int)$SBCSitem['quantity'];
      $qtydecimaltotal = $qtydecimaltotal + $qtydecimal;

      $prodvat = 0;
      if ($addVat) {
        $prodvat = calcVat($pricedecimal);
      }

      // Write cart to screen
      echo
      "
      <tr class='tablerow'>
      <td>".$SBCSitem['item']."</td>
      <td align=\"right\">£".finnum($SBCSitem['unitprice'])."</td>
      <td align=\"right\">".$SBCSitem['quantity']."</td>
      <td align=\"right\">£".finnum($pricedecimal * $qtydecimal)."</td>
      </tr>
      ";

      $purchase_unit['items'][] = array(
        "name" => $SBCSitem['item'],
        "unit_amount" => array("currency_code" => $currency, "value" => $pricedecimal),
        "tax" => array("currency_code" => $currency, "value" => $prodvat),
        "quantity" => $qtydecimal
      );

      // Total
      $notaxtotal += ($pricedecimal * $qtydecimal);
      $vat += ($prodvat * $qtydecimal);
    }
    $linenumber++;
  }

  if (!empty($_SESSION['country'])) {
    $c = $shipping[$_SESSION['country']];
    $shippingcost = $c[0];
    $_SESSION['shipping'] = $shippingcost;

    // Shipping
    echo
    "
    <tr class='tablerow'>
    <td>Shipping</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align=\"right\">£".finnum($shippingcost)."</td>
    </tr>
    ";
    $shippingvat = 0;
    if ($addVat) {
      $shippingvat = calcVat($shippingcost);
    }

    $vat = $vat + $shippingvat;
    $notaxtotal = $notaxtotal + $shippingcost;
    $purchase_unit['items'][] = array(
      "name" => "Shipping",
      "unit_amount" => array("currency_code" => $currency, "value" => $shippingcost),
      "tax" => array("currency_code" => $currency, "value" => $shippingvat),
      "quantity" => 1
    );
  $_SESSION['shippingcost'] = $shippingcost;
  $_SESSION['shippingvat'] = $shippingvat;
  }
  $notaxtotal = round($notaxtotal,2);
  $_SESSION['notaxtotal'] = $notaxtotal;
  $_SESSION['vat'] = $vat;
  $vat = round($vat,2);
  $_SESSION['vat'] = $vat;
  $total = $notaxtotal + $vat;
  $total = round($total,2);
  $_SESSION['total'] = $total;

  $purchase_unit['amount'] = array(
    "value" => $total,
    "currency_code" => $currency,
    "breakdown" => array(
      "item_total" => array("value" => $notaxtotal,
                            "currency_code" => $currency ),
      "tax_total" => array("value" => $vat,
                           "currency_code" => $currency )
                         )
  );

  echo
  "
  <tr class='tablerow'>
  <td>VAT</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td align=\"right\">£".finnum($vat)."</td>
  </tr>
  ";

echo "
<tr class=\"tableactive\">
<td>&nbsp;</td>
<td class='text-right'>Total</td>
<td align=\"right\">".$qtydecimaltotal."</td>
<td align=\"right\">£".finnum($total)."</td>
</tr>
</table>
</div>
</div>
";

$okconf = (!empty($_SESSION['shipping'])) && (!empty($_SESSION['email'])) &&
(!empty($_SESSION['town'])) && (!empty($_SESSION['postcode'])) &&
(!empty($_SESSION['name'])) && (!empty($_SESSION['address']));

if ($okconf) {
  echo "

  <div id=\"paypal-button-container\"></div>
  <script>paypal.Buttons({
    createOrder: function(data, actions) {
        return actions.order.create({
            purchase_units: [
              ".json_encode($purchase_unit,JSON_PRETTY_PRINT)."
            ],
        });
    },
    onApprove: function(data, actions) {
      // Capture the funds from the transaction
      return actions.order.capture().then(function(details) {
        // Show a success message to your buyer
        alert('Transaction completed by ' + details.payer.name.given_name);
        window.open(\"confirmation.php\",\"_self\");
      });
    },
    onError: function (err) {
      alert(err);
    }
}).render('#paypal-button-container');
  </script>

  <!-- Order -->
  <div class=\"panel panel-default\">
  <div class=\"panel-heading\">
  <h3 class=\"panel-title\">Invoice / Bank transfer</h3>
  </div>
  <div class=\"panel-body\">
  <form role=\"form\" method=\"post\" action=\"confirmation.php\">
  <div class=\"form-group\">
  <label for=\"optionsRadios1\">You can also request an invoice.</label>
  <div style=\"margin-top: 6px;\">
  </div>
  </div>
  <div class=\"form-group\">
  <div>
  <button type=\"submit\" class=\"btn btn-success pull-right\">Request invoice</button>
  <input type=\"hidden\" name=\"invoice\" value=\"1\">
  </div>
  </div>
  </form>
  </div>
  </div>
  ";
}

?>



<hr>

<footer>
  <p>Shopping basket is based on <a href="https://github.com/ganbarli/PHP-SBCS" target="blank">PHP-SBCS</a></p>
</footer>

</div><!--/.container-->

<?php
include("../footer.php");
?>
