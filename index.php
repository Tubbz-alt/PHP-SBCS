<?php
// Report All PHP Errors
//error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$title = "Attys - shop";

include("../header.php");

$products = array(
	array("Attys DAQ",299.00,"Attys data acquistion box with mini USB charger cable","attys.jpg"),
        array("Bluetooth USB dongle",24.99,"ASUS BT400 USB bluetooth dongle recommended for Windows and Linux","asus-usb-bt400.jpg"),
	array("Cable ties",4.99,"These cable ties fit through the mounting holes of the Attys so that it can be strapped to a belt, for example.","cable_ties.jpg"),
	array("Test lead black",4.99,"Standard 4mm lab test lead - black","banana_black.jpg"),
	array("Test lead red",4.99,"Standard 4mm lab test lead - red","banana_red.jpg"),
	array("Test lead green",4.99,"Standard 4mm lab test lead - green","banana_green.jpg"),
	array("ECG electrodes",4.99,"Pack of 50 disposable ECG electrodes","ecg_electrode.jpg"),
	array("Electrode clip",4.99,"4mm Banana plug to snap clip adapter for ECG electrodes.","electrode_clip.jpg")
);



	function getSess($name) {
		if($_SESSION[$name]!="") {
			return $_SESSION[$name];
		}
		return "";
	}

  if (empty($_SESSION['invoice_id'])) {
    $_SESSION['invoice_id'] = rand() % 999999;
  }

	function setSess($name,$val) {
		$_SESSION[$name] = $val;
	}

	// Add item to cart
	if (empty($_POST['item']) || empty($_POST['price']) || empty($_POST['quantity']))
	{ } else {

		# Get values
		$SBCSprice = $_POST['price'];
		$SBCSitem = $_POST['item'];
		$SBCSquantity = $_POST['quantity'];
		$SBCSuniquid = rand();
		$SBCSexist = false;
		$SBCScount = 0;
		// If SESSION Generated?
		if($_SESSION['SBCScart']!="")
		{
			// Look for item
			foreach($_SESSION['SBCScart'] as $SBCSproduct)
			{
				// Yes we found it
				if($SBCSitem == $SBCSproduct['item']) {
					$SBCSexist = true;
					break;
				}
				$SBCScount++;
			}
		}
		// If we found same item
		if($SBCSexist)
		{
			// Update quantity
			$_SESSION['SBCScart'][$SBCScount]['quantity'] += $SBCSquantity;
			// Write down the message and then we open in modal at the bottom
			$msg = "
			<script type=\"text/javascript\">
				$(document).ready(function(){
					$('#myDialogText').text('".$SBCSitem." quantity updated..');
					$('#modal-cart').modal('show');
				});
			</script>
			";

		} else {

			// If not found, insert new
			$SBCSmycartrow = array(
				'item' => $SBCSitem,
				'unitprice' => $SBCSprice,
				'quantity' => $SBCSquantity,
				'id' => $SBCSuniquid
			);

			// If session not exist, create
			if (!isset($_SESSION['SBCScart']))
				$_SESSION['SBCScart'] = array();

			// Add item to cart
			$_SESSION['SBCScart'][] = $SBCSmycartrow;

			// Write down the message and then we open in modal at the bottom
			$msg = "
			<script type=\"text/javascript\">
				$(document).ready(function(){
					$('#myDialogText').text('".$SBCSitem." added to your cart');
					$('#modal-cart').modal('show');
				});
			</script>
			";

		}
	}

	// Clear cart
	if(isset($_GET["clear"]))
	{
		session_unset();
		session_destroy();
		// Write down the message and then we open in modal at the bottom
		$msg = "
		<script type=\"text/javascript\">
			$(document).ready(function(){
				$('#myDialogText').text('Your cart is empty now..');
				$('#modal-cart').modal('show');
			});
		</script>
		";
	}

	// Remove item from cart (Updating quantity to 0)
	$remove = isset($_GET['remove']) ? $_GET['remove'] : '';
	if($remove!="")
	{
		$_SESSION['SBCScart'][$_GET["remove"]]['quantity'] = 0;
	}
	?>

    <div class="container">
      <h1>Attys Shop</h1>
      <div class="row">
        <div class="col-sm-7">
		<div class="row">
                            <?php
			    foreach($products as $prod) {
				echo "<div class=\"col-lg-4 d-flex align-items-stretch\"><div class=\"card mb-3\">
					<img class=\"card-img-top img-fluid\" src=\"".$prod[3]."\" class=\"img-responsive\" alt=\"".$prod[0]."\">
						<div class=\"card-body d-flex flex-column\">
							<h5 class=\"card-title\">".$prod[0]."</h5>
                                                        <p class=\"card-text\">".$prod[2]."</p>
							<div class=\"mt-auto\">
							<span class=\"align-bottom\">£".$prod[1]."</span>
						<form action=\"?\" method=\"post\">
							<div class = \"input-group\">
							<input class=\"form-control\" name=\"quantity\" type=\"text\" maxlength=\"2\" value=\"1\">
							<span class = \"input-group-btn\"><input type=\"submit\" class=\"btn btn-success\" type=\"button\" value=\"Add to basket\" style=\"font-size : 9pt;\"></span>
							</div>
							<input type=\"hidden\" name=\"item\" value=\"".$prod[0]."\" />
							<input type=\"hidden\" name=\"price\" value=\"".$prod[1]."\" />
						</form>
						</div>
						</div>
				</div></div>";
			    }
			    ?>
		</div>
        </div><!--/span-->

        <div class="col-sm-5" id="sidebar" role="navigation">
          <div class="sidebar-nav">
			<?php
			// If cart is empty
			if (!isset($_SESSION['SBCScart']) || (count($_SESSION['SBCScart']) == 0)) {
			?>
				<div class="panel panel-default">
				  <div class="panel-heading">
					<h3 class="panel-title"><span class="glyphicon glyphicon-shopping-cart"></span> My Cart</h3>
				  </div>
				  <div class="panel-body">Your cart is empty..</div>
				</div>
			<?php
			// If cart is not empty
			} else {
			?>
				<div class="panel panel-default">
					<div class="panel-heading" style="margin-bottom:0;">
						<h3 class="panel-title"><span class="glyphicon glyphicon-shopping-cart"></span> My Cart</h3>
					</div>
					<div class="table-responsive">
					<table class="table">
						<tr class="tableactive"><th>Product</th><th>Price</th><th>Qty.</th><th>Tot.</th></tr>
						<?php
						// List cart items
						// We store order detail in HTML
						$OrderDetail = '
						<table border=1 cellpadding=5 cellspacing=5>
							<thead>
								<tr>
									<th>Product</th>
									<th>Price</th>
									<th>Quantity</th>
									<th>Total</th>
								</tr>
							</thead>
							<tbody>';

						// Equal total to 0
						$total = 0;

						// For finding session elements line number
						$linenumber = 0;
            $qtydecimaltotal = 0;

						// Run loop for cart array
						foreach($_SESSION['SBCScart'] as $SBCSitem)
						{
							// Don't list items with 0 qty
							if($SBCSitem['quantity']!=0) {

							$pricedecimal = (float)$SBCSitem['unitprice'];
							$qtydecimal = (int)$SBCSitem['quantity'];
							$qtydecimaltotal = $qtydecimaltotal + $qtydecimal;

							$totaldecimal = $pricedecimal*$qtydecimal;

							// We store order detail in HTML
							$OrderDetail .= "<tr><td>".$SBCSitem['item']."</td><td>£".$SBCSitem['unitprice']."</td><td>".$SBCSitem['quantity']."</td><td>".$totaldecimal."</td></tr>";

							// Write cart to screen
							echo
							"
							<tr class='tablerow'>
								<td><a href=\"?remove=".$linenumber."\"  style=\"font-size : 10pt;\" class=\"btn btn-danger\" onclick=\"return confirm('Are you sure?')\">X</a> ".$SBCSitem['item']."</td>
								<td>£".$SBCSitem['unitprice']."</td>
								<td>".$SBCSitem['quantity']."</td>
								<td>£".$totaldecimal."</td>
							</tr>
							";

							// Total
							$total += $totaldecimal;

							}
							$linenumber++;
						}

						// We store order detail in HTML
						$OrderDetail .= "<tr><td>Total</td><td></td><td></td><td>£".$total."</td></tr></tbody></table>";

						?>
						<tr class='tableactive'>
							<td><a href='?clear' class='btn btn-danger btn-xs' onclick="return confirm('Are you sure?')">Empty Cart</a></td>
							<td class='text-right'>Total</td>
							<td><?php echo $qtydecimaltotal;?></td>
							<td>£<?php echo $total;?></td>
						</tr>
					</table>
					</div>
				</div>
				<!-- // Cart -->

				<!-- Checkout -->
				<div class="panel panel-default">
				  <div class="panel-body">
					<form role="form" method="post" action="checkout.php">
					  <div class="form-group">
						<div>
						  <button type="submit" class="btn btn-success pull-right">Checkout</button>
						</div>
					  </div>
					<input type="hidden" name="total" value="<?php echo $total;?>">
					<input type="hidden" name="OrderDetail" value="<?php echo htmlentities($OrderDetail);?>">
					</form>
				  </div>
				</div>
				<!-- // Address -->

			<?php } # End Cart Listing ?>
          </div><!--/.well -->
        </div><!--/span-->
      </div><!--/row-->

      <hr>

      <footer>
        <p>Shopping basket is based on <a href="https://github.com/ganbarli/PHP-SBCS" target="blank">PHP-SBCS</a></p>
      </footer>

    </div><!--/.container-->

<?php
include("../footer.php");
?>
