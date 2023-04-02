<!DOCTYPE html>
<html lang="en">
<head>
    <title>Audio Player</title>
    <meta charset="utf-8">
    <style>
        * {
            font-family: Helvetica, sans-serif;
            box-sizing: border-box;
        }

        html {
            width: 100%;
            min-height: 100vh;
        }

        body {
            padding: 0;
            margin: 0;
            min-height: 100vh;
            display: flex;
        }

        #content {
            margin: auto;
            width: 80%;
            min-width: 320px;
            max-width: 1200px;
            padding: 5% 0 5% 0;
        }

        #audioplayer {
            width: 100%;
        }

        #playlist {
            margin-top: 10px;
        }

        #playlist .song {
            padding: 10px;
            color: #646464;
            border-bottom: 1px solid #e1e1e1;
            cursor: pointer;
        }

        #playlist .song:first-child {
            border-top: 1px solid #e1e1e1;
        }

        #playlist .song.now {
            color: #333;
            background: #f1f3f4;
        }

        #playlist .song::before {
            content: "\2022";
            margin-right: 10px;
        }

        #coverart img {
            width: 100%;
            height: auto;
            background: url("./cover.png") fixed center center;
            margin-bottom: 5%;

            -webkit-box-shadow: 0px 5px 15px -6px rgba(0, 0, 0, 0.8);
            box-shadow: 0px 5px 15px -6px rgba(0, 0, 0, 0.8);
        }

        @media only screen and (min-width: 800px) {
            #content {
                display: flex;
                flex-direction: row;
                flex-wrap: nowrap;
                justify-content: center;
                align-items: center;
                align-content: center;
            }

            #coverart, #playerwrap {
                flex: 0 1 auto;
                flex-grow: 0;
                flex-shrink: 1;
                flex-basis: auto;
                align-self: flex-start;
            }

            #coverart {
                width: 35%;
            }

            #playerwrap {
                width: 60%;
                margin-left: 5%;
            }
        }
    </style>
    <script>
        const aud = {
            // (A) INITIALIZE PLAYER
            player: null,   // html <audio> element
            playlist: null, // html playlist
            now: 0,         // current song
            init: () => {
                // (A1) GET HTML ELEMENTS
                aud.player = document.getElementById("audioplayer");
                aud.playlist = document.querySelectorAll("#playlist .song");

                // (A2) LOOP THROUGH ALL THE SONGS, CLICK TO PLAY
                for (let i = 0; i < aud.playlist.length; i++) {
                    aud.playlist[i].onclick = () => aud.play(i);
                }

                // (A3) AUTO PLAY WHEN SUFFICIENTLY LOADED
                aud.player.oncanplay = aud.player.play;

                // (A4) AUTOPLAY NEXT SONG IN PLAYLIST WHEN CURRENT SONG ENDS
                aud.player.onended = () => {
                    aud.now++;
                    if (aud.now >= aud.playlist.length) {
                        aud.now = 0;
                    }
                    aud.play(aud.now);
                };
            },

            // (B) PLAY SELECTED SONG
            play: id => {
                // (B1) UPDATE AUDIO SRC
                aud.now = id;
                aud.player.src = "audio/" + aud.playlist[id].dataset.src;

                // (B2) A LITTLE BIT OF COSMETIC
                for (let i = 0; i < aud.playlist.length; i++) {
                    if (i === id) {
                        aud.playlist[i].classList.add("now");
                    } else {
                        aud.playlist[i].classList.remove("now");
                    }
                }
            }
        };
        window.addEventListener("DOMContentLoaded", aud.init);
    </script>
</head>
<body>
<div id="content">
    <!-- COVER ART -->
    <?php if (file_exists("./cover.png")) {
        echo '<div id="coverart"><img src="cover.png" alt="coverart"></div>';
    }
    ?>

    <!-- AUDIO PLAYER -->
    <div id="playerwrap">
        <!-- TRANSPORT CONTROLS -->
        <audio id="audioplayer" controls></audio>

        <!-- PLAYLIST -->
        <div id="playlist">
            <?php
            // GET ALL SONGS
            $songs = glob("audio/*.{mp3,webm,ogg,wav}", GLOB_BRACE);

            // OUTPUT SONGS IN <DIV>
            if (is_array($songs)) {
                foreach ($songs as $k => $s) {
                    $name = basename($s);
                    printf("<div data-src='%s' class='song'>%s</div>", rawurlencode($name), $name);
                }
            } else {
                echo "No songs found!";
            }
            ?>
        </div>
    </div>

</div>
</body>
</html>