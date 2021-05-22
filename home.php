<?php 
include 'admin/db_connect.php'; 
?>
<style>
#portfolio .img-fluid{
    width: calc(100%);
    height: 30vh;
    z-index: -1;
    position: relative;
    padding: 1em;
}
.event-list{
cursor: pointer;
}
span.hightlight{
    background: yellow;
}
.banner{
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 26vh;
        width: calc(30%);
    }
    .banner img{
        width: calc(100%);
        height: calc(100%);
        cursor :pointer;
    }
.event-list{
cursor: pointer;
border: unset;
flex-direction: inherit;
}

.event-list .banner {
    width: calc(40%)
}
.event-list .card-body {
    width: calc(60%)
}
.event-list .banner img {
    border-top-left-radius: 5px;
    border-bottom-left-radius: 5px;
    min-height: 50vh;
}
span.hightlight{
    background: yellow;
}
.banner{
   min-height: calc(100%)
}
</style>
        <header class="masthead">
            <div class="container-fluid h-100">
                <div class="row h-100 align-items-center justify-content-center text-center">
                    <div class="col-lg-8 align-self-end mb-4 page-title">
                    	<h3 class="text-white">Welcome to <?php echo $_SESSION['system']['name']; ?></h3>
                        <hr class="divider my-4" />

                    <div class="col-md-12 mb-2 justify-content-center">
                    </div>                        
                    </div>
                    
                </div>
            </div>
        </header>
            <div class="container mt-3 pt-2">
                <h4 class="text-center text-white">Upcoming Events</h4>
                <hr class="divider">
                <?php
                $event = $conn->query("SELECT * FROM events where date_format(schedule,'%Y-%m%-d') >= '".date('Y-m-d')."' order by unix_timestamp(schedule) asc");
                while($row = $event->fetch_assoc()):
                    $trans = get_html_translation_table(HTML_ENTITIES,ENT_QUOTES);
                    unset($trans["\""], $trans["<"], $trans[">"], $trans["<h2"]);
                    $desc = strtr(html_entity_decode($row['content']),$trans);
                    $desc=str_replace(array("<li>","</li>"), array("",","), $desc);
                ?>
                <div class="card event-list" data-id="<?php echo $row['id'] ?>">
                     <div class='banner'>
                        <?php if(!empty($row['banner'])): ?>
                            <img src="admin/assets/uploads/<?php echo($row['banner']) ?>" alt="">
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <div class="row  align-items-center justify-content-center text-center h-100">
                            <div class="">
                                <h3><b class="filter-txt"><?php echo ucwords($row['title']) ?></b></h3>
                                <div><small><p><b><i class="fa fa-calendar"></i> <?php echo date("F d, Y h:i A",strtotime($row['schedule'])) ?></b></p></small></div>
                                <hr>
                                <larger class="truncate filter-txt"><?php echo strip_tags($desc) ?></larger>
                                <br>
                                <hr class="divider"  style="max-width: calc(80%)">
                                <button class="btn btn-primary float-right read_more" data-id="<?php echo $row['id'] ?>">Read More</button>
                            </div>
                        </div>
                        

                    </div>
                </div>
                <br>
                <?php endwhile; ?>
                
            </div>

<!-- PHP Start -->
<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';


if(isset($_POST["email"])) 
    {   
        $emailTo = 'contact@nsuchannel.fahimuddin.dev';
        $emailFrom = $_POST["email"];
        $message = $_POST["message"];
        $name = $_POST["name"];
        $subject = $_POST["subject"];


        // Instantiation and passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->isSMTP();                                            // Send using SMTP
            $mail->Host       = 'nsuchannel.fahimuddin.dev';                    // Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = 'sendingserver@nsuchannel.fahimuddin.dev';                     // SMTP username
            $mail->Password   = 'nsuchannel';                               // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $mail->Port       = 465;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

            //Recipients
            $mail->setFrom("$emailFrom", "$name");
            $mail->addAddress("$emailTo");     // Add a recipient
            $mail->addReplyTo("$emailFrom", "$name");

            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = "$subject";
            $mail->Body    = " <h1>Message Body</h1>
                                <p>$message</P>";
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
            $_SESSION['success'] = "Message Sent";
            // header('location: index.php');
        } catch (Exception $e) {
          $_SESSION['status'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        //   header('location: index.php');
        } 
        exit();   
    }
?>

<!-- PHP End -->
             <!-- Testing Code Start -->
             <div class="container mt-3 pt-2">
                <h4 class="text-center text-white">Contact Us</h4>
                <hr class="divider">
                <?php 
                if(isset($_SESSION['success']) && $_SESSION['success'] != '')
                {
                    echo '<h4 class="bg-success text-white p-1"> '.$_SESSION['success'].'</h4>';
                    unset($_SESSION['success']);
                }
                if(isset($_SESSION['status']) && $_SESSION['status'] != '')
                {
                    echo '<h4 class="bg-danger text-white p-1">'.$_SESSION['status'].'</h4>';
                    unset($_SESSION['status']);
                }
                ?>

            <form method="POST">
                <div class="form-group my-4">
                    <label for="formGroupExampleInput" class="mb-2" style="color : #ffffff;">Your Name</label>
                    <input type="text" name="name" class="form-control" id="formGroupExampleInput" placeholder="Enter Name" required>
                </div>
                <div class="form-group my-4">
                    <label for="exampleInputEmail1" class="mb-2" style="color : #ffffff;">Email address</label>
                    <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email" required>
                    <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                </div>
                <div class="form-group my-4">
                    <label for="formGroupExampleInput" class="mb-2" style="color : #ffffff;">Subject</label>
                    <input type="text" name="subject" class="form-control" id="formGroupExampleInput" placeholder="Enter Subject" required>
                </div>
                <div class="form-group my-4">
                    <label for="exampleFormControlTextarea1" class="mb-2" style="color : #ffffff;">Your Message</label>
                    <textarea class="form-control" name="message" id="exampleFormControlTextarea1" rows="4"></textarea>
                </div>
                <button type="submit" class="btn btn-primary" style="margin-bottom: 30px;">Submit</button>
            </form>
                
       
            </div>  
                
          
            <!-- Testing Code End -->

            



<script>
     $('.read_more').click(function(){
         location.href = "index.php?page=view_event&id="+$(this).attr('data-id')
     })
     $('.banner img').click(function(){
        viewer_modal($(this).attr('src'))
    })
    $('#filter').keyup(function(e){
        var filter = $(this).val()

        $('.card.event-list .filter-txt').each(function(){
            var txto = $(this).html();
            txt = txto
            if((txt.toLowerCase()).includes((filter.toLowerCase())) == true){
                $(this).closest('.card').toggle(true)
            }else{
                $(this).closest('.card').toggle(false)
               
            }
        })
    })
</script>