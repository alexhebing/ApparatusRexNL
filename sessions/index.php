<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$sessions = array();
$mp3dir = "./mp3s";

foreach (new DirectoryIterator($mp3dir) as $file) {
  if ($file->isFile()) {
    $title = basename($file);
    $path = $mp3dir . '/' . $title;

    $file = array(
      "title" => basename($file, ".mp3"),
      "path" => $path
    );

    array_push($sessions, $file);
  }
}

    //include('master.php');
?>

<html>
<head>
<head id="head">
  <script src="scripts/jquery-3.1.1.js"></script>
  <title>Apparatus Rex</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="style.css">
  <link rel="shortcut icon" href='img/round.ico' />
</head>

<body>

<div id="menu">
    <script>
        $('#menu').load('../menu.html');
    </script>
</div>

<div class="content">
  <img id="banner" src="../img/banner.png"/>

    <table id="myTable">
        <thead><tr>
            <th>Sessions</th><th>Play!</th>
        </tr></thead>
        <tbody data-bind="foreach: sessions">
          <tr>
              <td>
                <p data-bind="text: title"></p>
              </td>
              <td>
                  <div class="audioPlayer">
                    <audio controls>
                      <source data-bind="attr: { 'src': path }" type="audio/mpeg">
                      Your browser does not support the audio tag.
                    </audio>
                  </div>
              </td>
              <!-- <td>
                  <span data-bind="text: tempo"></span>
              </td>
              <td>
                  <span data-bind="text: boomFactor"></span>
              </td> -->
          </tr>    
        </tbody>
      </table>
</div>
</body>
<script src="edit/scripts/knockout-3.4.2.js"></script>
<script src="scripts/jquery.dataTables.js"></script>
<script>

// Overall viewmodel for this screen, along with initial state
function SessionsViewModel() {
    var self = this;
    self.sessions = ko.observableArray(getSessions());
    console.log("view model init");
}

function getSessions()
{
  console.log(JSON.parse( '<?php echo json_encode($sessions) ?>' ));
  return JSON.parse( '<?php echo json_encode($sessions) ?>' );
}

ko.applyBindings(new SessionsViewModel());

$(document).ready(function(){
    $('#myTable').DataTable({
        "paging": false,
        "searching": false,
        "info": false
    });
});

</script>
