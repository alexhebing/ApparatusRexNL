
<?php

  error_reporting(E_ALL);
ini_set('display_errors', 1);

include('master.php');

$sessions = $db->listSessions();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  if (isset($_POST['btnNew']))
  {
      $target_dir = "img/";
      $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);

      SaveImage($target_file); 

      $datumTitel = test_input($_POST["naam"]);
      $audioPad = test_input($_POST["audioPad"]);
      $session = new Volcasession();
      $session->fillWithoutId($datumTitel, $audioPad);
      $session->naam = test_input($_POST["naam"]);
      $session->plaatjePad = test_input($_POST["plaatjePad"]);
      $session->boomFactor = test_input($_POST["boomFactor"]);
      $session->tempo = test_input($_POST["tempo"]);

      $db->insertSession($session);
      $sessions = $db->listSessions();
  }

  if (isset($_POST['btnUpdate']))
  {
    $removedSessions = json_decode($_POST["removedSessions"]);

    foreach($removedSessions as $session) {
      $db->deleteSession($session);
    }

    // $updatedSessions = json_decode($_POST["updatedSessions"]);

    // print_r($updatedSessions);

    // foreach($updatedSessions as $updatedSession) {
    //   $db->updateSession($updatedSession);
    // }

    $sessions = $db->listSessions();
  }
}

function SaveImage($fullPath) {
  
  $uploadOk = 1;
  $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
  // Check if image file is a actual image or fake image  
  $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
  if($check !== false) {
      echo "File is an image - " . $check["mime"] . ".";
      $uploadOk = 1;
  } 
  else {
      echo "File is not an image.";
      $uploadOk = 0;
  }

  if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
  }

  if ($_FILES["fileToUpload"]["size"] > 500000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
  }

  // Allow certain file formats
  if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
  && $imageFileType != "gif" ) {
      echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
      $uploadOk = 0;
  }

  // Check if $uploadOk is set to 0 by an error
  if ($uploadOk == 0) {
      echo "Sorry, your file was not uploaded.";
  // if everything is ok, try to upload file
  } else {
      if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
          echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
      } else {
          echo "Sorry, there was an error uploading your file.";
      }
  }
}

?>

<div class="imgcontainer">
    <img src="img/planet.jpg" width="100" height="100" alt="Avatar" class="avatar">
  </div>

<form form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
  

  <div class="newBubbles">
    <div class="container">

      <h1>Nieuwe sessie toevoegen</h1>

      <label><b>Naam</b></label>
      <input type="text" placeholder="Voer naam in" name="naam">

      <label><b>Audiopad</b></label>
      <input type="text" placeholder="Plak url naar mp3" name="audioPad">

      <label><b>Boomfactor</b></label>
      <input type="text" placeholder="crunchy?" name="boomFactor">

      <label><b>Tempo</b></label>
      <input type="text" placeholder="bpm" name="tempo">

      <label><b>PlaatjePad</b></label>
      <input type="file" name="fileToUpload" id="fileToUpload">
      <input type="text" placeholder="Plak url naar plaatje" name="plaatjePad">

      <input type="submit" name='btnNew' class="noborder" value="Voeg nieuwe sessie toe" />      
    </div>
  </div>

  <div class="updateBubbles">
    <input type="hidden" name="updatedSessions" id="updatedSessions" data-bind="value: ko.toJSON(sessions)">
    <input type="hidden" id="removedSessions" name="removedSessions">
    
    <div class="container">

      <h1>Verwijder sessies</h1>
      <table>
        <thead><tr>
            <th>DatumTitel</th><th>Naam</th><th>Datum toegevoegd</th>
        </tr></thead>
        <tbody data-bind="foreach: sessions">
          <tr>
              <td><input type="text" data-bind="value: datumTitel"/></td>
              <td><input type="text" data-bind="value: naam"/></td>
              <td><input type="text" data-bind="value: datumToegevoegd" readonly/></td>               
              <td><a href="#" data-bind="click: $root.removeSession">Verwijder</a></td>
          </tr>    
        </tbody>
      </table>
      <input type="submit" name='btnUpdate' class="noborder" value="Voer veranderingen door" onclick="setHiddenFields()" />
    </div>
  </div>

</form>

<script src="scripts/knockout-3.4.2.js"></script>
<script>
 
var removedSessions = [];

function Volcasession(datumTitel, audioPad, tempo) {
  this.datumTitel = datumTitel;
  this.audioPad = audioPad;
  this.tempo(tempo);
}

// Overall viewmodel for this screen, along with initial state
function SessionsViewModel() {
    var self = this;
    self.sessions = ko.observableArray(getSessions());
    self.removeSession = function(session) 
    { 
      self.sessions.remove(session);
      removedSessions.push(session);
    }
}

function setHiddenFields()
{
  document.getElementById("removedSessions").value = JSON.stringify(removedSessions);
}

function getSessions()
{
  //console.log(JSON.parse( '<?php echo json_encode($sessions) ?>' ));
  return JSON.parse( '<?php echo json_encode($sessions) ?>' );
}

ko.applyBindings(new SessionsViewModel());
</script>