var isActiveTab = true;

var canvas = document.getElementsByTagName("canvas")[0]; //get the canvas dom object
var ctx = canvas.getContext("2d"); //get the context
var radius = 23;

var bubbles = [];

var mp3s = getMp3s();

function initializeBubbles() {
    if (isActiveTab) {
        for (var i = 1; i <= canvas.width / (radius * 2) + 3; i++) {
            var b = new bubble(i * ((radius) * 2), canvas.height + radius * 5);
            bubbles.push(b);
        }
    }
}

function bubble(x, y) {
    this.x = x;
    this.y = y;
    this.verticalSpeed = getRandomArbitrary(0.3, 1.0)

    this.left = x - radius;
    this.right = x + radius;

    bubble.prototype.setTopAndBottom = function (y) {
        this.top = y - radius;
        this.bottom = y + radius;
    }
}

function getRandomArbitrary(min, max) {
    return Math.random() * (max - min) + min;
}

var redraw = function () {
    ctx.clearRect(0, 0, canvas.width, canvas.height); //clear canvas

    for (var i = 0; i < bubbles.length; i++) {

        var c = bubbles[i];

        ctx.beginPath();  //draw the object c
        ctx.arc(c.x, c.y, radius, 0, Math.PI * 2);
        ctx.closePath();
        
        if (c.verticalSpeed < 0.6)
        {
            if (c.verticalSpeed < 0.4)
            {
                ctx.fillStyle = "#ff3399";
            }
            else
            {
                ctx.fillStyle = "#ff4da6";
            }
        }
        else
        {
            ctx.fillStyle = "hotpink";
        }

        
        ctx.fill();
    };


    requestAnimationFrame(redraw);
}

function move() {

    for (var i = 0; i < bubbles.length; i++) {

        var c = bubbles[i];

        if (c) {
            if (c.y < -10) {
                bubbles.splice(i, 1);
            }
            else {
                c.y = c.y - c.verticalSpeed;
                c.setTopAndBottom(c.y);
            }
        }
    }
}

$('#myCanvas').click(function (e) {
    var clickedX = e.pageX - this.offsetLeft;
    var clickedY = e.pageY - this.offsetTop;

    for (var i = 0; i < bubbles.length; i++) {
        var bubble = bubbles[i];

        if (clickedX < bubble.right && clickedX > bubble.left && clickedY > bubble.top && clickedY < bubble.bottom) {

            var numberOfMp3s = mp3s.length;

            if (i > mp3s.length)
            {
                // todo: iets slims verzinnen om bubble aan mp3 te linken
            }
            else {
                setTrack(mp3s[i])
            }
        }
    }
});

$(document).ready(function () {
    var hidden, visibilityState, visibilityChange;

    if (typeof document.hidden !== "undefined") {
        hidden = "hidden", visibilityChange = "visibilitychange", visibilityState = "visibilityState";
    } else if (typeof document.msHidden !== "undefined") {
        hidden = "msHidden", visibilityChange = "msvisibilitychange", visibilityState = "msVisibilityState";
    }

    var document_hidden = document[hidden];

    document.addEventListener(visibilityChange, function () {
        if (document_hidden != document[hidden]) {
            if (document[hidden]) {
                isActiveTab = false;
            } else {
                isActiveTab = true;
            }

            document_hidden = document[hidden];
        }
    });
});