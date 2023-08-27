
<?php

include('master.php');

$entries = $db->listEntries();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST['btnNew']))
  {
    $naam = test_input($_POST["naam"]);
    $pad = test_input($_POST["pad"]);
    $entry = new Entry();
    $entry->fillWithoutId($naam, $pad);

    $db->insertEntry($entry);
    $entries = $db->listEntries();
  }

  if (isset($_POST['btnUpdate']))
  {
    $removedEntries = json_decode($_POST["removedEntries"]);

    foreach($removedEntries as $entry) {
      $db->deleteEntry($entry);
    }
    $entries = $db->listEntries();
  }
}

?>

<div class="imgcontainer">
    <img src="img/planet.jpg" alt="Avatar" class="avatar">
  </div>

<form form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
  

  <div class="newBubbles">
    <div class="container">

      <h1>Nieuwe bubbels</h1>

      <label><b>Naam</b></label>
      <input type="text" placeholder="Voer naam in" name="naam">

      <label><b>URL</b></label>
      <input type="text" placeholder="Plak url naar mp3 of video" name="pad">

      <input type="submit" name='btnNew' class="noborder" value="Voeg nieuwe bubbel toe" />      
    </div>
  </div>

  <div class="updateBubbles">
    <input type="hidden" name="updatedEntries" data-bind="value: ko.toJSON(entries)">
    <input type="hidden" id="removedEntries" name="removedEntries">
    
    <div class="container">

      <h1>Update bubbels</h1>
      <table>
        <thead><tr>
            <th>Naam</th><th>Pad</th><th>Datum toegevoegd</th>
        </tr></thead>
        <tbody data-bind="foreach: entries">
          <tr>
              <td><input type="text" data-bind="value: naam"/></td>
              <td><input type="text" data-bind="value: pad"/></td>
              <td><input type="text" data-bind="value: datumToegevoegd" readonly/></td>               
              <td><a href="#" data-bind="click: $root.removeEntry">Verwijder</a></td>
          </tr>    
        </tbody>
      </table>
      <input type="submit" name='btnUpdate' class="noborder" value="Voer updates door" onclick="setHiddenFields()" />
    </div>
  </div>

</form>

<script src="scripts/knockout-3.4.2.js"></script>
<script>
 
var removedEntries = [];

  // Class to represent a row 
function Entry(id, naam, pad, datumToegevoegd) {
    var self = this;
    self.naam = naam;
    self.pad = pad;
    self.datumToegevoegd = datumToegevoegd;
    self.id = id; 
}

// Overall viewmodel for this screen, along with initial state
function EntriesViewModel() {
    var self = this;
    self.entries = ko.observableArray(getEntries());
    self.removeEntry = function(entry) 
    { 
      self.entries.remove(entry);
      removedEntries.push(entry);
    }
}

function setHiddenFields()
{
  document.getElementById("removedEntries").value = JSON.stringify(removedEntries);
}

function getEntries()
{
  //console.log(JSON.parse( '<?php echo json_encode($entries) ?>' ));
  return JSON.parse( '<?php echo json_encode($entries) ?>' );
}

ko.applyBindings(new EntriesViewModel());
</script>