  <?php
  // Report All PHP Errors
  //error_reporting(E_ALL);

  if (session_status() == PHP_SESSION_NONE) {
    session_start();
  }

  $title = "Attys - Confirmation";

  $footer = "<br />";
  if (isset($_POST['invoice'])) {
    $header = "<h2>Invoice</h2>\n";
    $footer = $footer."<h4>Payment Method</h4>
    <p>
xxxxxxxxxxxxxxxxxxxxxxxxxxxxx
</p>
    ";
  } else {
  $header = "<h2>Order confirmation</h2>\n";
  }
  $footer = $footer."<hr /><br />Glasgow Neuro Ltd<br />
xxxxxxxxxxxxxxxxxxxxxxxxxxxxx
";

  include("../header.php");

  function getVal($name) {
    if(isset($_SESSION[$name])) {
      return "<p>".$_SESSION[$name]."<p>\n";
    }
    return "\n";
  }

  echo "<div class=\"container\">";

    $order = $header."
    <p>Invoice No.: ".$_SESSION['invoice_id']."</p>";
    $order = $order.getVal("email");
    $order = $order.getVal("phone");
    $order = $order.getVal("name");
    $order = $order.getVal("business");
    $order = $order.getVal("address");
    $order = $order.getVal("town");
    $order = $order.getVal("postcode");
    $order = $order.getVal("country");
    $order = $order.getVal("euvat");

    $order = $order."
    <br /><br />
    <table cellpadding=\"10px\">
    <tr>
    <td><b>Product</b></td><td align=\"right\"><b>Price</b></td><td align=\"right\"><b>Quantity</b></td><td align=\"right\"><b>Total</b>.</td>
    </tr>
    ";

    $qtydecimaltotal = 0;
    // Run loop for cart array
    foreach($_SESSION['SBCScart'] as $SBCSitem)
    {
      // Don't list items with 0 qty
      if($SBCSitem['quantity']!=0) {

        $pricedecimal = (float)$SBCSitem['unitprice'];
        $qtydecimal = (int)$SBCSitem['quantity'];
        $qtydecimaltotal = $qtydecimaltotal + $qtydecimal;

        // Write cart to screen
        $order=$order.
        "
        <tr>
        <td>".$SBCSitem['item']."</td>
        <td align=\"right\">£".finnum($SBCSitem['unitprice'])."</td>
        <td align=\"right\">".$SBCSitem['quantity']."</td>
        <td align=\"right\">£".finnum($pricedecimal * $qtydecimal)."</td>
        </tr>
        ";
      }
    }

      // Shipping
      $order = $order.
      "
      <tr>
      <td>Shipping</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td align=\"right\">£".$_SESSION['shippingcost']."</td>
      </tr>

    <tr>
    <td>VAT</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align=\"right\">£".$_SESSION['vat']."</td>
    </tr>

  <tr>
  <td>&nbsp;</td>
  <td>Total</td>
  <td align=\"right\"></td>
  <td align=\"right\">£".$_SESSION['total']."</td>
  </tr>
  </table>

  <br /><br />
  <p>Thank you for your order.</p>
  ";

  echo $order;

  echo $footer;

  $headers = "MIME-Version: 1.0" . "\r\n";
  $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
  $headers .= "From: sales@xxxx.tech" . "\r\n";
  $headers .= 'Cc: xyz@xxx.tech';
  $message = "<html><body>".$order.$footer."</body></html>";
  mail($_SESSION['email'],
    "Attys order ".$_SESSION['invoice_id'],
    $message,$headers);
  ?>

  <br /><br />
  <p>Please print this page. We have sent you also a confirmation e-mail.</p>
  <p>&nbsp</p>

  </div><!--/.container-->

  <?php
  include("../footer.php");
  ?>
