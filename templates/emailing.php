<?php
/**
 * @package  Miraget Generator
 */

class  MiragetEmailingTemplate{

}
?>
<?php
    
    if( isset($_POST['user_mail']) && isset($_POST['subject']) && isset($_POST['message'])){
       
         $user_email = sanitize_text_field($_POST['user_mail']);
         $user_message = sanitize_text_field($_POST['message']);
         $subject = sanitize_text_field($_POST['subject']);
         $to      = 'contact@miraget.com';
        
        $headers = array(
          'From' => $user_email,
          'Reply-To' => $user_email,
          'X-Mailer' => 'PHP/' . phpversion()
      );
        $sent=wp_mail($to, $subject, $user_message, $headers);
        if($sent) echo '<h2 style="color:green;">Thank you for your FeedBack <h2>';
        else echo '<h4>failed to send</h4>';
       
       
    }
?>


 <div class="wrap">
 <div class="connector-link-contact">
    Questions? Need Help? <br><br><a href="https://miraget.com/contact-2/" target="_blank"> Contact Us </a>
 </div>
 <br>
 <hr>
  <h3> For custom integrations or to report a bug please contact us below : </h3>
  <div class="email-container">
    <form method="post" action="">
      <div class="row">
        <div class="col-25">
          <label for="fname"> Email</label>
        </div>
        <div class="col-75">
          <input type="text" id="fname" name="user_mail" placeholder="Your Email..">
        </div>
      </div>
      <div class="row">
        <div class="col-25">
          <label for="lname">Subject</label>
        </div>
        <div class="col-75">
          <input type="text" id="lname" name="subject" placeholder="Subject..">
        </div>
      </div>
      
      <div class="row">
        <div class="col-25">
          <label for="subject">Message</label>
        </div>
        <div class="col-75">
          <textarea id="subject" name="message" placeholder="Your Message here..." style="height:200px"></textarea>
        </div>
      </div>
      
      <div class="row">
        <input type="submit" value="Send Email">
      </div>
    </form>
  </div>
 </div> 
