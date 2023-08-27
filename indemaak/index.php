<?php
	$fileNames = array();
	$mp3dir = "./mp3s";



	foreach (new DirectoryIterator($mp3dir) as $file) {
	  if ($file->isFile()) {

	  	$title = basename($file);
	  	$path = $mp3dir . '/' . $title;

	  	$file = array(
	  		"title" => basename($file, ".mp3"),
    		"path" => $path);

	      array_push($fileNames, $file);
	  }
	}

	//include('master.php');
?>

<div id="title" class="mediaplayerTitle">Click my bubbles!</div>

<div id="mediaPlayer" class="mediaplayer">
    <!-- <img class="mediaplayerImage" src="img/volcabass.png" />*@     -->
    <audio id="audio" controls="controls">
        <source id="mp3Source" src="" type="audio/mp3" />
        Your browser does not support the audio element.
    </audio>
</div>

<input id="position" type="range" min="0.00" step="0.01" value="0" />
<div id="time"></div>
<p id="result"></p>

<input type="button" id="pause" value="pause" />
<input type="button" id="play" value="play" />

<div class="page" align="center">
    <div class="content">
        <canvas class="canvas" height="500" width="529" id="myCanvas" ></canvas>
    </div>
</div>

<script src="scripts/jquery-3.1.1.js"></script>
<script src="scripts/custom.js"></script>

<link rel="stylesheet" type="text/css" href="styles/custom.css">

<script>
    var currentlyPlayingMp3;

    function getMp3s() {
    	return JSON.parse( '<?php echo json_encode($fileNames) ?>' );
	}

    $(document).ready(function () { 
        initializeBubbles();
        redraw();

        setInterval(move, 10);
        setInterval(initializeBubbles, 3500);

        $('#pause').click(function () { audio.pause(); })
        $('#play').click(function () { audio.play(); })

        var p = document.getElementById("position");
        var res = document.getElementById("result");

        p.addEventListener("input", function () { 
            var p = document.getElementById("position");

            if (!isNaN(audio.duration)) {
                //audio.currentTime = p.value;
            }
        }, false);

        //audio.ontimeupdate = function () {                
        //    res.innerHTML = ConvertToMinutesAndSeconds(audio.currentTime);
        //}

        //audio.onended = function () {
        //    resetPosition();
        //    audio.play();
        //}
    }); 

    function setTrack(mp3) {
        currentlyPlayingMp3 = mp3;
        resetPosition()
        //setRangeMax(mp3.DurationInSeconds)

        var source = document.getElementById('mp3Source');
        source.src = mp3.path;

        try {
            audio.load(); //call this to just preload the audio without playing
            audio.play();
        }
        catch (e) {
            // squelch. typically thrown when two bubbles pressed
        }
        var titleElement = document.getElementById("title");
        titleElement.innerText = mp3.title;
    }

    function setRangeMax(duration)
    {
        var p = document.getElementById("position");
        p.max = duration;
    }

    function resetPosition()
    {
        var p = document.getElementById("position");
        p.value = 0;
    }

    function ConvertToMinutesAndSeconds(time)
    {
        var minutes = Math.floor(time / 60);
        var seconds = time - minutes * 60;
        seconds = seconds.toFixed(0);

        if (seconds < 10) seconds = "0" + seconds;

        return minutes + ":" + seconds;
    }

    function ConvertPercentageToTime(percentage)
    {            
        var newTime = (currentlyPlayingMp3.DurationInSeconds / 100) * percentage;
        return newTime;
    }

    function ConvertTimeToPercentage(time)
    {
        return time / (currentlyPlayingMp3.DurationInSeconds * 100);
    }
</script>

<script src="scripts/bubbles.js"></script>

