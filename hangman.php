<?php
    session_start();

    if(!isset($_SESSION["cur_word"])){
        //ini words dict
        $words_list= array("javascript",
                            "microwave",
                            "apple",
                            "github",
                            "kathmandu",
                            "sundarpichai",
                            "stevejobs",
                            "bemidji",
                            "ukraine",
                            "california"

        );
        $words_hints= array("Client Side Web Programming Language.",
                            "Machine used to heat up.",
                            "a fruit or technology brand name.",
                            "A popular version control software/system.",
                            "Capital city of Nepal.",
                            "Current CEO of Google.",
                            "Founder of Apple.",
                            "Where is BSU located?",
                            "What is the largest country located entirely in Europe?",
                            "Death Valley is located in what U.S. state?"
        );
        //generate rand val
        $ranVal=rand(0,9);
        //pick word
        $cur_word= $words_list[$ranVal];
        //store in session
        $_SESSION["cur_word"]=$cur_word;
        $_SESSION["cur_hint"]=$words_hints[$ranVal];
    }

    if( isset( $_POST["status"] ) && $_POST["status"] == "gameover" ) {
      session_unset();
      //header('Location: hangman.php');
      //echo "new game";
  }
  
  
?>

<!DOCTYPE html>
<html>

<head>
  <title>Aatish Balla: Hangman</title>
  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u"
    crossorigin="anonymous">
  <link href="style.css" rel="stylesheet">
  <script>
    function wordBlender(wrd1, wrd2) {

      var pos, final;
      var ary = new Array();

      len2 = wrd2.length;

      len1 = wrd1.length;
      ary.length = len1;

      for (i = 0; i < len1; i++) {
        ary[i] = "*";
      }

      for (i = 0; i < len2; i++) {
        char = wrd2.charAt(i);
        pos = wrd1.indexOf(char);

        while (pos != -1) {
          ary[pos] = char;
          pos = wrd1.indexOf(char, pos + 1);
        }
      }

      for (i = 0; i < len1; i++) {
        document.getElementById("answer").innerHTML = ary.join("");
      }

    }

    function btnClik_onclick() {

      var guess = document.getElementById("gus").value.toLowerCase();
      var nuAddr, right, wrong;
      var address = window.location.href;
      var rite = getVar(address, "right");
      var rong = getVar(address, "wrong");
      //total guessed letters
      var guessed = rite.concat(rong);

      //grab from session
      //add to HTML ele21 
      var secretWord = document.getElementById("ss").value;

      if (isValid(guess)) {
        //if (guessed.includes(guess)){
        //alert(guess+" already entered! Try another guess!");}
        //else {
        if (secretWord.includes(guess)) {
          right = rite.concat(guess);
          nuAddr = setVar(address, "right", right);
        }

        else {
          wrong = rong.concat(guess);
          nuAddr = setVar(address, "wrong", wrong);
        }
        //redirecting to new address
        window.location.href = nuAddr;
      }


      else {
        alert("Enter a valid letter!");
      }

    }

    function printIMG(num) {
      if (num > 11)
        num = 11;

      document.write("<img src ='/hangman-php/HIMG" + num + ".gif'><br/>");
    }

    function isValid(strGuess) {
      ordGus = strGuess.charCodeAt(0);
      if (ordGus >= 97 && ordGus <= 122) {
        return true;
      }
      else {
        return false;
      }
    }


    function getVar(addr, key) {

      var tmp = addr.toLowerCase();
      var pos, pos2, varname, varval;

      key = key.toLowerCase();
      pos = tmp.indexOf("?");

      if (pos == -1)
        tmp = "";
      else
        tmp = tmp.substring(pos + 1);

      while (tmp.length > 0) {
        pos = tmp.indexOf("=");
        varname = tmp.substring(0, pos);
        pos2 = tmp.indexOf("&");
        if (pos2 == -1) {
          varval = tmp.substring(pos + 1);
          tmp = "";
        }
        else {
          varval = tmp.substring(pos + 1, pos2);
          tmp = tmp.substring(pos2 + 1);
        }

        if (key == varname)
          return varval;
      }

      return "";
    }


    function setVar(addr, key, val) {
      var tmp = addr.toLowerCase();
      var blnQuest = false;
      var pos, pos2, varname, varval;

      key = key.toLowerCase();
      blnQuest = (tmp.indexOf("?") > -1);

      if (blnQuest) {
        pos = tmp.indexOf(key + "=");

        if (pos > -1) {
          pos = pos + key.length + 1;
          pos2 = tmp.indexOf("&", pos + 1);

          if (pos2 > -1) {
            tmp = tmp.substring(0, pos) + val + tmp.substring(pos2);
          }
          else {
            tmp = tmp.substring(0, pos) + val;
          }
        }
        else {
          tmp = addr + "&" + key + "=" + val;
        }
      }
      else {
        tmp = addr + "?" + key + "=" + val;
      }

      return tmp;
    }

  </script>

</head>

<body>
  <h1 class="text-center">Classic Hangman!</h1>
  <div class="container">
    <div class="row">
      <div class="col-md-8 text-center">
        <form action="" name="myform">
          <div class="form-group">
          Guess:
          <input type="text" name="txtGuess" id="gus" maxlength="1" minlength="1">
          <input type="button" id="button" value="Submit" name="btnClicks" onclick="btnClik_onclick();">
          </div>
          <input type="hidden" value="<?php echo $_SESSION['cur_word']?>" name="txtSecret" id="ss">
          <p class="lead alert alert-success" id="hint"> Hint:
            <?php echo $_SESSION['cur_hint']?>
          </p>

          <p class="lead" id="answer"></p>

        </form>
        <div class="row">
          <div class="col-md-6">
            <p class="lead">Used correct letters:</p>
            <div id="usedCorrect"></div>
          </div>
          <div class="col-md-6">
            <p class="lead">Used wrong letters:</p>
            <div id="usedWrong"></div>
          </div>
        </div>


      </div>

      <div class="col-md-4">
        <!--script tag-->
        <script>
          var addr = window.location.href;
          var r = getVar(addr, "right");
          var w = getVar(addr, "wrong");
          console.log("Correct letters: " + r);
          console.log("Wrong letters: " + w);
          console.log("Secret Word: " + document.getElementById("ss").value);
          var seekrit, answer;

          document.getElementById("usedCorrect").innerHTML = r;
          document.getElementById("usedWrong").innerHTML = w;


          if ((r.length + w.length) == 0) {
            seekrit = document.getElementById("ss").value;
          }


          printIMG(w.length + 1);

          wordBlender(document.getElementById("ss").value, r);


          if ((w.length) >= 10) {
            alert("Out of guesses! Game Over! The word was: " + document.getElementById("ss").value);
            sendAJAXreq();
          }

          if ((r.length + w.length) > 11) {
            alert("Too many guesses! Game Over! The word was: " + document.getElementById("ss").value);
            sendAJAXreq();

          }
          answer = document.getElementById("answer").innerHTML;

          if (!(answer.includes("*"))) {
            alert("You guessed the right answer!");
            sendAJAXreq();

          }

          function sendAJAXreq() {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function () {
              if (this.readyState == 4 && this.status == 200) {
                // change in the cs server
                window.location.href = 'http://localhost/hangman-php/hangman.php';
              }
            };
            xhttp.open("POST", "hangman.php", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send("status=gameover");
          }
        </script>

      </div>
    </div>
  </div>
</body>

</html>
