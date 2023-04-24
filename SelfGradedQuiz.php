<?php

session_start();
// array with questions, answers, and correct answers
$_SESSION['Questions'] = array(
  1 => array('Question' => '"URL" stands for "Universal Reference Link".',
      'Answers' => array('A' => 'True', 'B' => 'False'),
      'CorrectAnswer' => 'B'),
  2 => array('Question' => 'An Apple MacBook is an example of a Linux system.',
      'Answers' => array('A' => 'True','B' => 'False'),
      'CorrectAnswer' => 'A'),
  3 => array('Question' => 'Which of these do NOT contribute to packet delay in a packet switching network?',
      'Answers' => array(
          'A' => 'Processing delay at a router','B' => 'CPU workload on a client','C' => 'Transmission delay along a communications link','D' => 'Propagation delay'),
      'CorrectAnswer' => 'B'),
  4 => array('Question' => 'This Internet layer is responsible for creating the packets that move across the network.',
      'Answers' => array('A' => 'Physical','B' => 'Data Link', 'C' => 'Network','D' => 'Transport'),
      'CorrectAnswer' => 'C'),
  5 => array('Question' => '_______ is a networking protocol that runs over TCP/IP, and governs communication between web browsers and web servers.',
      'Answers' => array('A' => ''),
      'CorrectAnswer' => 'HTTP'),
  6 => array('Question' => ' A small icon displayed in a browser table that identifies a website is called a ________.',
      'Answers' => array(
          'A' => ''),
      'CorrectAnswer' => 'FAVICON'));

// if the cookie is set and user account has been created, display the quiz
if (isset($_COOKIE['user'])) {
  display_quiz();
// check what buttont the user clicks; submit account registration, submit log in, or display login/registration page.
} else {
    // If they have clicked the button to register their account
    if (isset($_POST['RegisterAccount'])) {
      $user_exists = false;
      //if the username/password fields are not empty
      if ((empty($_POST['username']) == false) && (empty($_POST['password']) == false)) {
        $_SESSION['username'] = $_POST['username'];
        //loop through file to search for potentially existing usernames
        $file = fopen("passwd.txt", "r") or die("Unable to open file!");
        while (!feof($file)){  
          $data = fgets($file); 
          $username = '/^(.*?)\:/';
          $password = '/\:(.*)/';
          preg_match($username, $data, $userid);
          preg_match($password, $data, $pass);
          // if username exists, go back to registration form
          if (empty($_POST['username']) == false && $_POST['username'] == $userid[1]){
            $user_exists = true;
            echo '<h3>This username already exists. Please log in instead.</h3>';
            registration_form();
            die;
          }
        }
        fclose($file);
        //if username does not exist
        // if username has a colon
        if ($user_exists == false && (empty($_POST['username']) == false)) {
          if (strpos($_SESSION['username'], ':') != false) {
            echo "<h3>Username may not contain the special character colon: \" : \".</h3>";
            registration_form();
          // if username is valid, set the cookie, reload the page, and verification of existing cookie will display the quiz
          }else {
            $file = fopen("passwd.txt", "a") or die("Unable to open file!");
          $entry = $_POST['username'].':'.$_POST['password']."\n";
          fwrite($file, $entry);
          fclose($file);
          $_SESSION['username'] = $_POST['username'];
          setcookie("user", "Mark", time() +900, "/");
          echo "New user account created. You are now logged in.";
          header("Location: SelfGradedQuiz.php");
          }
        }
      // if either of the fields are empty
      } else {
        echo "<h3>Username and Password fields can not be empty!</h3>";
        registration_form();
      }
  // if they have clicked button to log in to account
  } else if (isset($_POST['LoginAccount'])) {
    $user_exists = false;
    if ((empty($_POST['username']) == false) && (empty($_POST['password']) == false)) {
      //check account for potentially existing username
      $_SESSION['username'] = $_POST['username'];
      $file = fopen("passwd.txt", "r") or die("Unable to open file!");
      while (!feof($file)){  
        $data = fgets($file); 
        $username = '/^(.*?)\:/';
        $password = '/\:(.*)/';
        preg_match($username, $data, $userid);
        preg_match($password, $data, $pass);
        //if they log in but their info already exists, it means they have already taken the quiz.
        if (empty($_POST['username']) == false && $_POST['username'] == $userid[1] && $_POST['password'] == $pass[1]){
          $user_exists = true;
          echo '<h3>You have already taken the quiz. You may not take it again.</h3>';
          login();
          die;
        // username exists but the username and password do not match
        } else if (empty($_POST['username']) == false && $_POST['username'] == $userid[1] && $_POST['password'] != $pass[1]){
          $user_exists = true;
          echo '<h3>Incorrect password entered.</h3>';
          login();
        }
      }
      fclose($file);
      //username does not exist in file name, they cannot log in
      if ($user_exists == false && (empty($_POST['username']) == false)) {
        echo "<h3>This account does not exist. Please enter the correct information or register instead.</h3>";
        login();
      }
    //username and/or password fields empty
    } else {
      echo "<h3>Username and Password fields can not be empty!</h3>";
      login();
    }

    //go to log in page
  } else if (isset($_POST['LoginPage'])) {
    login();
    //go to registration page
  }else { 
    registration_form();
    } 
}
function registration_form() {
    ?>
    <html>
  <head>
      <title>Registration Page</title>
      <meta charset="UTF-8">
      <meta name="description" content="Login Page">
      <meta name="author" content="Alexa Reza">
      <link href="quiz.css" rel="stylesheet">
      <link href='https://fonts.googleapis.com/css?family=Didact Gothic' rel='stylesheet'>
    </head>
  <body>
    <h2>Web History Quiz Registration Page</h2>
    <h3>You must register a new account to take this quiz. If you already have an account, please hit the log in button below.
      <br>You may only take the quiz once, and you will have 15 minutes to do so before the session terminates. Your score will be saved.
      <br></h3>
    <form action="SelfGradedQuiz.php" method="POST">
    <label for="username">Username</label>
    <input type="text" name="username" id="username"><br>
    <label for="password">Password</label>
    <input type="password" name="password" id="password"><br><br>
    <input type="submit" class="button" name="RegisterAccount" value="Register"><br><br>
    Have you already registered? <input type="submit" class="button" name="LoginPage" value="Log in">
  </body>
    </form>	</html><?php
}
function login() {
  ?>
    <html>
  <head>
      <title>Login Page</title>
      <meta charset="UTF-8">
      <meta name="description" content="Login Page">
      <meta name="author" content="Alexa Reza">
      <link href="quiz.css" rel="stylesheet">
      <link href='https://fonts.googleapis.com/css?family=Didact Gothic' rel='stylesheet'>
    </head>
  <body>
    <h2>Web History Quiz Login Page</h2>
    <h3>The user is not currently logged in! You must be logged in to take this quiz. If you do not have an account, please hit the register button below.
      <br>You may only take the quiz once, and you will have 15 minutes to do so before the session terminates. Your score will be saved.</h3>
    <form action="SelfGradedQuiz.php" method="POST">
    <label for="username">Username</label>
    <input type="text" name="username" id="username"><br>
    <label for="password">Password</label>
    <input type="password" name="password" id="password"><br><br>
    <input type="submit" class="button" name="LoginAccount" value="Login"><br><br>
    Don't have an account? <input type="submit" class="button" name="Register" value="Register">
  </body>
    </form>	</html><?php

}
function display_quiz() {
  if (!isset($_SESSION["number"])){
    $_SESSION["number"] = 0;
    $_SESSION["answer"] = "";
    $_SESSION["correct"] = 0;
  }
  $total_number = 6;
 ?>
  <html>
  <head>
  <title>Web History Quiz</title>
  <link href="quiz.css" rel="stylesheet">
  <link href='https://fonts.googleapis.com/css?family=Didact Gothic' rel='stylesheet'>
  </head>
  <body>
  <h3> Web History Quiz </h3>
  <?php
  
  $number = $_SESSION["number"];
  $answer = $_SESSION["answer"];
  $correct = $_SESSION["correct"];
  if (!isset($_SESSION['username'])) {
    registration_form();
  //if they are on the first question, append a new entry with their username to the results file with a score of zero.
  } else if ($number == 0){
    $number=1;
    $_SESSION["number"] = $number;
    $file = fopen("results.txt", "a") or die("Unable to open file!");
        $entry = $_SESSION['username'].":0";
        fwrite($file, $entry."\n");
      fclose($file);
    ?> <html>
    <p> You will be given <?php echo $total_number; ?> questions in this quiz, and each question is worth 10 points. <br /><br/>
        Here is your first question: <br/>
    </p></html>
  <?php
  //if they submit their first question
  } else if ($number >= 1){
    //if their answer was the correct answer, update their score and update question number so they can move on to the next question
    if (strtoupper($_POST["answer"]) == $answer) {
      $correct++;
      $number++;
      $_SESSION["number"] = $number;
      $_SESSION["correct"] = $correct;
      $file = fopen("results.txt", "r") or die("Unable to open file!");
      // Finds data associated with username in results file and updates it with new score
      while (!feof($file)){  
        $data = fgets($file); 
        $username = '/^(.*?)\:/';
        preg_match($username, $data, $userid);
        if ($_SESSION['username'] == $userid[1]) {
          $newstr = $_SESSION['username'].':'.($correct*10)."\n";
          $contents = file_get_contents('results.txt');
          $contents = str_replace($data, $newstr, $contents);
          file_put_contents('results.txt', $contents);
        }}
      fclose($file);
    //if text fields are left blank for last two questions, say that they must fill in the field
    } else if (!isset($_POST["answer"]) or ($number == 5 && $_POST["answer"] == "") or ($number == 6 && $_POST["answer"] == "")) {
      print <<<INCORRECT
      You may not leave any answers blank! Please choose an answer to submit.<br /><br />
    INCORRECT;
    //if they do not get the correct answer, simply move on to the next question by updating the number
    } else if ($_POST["answer"] != $answer) {
      $number++;
      $_SESSION["number"] = $number;
    }
  }
  // if they are finished taking the quiz, calculate/display their final score and destroy the cookie so that when they reload the page, they will be taken back to the registration page
  if ($number > $total_number){
    $correct *= 10;
    $total_points = $total_number *= 10;
    setcookie("user", "Mark", time() -120, "/");
    print <<<FINAL_SCORE
    Your final score is: $correct correct out of $total_points points. <br /><br />
    Thank you for playing. You have now been logged out. <br /><br />
  FINAL_SCORE;
    session_destroy();
  //if they are not finished taking the quiz, grab new question contents/subarray from the larger array 
  } else if (isset($_SESSION['username'])) {
    foreach ($_SESSION['Questions'] as $QuestionNum => $QString){
      if ($_SESSION['number'] == $QuestionNum) {
        $question = $QString;
        $numQuestion = $QuestionNum;
      }
    }
    $script = $_SERVER['PHP_SELF'];
    $_SESSION["answer"] = $question['CorrectAnswer'];

    //display new question by looping through subarray for that question number
    print <<<FORM
    <form method = "POST" action = $script>
    FORM;
      
    echo $question['Question'].'<br>';
    //change the type of input based on the question number
    foreach ($question['Answers'] as $Letter => $Answer) { 
      if ($numQuestion == 1 or $numQuestion == 2) {
        $inputType = "radio";
      } else if ($numQuestion == 3 or $numQuestion == 4) {
        $inputType = "checkbox";
      } else {
        $inputType = "text";
        $Letter = "";
      }?>
      <input type="<?php echo $inputType; ?>" name="answer" value="<?php echo $Letter; ?>">
      <?php echo $Answer; ?><br><?php
    }?>
    <br><input type = "submit" value = "Submit" class="button"/>
    </form>
  <?php
  }
  print <<<BOTTOM
  </body>
  </html>
  BOTTOM;
}
?>
