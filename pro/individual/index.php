<?php
if (!isset($file_access)) die("Direct File Access Denied");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>

  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  

  
</head>
<body>
  

<div class="content">
    <div class="container-fluid">
        <?php
        if (!isset($_POST['submit'])) {
        ?>
        <div class="row">
            <div class="col-lg-12">
            <h5 class="m-0">Search For Tourist Sites Here</h5>
                <script async src="https://cse.google.com/cse.js?cx=a26567b5daa134b07"></script>
                <div class="gcse-search"></div>
                <div class="card">
                    <div class="card-header alert-success">
                        <h5 class="m-0">Quick Tips</h5>
                    </div>
                    <div class="card-body">
                        Use the links at the left.
                        <br />You can see list of schedules by clicking on "New Booking". The system will display list
                        of available schedules for you which you can view and make bookings from. <br>Before your
                        bookings are saved, you are redirected to make payment. <br>After a successful payment, system
                        generates your ticket ID for you which you are required to bring to the station. <br>You are
                        allowed to view all your booking history by clicking on "View Bookings".
                    </div>
                </div>
            </div><?php
                    } else {
                        $class = $_POST['class'];
                        $number = $_POST['number'];
                        $schedule_id = $_POST['id'];
                        if ($number < 1) die("Invalid Number");
                        ?>

            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-header alert-success">
                            <h5 class="m-0">Booking Preview</h5>
                        </div>
                        <div class="card-body">
                            <div class="callout callout-info">
                                <h5><i class="fas fa-info"></i> <?php echo ucwords($class), " Class" ?>:</h5>
                                You are about to book
                                <?php echo $number, " Ticket", $number > 1 ? 's' : '', ' for ', getRouteFromSchedule($schedule_id); ?>
                                <br />

                                <?php

                                    $fee = ($_SESSION['amount'] = getFee($schedule_id, $class));
                                    echo $number, " x GH₵ ", $fee, " = GH₵ ", ($fee * $number), "<hr/>";
                                    $fee = $fee * $number;
                                    $amount = intval($fee);
                                    $vat = ceil($fee * 0.01);
                                    echo "V.A.T Charges = GH₵ $vat<br/><br/><hr/>";
                                    echo "Total = GH₵  ", $total = $amount + $vat;
                                    $fee =  intval($total) . "00";
                                    $_SESSION['amount'] =  $total;
                                    $_SESSION['original'] =  $fee;
                                    $_SESSION['schedule'] =  $schedule_id;
                                    $_SESSION['no'] =  $number;
                                    $_SESSION['class'] =  $class;
                                    ?>
                            </div>
                            
<button class="btn btn-primary" type="button" data-toggle="modal" data-target="#exampleModalLong" name="payment" style="background-color: #28a745; border:none;">Continue to billing</button>
                        </div>
                    </div>
                </div>
                <?php
                    }
                ?>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="width: 80%;">
      <div class="modal-header">
        <h3 class="modal-title" id="exampleModalLongTitle">Contact Details</h3>
        <button  type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
                <form method="post" form id="paymentForm" enctype="multipart/form-data" >
                  <div class="form" >
                    <div class="text">
                        <label for="name">Email Account</label>
                        <br>
                        <input type="text" class="text-field w-input" maxlength="256" name="name" data-name="Name" value="<?php echo  $email   ?>" placeholder="" id="email-address" readonly="readonly" 
                        style="border: 1px solid #cccccc; width: 70%; height: 38px; padding: 8px 12px; font-size: 14px; border-radius: 10px; background-color:#eeeeee;">
                       <br><br>
                        <label for="email">Total Amount</label>
                        <br>
                        <input type="text" class="text-field-2 w-input" id="amount"  maxlength="256" name="amount" data-name="amount" placeholder="" value=" <?php echo  $_SESSION["amount"]   ?>.00" readonly="readonly" required=""
                        style="border: 1px solid #cccccc; width: 70%; height: 38px; padding: 8px 12px; font-size: 14px; border-radius: 10px; background-color:#eeeeee;">


                    </div>
                
                    

                  </div>
                </form>
      </div>
      <div class="modal-footer" >
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" onclick="payWithPaystack()" data-wait="Please wait..." class="btn btn-primary"> Continue to billing </button>
        
       </button> 
      </div>
    </div>
  </div>
</div>

	<!-- PAYSTACK INLINE SCRIPT -->
    <script src="https://js.paystack.co/v1/inline.js"></script> 

    <script>
      const paymentForm = document.getElementById('paymentForm');
      paymentForm.addEventListener("submit", payWithPaystack, false);

      // PAYMENT FUNCTION
      function payWithPaystack(e) {
        // e.preventDefault();
        let handler = PaystackPop.setup({
		    key: 'pk_test_a581cbd28de1bc22a3b605acb112f9520cc89675',
          email: document.getElementById("email-address").value,
          amount: document.getElementById("amount").value * 100,
          currency:'GHS',
          onClose: function(){
          alert('Window closed.');
          },
          callback: function(response){
            window.location = `verify.php?email=${document.getElementById("email-address").value}&amount=${document.getElementById("amount").value}&reference=${response.reference}`
          }
        });
        handler.openIframe();
      }

    </script>


</body>
</html>
