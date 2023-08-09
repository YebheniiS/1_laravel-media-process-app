$('#owl-demo').owlCarousel({
    singleItem:true,
    margin:0,
    loop:true,          
    nav: true,
    navText: ["<img src='https://i-fast.b-cdn.net/interactr.io/slide-previous-icon.png'>","<img src='https://i-fast.b-cdn.net/interactr.io/slide-next-icon.png'>"],
    dots: true,
    items:1,     
    autoplay:false, 
    smartSpeed:2000,
    autoplayTimeout:3000,             
    }); 


$('a[href^="#"]').click(function() {
    $('html,body').animate({ scrollTop: $(this.hash).offset().top});
    return false;
    e.preventDefault();
    });


$('.video').parent().click(function () {
    if($(this).children(".video").get(0).paused){        $(this).children(".video").get(0).play();   $(this).children(".playpause").fadeOut();
        }else{       $(this).children(".video").get(0).pause();
    $(this).children(".playpause").fadeIn();
        }
    });

// https://developers.google.com/youtube/iframe_api_reference

// global variable for the player
var player;

// this function gets called when API is ready to use
function onYouTubePlayerAPIReady() {
  // create the global player from the specific iframe (#video)
  player = new YT.Player("video", {
    events: {
      // call this function when player is ready to use
      onReady: onPlayerReady
    }
  });
}

function onPlayerReady(event) {
  // bind events
  var playButton = document.getElementById("play-button");
  playButton.addEventListener("click", function () {
    player.playVideo();
  });

  var pauseButton = document.getElementById("pause-button");
  pauseButton.addEventListener("click", function () {
    player.pauseVideo();
  });
}

// Inject YouTube API script
var tag = document.createElement("script");
tag.src = "//www.youtube.com/player_api";
var firstScriptTag = document.getElementsByTagName("script")[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

