<!DOCTYPE html>
<!--
    Licensed to the Apache Software Foundation (ASF) under one
    or more contributor license agreements.  See the NOTICE file
    distributed with this work for additional information
    regarding copyright ownership.  The ASF licenses this file
    to you under the Apache License, Version 2.0 (the
    "License"); you may not use this file except in compliance
    with the License.  You may obtain a copy of the License at

    http://www.apache.org/licenses/LICENSE-2.0

    Unless required by applicable law or agreed to in writing,
    software distributed under the License is distributed on an
    "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
     KIND, either express or implied.  See the License for the
    specific language governing permissions and limitations
    under the License.
-->
<html ng-app>
<head>
    <!--meta charset="utf-8"/>
    <meta name="format-detection" content="telephone=no"/>
    <!-- WARNING: for iOS 7, remove the width=device-width and height=device-height attributes. See https://issues.apache.org/jira/browse/CB-4323 -->
    <!--meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height, target-densitydpi=device-dpi"/-->

    <meta charset="utf-8"/>
    <meta name="format-detection" content="telephone=no"/>
    <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1, maximum-scale=1">

    <?php
       echo '<meta property="og:image" content="http://www.coldfusionjedi.com/images/ScreenClip145.png"/>';
    ?>



    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">

    <link rel="stylesheet" type="text/css" href="public/js/libs/chosen/chosen.min.css"/>

    <link rel="stylesheet" type="text/css" href="public/css/fb.css"/>

    <link rel="stylesheet" type="text/css" href="public/css/index.css"/>
    <link rel="stylesheet" type="text/css" href="public/css/songlist.css"/>
    <link rel="stylesheet" type="text/css" href="public/css/theme.css"/>

    <link rel="stylesheet" type="text/css" href="public/css/videoPlayer/videoPlayer.css"/>

    <link rel="stylesheet" type="text/css" href="public/css/libs/jquery.mobile-1.4.2.css"/>
    <link rel="stylesheet" href="public/js/libs/mediaelement/mediaelementplayer.css"/>
    <link rel="stylesheet" href="public/js/libs/mediaelement/playlist/mep-feature-playlist.css"/>

    <script type="text/javascript">
        //Reload Page if params
        function getURLParameters(name) {
            name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
            var regexS = "[\\?&]" + name + "=([^&#]*)";
            var regex = new RegExp(regexS);
            var results = regex.exec(window.location.href);
            if (results == null)    return false;
            else    return results[1];
        }


        if (getURLParameters("ui-state")) {
            console.log("RELOAD")
            location.href = 'http://' + window.location.hostname + window.location.pathname;    //TODO !!!!!!!!!!!!!!!

        }
        //PROVIDE DEBUG INFORMATION
        if (typeof window.console == "undefined") {
            window.console = {log: function (str) {
                window.external.Notify(str);
            }};
        }


        function prompt() {

        }


        window.onerror = function (msg, url, linenumber) {

            console.log("ERROR! : " + url + "(" + linenumber + ") : " + JSON.stringify(msg));

            // Send Error to server
            if (true || Math.random() > .1) {
                // Only log 10% of errors
                return false;
            }
            else {

                var args = "ref=" + window.location.hash + "&line=" + linenumber + "&url=" + url + "&message=" + msg;

                $.ajax({
                    url: "/client_error",
                    type: "POST",
                    data: args,
                    dataType: "json",
                    error: function (xhr, ajaxOptions, thrownError) {
                    },
                    success: function (data) {
                    }
                });

            }
        }

        console.log("Installed console ! ");
    </script>

    <title>Songbase.fm</title>
</head>
<body ng-controller="MainController">

<!-- FACEBOOK -->
<div id="fb-root"></div>
<script>(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/de_DE/all.js#xfbml=1";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<!-- FACEBOOK -->

<div id="lyricsiframe" class="fadeincompleteslow" style="display:none">
    <div id="lyricsifrmback">
    </div>
    <iframe id="lyricsifrm" src="about:blank" seamless="">
    </iframe>

    <img id="lyricsiframeresizebar" onclick="mediaController.toggleLyrics();" src="public/img/resizebar.png" style="position:absolute;right: 0px;top:0px">
</div>


<div data-role="page" id="page" style="opacity:0" data-theme="b">

<div data-role="panel" id="rightpanel" data-display="overlay" data-position="right" data-theme="b">
</div>
<!-- /panel -->

<div data-role="header" id="header" data-position="fixed">
    <img src="public/img/logo.gif" id="iconHeader" style="opacity:0;position:absolute;left: 8px;top: 8px" width="30px" height="30px">


    <h1 id="titleHeader" style="display:none">{{appTitle}}</h1>

    <img src="public/img/bars-white.png" id="openSidePanelBarIcon" onclick="uiController.toggleSidePanel()" style="position:absolute;right: 6px;top: 13px" width="17px" height="20px"/>


    <div class="ui-btn-right">
        <!--div id="fblike"  href="#" >
         <div class="fb-like fadeincompleteslow" data-show-faces="true" style="display:none" data-width ="85px" data-href="https://www.songbase.fm" data-layout="box_count" data-action="like" data-show-faces="true" data-share="true"></div>
        </div-->
        <a id="playingSongInfoLink" style="opacity:0" onclick="searchController.getArtistInfo();" ng-show="playbackController.playingSong" href="#popupArtist" data-rel="popup" data-position-to="#playingSongInfoLink"
           class="playingSongInfo  ui-btn  ui-corner-all ui-shadow ui-btn-inline ui-icon-custom ui-btn-icon-left ui-btn-a" data-transition="pop">
            <span style="opacity:0">{{playbackController.getPlayingTitle()}}</span>
            <span id="playingSongTitleLoading" class="fadeincomplete" style="text-align:left;z-index:0;display:none;position:absolute;left: 35px;top: 9px;right: 0px">{{playbackController.getPlayingTitle()}}</span>
            <span id="playingSongTitle" class="fadeincomplete" style="text-align:left;z-index:0;display:none;position:absolute;left: 35px;top: 9px;right: 0px">{{playbackController.getPlayingTitle()}} </span>
        </a>
        <a id="buySongLink" style="opacity:0" onclick="mediaController.buySong()" ng-show="playbackController.playingSong" href="#" data-rel="popup" data-position-to="#buySongLink"
           class="playingSongBuy   ui-btn  ui-corner-all ui-shadow ui-btn-inline ui-icon-heart ui-btn-icon-left ui-btn-a" data-transition="pop">Buy Song</a>

        <a id="registerLink" ng-if="!accountController.loggedIn" href="#popupRegister" onclick="setTimeout(function(){$('#registerusername').focus();},700)" data-rel="popup" data-position-to="#registerLink"
           class="ui-btn ui-corner-all ui-shadow ui-btn-inline ui-icon-plus ui-btn-icon-left ui-btn-a" data-transition="pop">Sign up</a>

        <a id="signinLink" ng-if="!accountController.loggedIn" href="#popupLogin" onclick="setTimeout(function(){$('#signinusername').focus();},700)" data-rel="popup" data-position-to="#signinLink"
           class="ui-btn ui-corner-all ui-shadow ui-btn-inline ui-icon-check ui-btn-icon-left ui-btn-a" data-transition="pop">Sign in</a>
        <a id="linkAccount" ng-if="accountController.loggedIn" href="#popupAccount" data-rel="popup" class="ui-btn  ui-corner-all ui-shadow ui-btn-inline ui-icon-user ui-btn-icon-left ui-btn-a" data-transition="pop">{{accountController.userName}}</a>


    </div>

</div>


<!-- /header -->

<!--Open Side Panel-->
<div onclick="uiController.toggleSidePanel();" id="openSidePanelBarIconBar" style="z-index:9000;position: fixed;top: 0;right: 0;width: 30px;height:44px;"></div>



<div data-role="content" id="content">
<div id="controlbar">
    <input id="searchinput" data-type="search" data-theme="a" placeholder="Search">


    <div id="controlselecthorizontal">
        <a id="searchlayoutbutton" data-type="button" data-theme="b" onclick="uiController.toggleGridLayout();" style="background-color: #442727;width: 4px;height: 20px;" class="ui-input-btn ui-btn ui-btn-b ui-shadow ui-corner-all"><img src="public/img/grid.png"  style="width: 20px;margin-left: -8px;"> </a>
        <input id="searchbutton1" data-type="button" data-theme="b" onclick="searchController.activateButton(0);searchController.showSearchList()" type="button" value="Songs">
        <input id="searchbutton2" data-type="button" data-theme="b" onclick="searchController.activateButton(1);searchController.showPopulars()" type="button" value="Popular">
        <input id="searchbutton3" data-type="button" data-theme="b" onclick="searchController.activateButton(2);searchController.showSuggestions()" type="button" value="Suggestions">
        <input id="searchbutton4" data-type="button" data-theme="b" onclick="searchController.activateButton(3);searchController.showPlaylists()" type="button" value="Playlists">
    </div>
    <div id="controlselectvertical">

        <select name="controlselectvertical" data-native-menu="false" data-iconpos="left" data-inline="true" data-theme="a">
            <option value="1">Songs</option>
            <option value="2">Popular</option>
            <option value="3">Suggestions</option>
            <option value="4">Playlists</option>
        </select>

    </div>
</div>


<div id="searchlist">


    <!--form class="ui-filterable" >
        <input id="filterBasic-input" data-type="search" data-theme="a">
    </form-->

    <ul  data-role="listview" id="searchlistview" class="connectedSortable songlist fast3d">

        <li ng-repeat="song in searchController.searchResults track by song.id" data-songid="searchsong{{song.id}}" data-songtitle ="{{song.name}}-{{mediaController.getSongArtist(song)}}"   class="draggableSong fadeslideincompletefast" ng-click="playbackController.clickedElement($event,song);"  ng-dblclick="playlistController.deselectSongs($event);"><a tabindex="-1"><img
                src="public/img/empty.png"   ng-style="{'background-image':'url('+mediaController.getSongCover(song)+')','background-size':'100%'}" alt="" class="ui-li-icon ui-corner-none" ><img src="public/img/empty.png"
                                                                                                                                                                                                   class="loadingSongImg"   >

            <h3>{{song.name}}</h3>

            <p>{{mediaController.getSongArtist(song)}}</p></a>
        </li>


    </ul>
</div>


<div id="playlist" style="opacity:0">

    <!--h3>Playlist</h3-->

    <div id="controlbarplaylist">

        <div id="playlistselectvertical">
            <a href="#" id="clearChoosenPlaylists" style="display:none" class="ui-input-clear ui-btn ui-icon-delete ui-btn-icon-notext ui-corner-all" title="Clear Selection">Clear Selection</a>

            <form>
                <select id="playlistselectverticalform" data-role="none" data-placeholder="Select Playlists" multiple class="chosen-select">
                    <option ng-repeat="playlist in playlistController.playlists track by playlist.gid" value="{{playlist.gid}}">{{playlist.name}}</option>
                </select>

            </form>


        </div>
        <div id="sortplaylistbutton">

            <button id="sortplaylistbtn" onclick="playlistController.toggleSortablePlaylist(false,true);" data-mini="true" data-type="button" value="" data-theme="b">
                <img src="public/img/sort.png" width="22px" height="22px" alt="Sort"/>
            </button>

        </div>
        <div id="saveplaylistinput" style="display:none">
            <input id="saveplaylistinpt" class="ui-input ui-body-a ui-corner-all ui-shadow-inset" data-theme="a" placeholder="Enter Name">
        </div>
        <div id="saveplaylistbutton">
            <button id="saveplaylistbtn" onclick="playlistController.toggleSavePlaylist();" data-mini="true" data-type="button" value="" data-theme="b">
                <img src="public/img/plus.png" width="22px" height="22px" alt="Sort"/>
            </button>
        </div>
        <div id="saveokayplaylistbutton" style="display:none">

            <button id="saveokayplaylistbtn" onclick="playlistController.toggleSavePlaylist(true);" class="greenbackground" data-mini="true" data-type="button" value="" data-theme="b">
                <img src="public/img/check.png" width="22px" height="22px" alt="Sort"/>
            </button>

        </div>

    </div>
    <!--form class="ui-filterable" >
        <input id="filterBasic-input" data-type="search" data-theme="a">
    </form-->
    <div id="playlisthelp" ng-show="playlistController.loadedPlaylistSongs.length==0">
        Drag and Drop your favorite Songs<br>to add them to this Playlist.
    </div>

    <div id="playlistInner" class="animate" style="display:none">
        <ul ui-sortable ng-model="playlistController.loadedPlaylistSongs" data-role="listview" id="playlistview" class="sortable songlist connectedSortable">
            <li ng-repeat="song in playlistController.loadedPlaylistSongs track by song.gid" data-songid="playlistsong{{song.id}}" data-songgid="playlistsong{{song.gid}}" class="fadeslideincompletefast playlistsong"
                ng-click="playbackController.clickedElement($event,song);"  ng-dblclick="playlistController.deselectSongs($event);"><a tabindex="-1"><img src="public/img/empty.png" ng-style="{'background-image':'url('+mediaController.getSongCover(song)+')','background-size':'100%'}" alt=""
                                                                                                                                                          class="ui-li-icon ui-corner-none"  >
                <img src="public/img/empty.png" class="loadingSongImg">
                    <span ng-if="!song.isPlaylist">
                    <h3>{{song.name}}</h3>
                    <p>{{mediaController.getSongArtist(song)}}</p><img class="removesong" ng-style="{'background-image':'url(public/img/trash.png)',display:'none'}" src="public/img/empty.png">
                    </span>
                    <span ng-if="song.isPlaylist">
                         <h3>{{song.name}}</h3>
                         <p>Playlist</p><img class="removesong" ng-style="{'background-image':'url(public/img/trash.png)',display:'none'}" src="public/img/empty.png">
                    </span>
            </a></li>
        </ul>
    </div>
</div>

<div id="videoplayer">
    <div id="videoplayerInner">

        <!-- mediaemelemtjs player -->
        <video id="mediaemelemtjsPlayer1" controls="controls" preload="true">
            <source src="" type="video/mp4"/>
            <!--   <source src="" type="video/flv"/> -->
        </video>


        <--OLD //TODO REMOVE -->
        <video id="player1" style="display:none" >

        </video>


        <!-- embedded players -->
        <div id="embedplayer" >
            <div id="dmplayer" ></div>
        </div>

    </div>

</div>
<div id="videocontrols" class="videoControlElements-container" style="text-align: center">
    <div id="videocontrolsInner">

        <div class="videoControlElements-controls">
            <div class="videoControlElements-button videoControlElements-prevtrack-button videoControlElements-prevtrack">
                <button type="button" aria-controls="mep_0" title="Previous Track" ></button>
            </div>
            <div class="videoControlElements-button videoControlElements-playpause-button videoControlElements-play">
                <button type="button" aria-controls="mep_0" title="Play/Pause" aria-label="Play/Pause" ></button>
            </div>
            <div class="videoControlElements-button videoControlElements-stop-button videoControlElements-stop">
                <button type="button" aria-controls="mep_0" title="Stop" aria-label="Stop" ></button>
            </div>
            <div class="videoControlElements-button videoControlElements-nexttrack-button videoControlElements-nexttrack">
                <button type="button" aria-controls="mep_0" title="Next Track" ></button>
            </div>
            <div class="videoControlElements-button videoControlElements-shuffle-button videoControlElements-shuffle-off">
                <button type="button" aria-controls="mep_0" title="Shuffle On/Off" style="opacity:0.5" >
                </button>
            </div>
            <div class="videoControlElements-time videoControlElements-currenttime-container">
                <span class="videoControlElements-currenttime">00:00</span>

            </div>
            <div class="videoControlElements-time-rail" style="width: 491px;">
                    <span class="videoControlElements-time-total" style="width: 481px;">
                        <span class="videoControlElements-time-buffering" style="display: none;"></span>
                        <span class="videoControlElements-time-loaded"></span>
                        <span class="videoControlElements-time-current"></span>
                        <span class="videoControlElements-time-handle"></span>
                        <span class="videoControlElements-time-float">
                            <span class="videoControlElements-time-float-current">00:00</span>
                            <span class="videoControlElements-time-float-corner"></span>
                        </span>
                    </span>
            </div>

            <div class="videoControlElements-time videoControlElements-duration-container">
                <span class="videoControlElements-duration">00:00</span>
            </div>

            <div class="videoControlElements-button videoControlElements-volume-button videoControlElements-mute">
                <button type="button" aria-controls="mep_0" title="Mute Toggle" aria-label="Mute Toggle"></button>
                <div class="videoControlElements-volume-slider" style="display: none;">
                    <div class="videoControlElements-volume-total"></div>
                    <div class="videoControlElements-volume-current" style="height: 80px; top: 32px;"></div>
                    <div class="videoControlElements-volume-handle" style="top: 29px;"></div>
                </div>
            </div>


            <div class="videoControlElements-button videoControlElements-fullscreen-button">
                <button type="button" aria-controls="mep_0" title="Fullscreen" style="opacity:0.5" aria-label="Fullscreen"> </button>
            </div>

            <div class="videoControlElements-button videoControlElements-button-choose-version videoControlElements-custom-button">
                <button type="button" id="chooseversionbutton" data-role="none" style="opacity:0.5" aria-controls="mep_0" title="Choose Version" aria-label="Choose Version"></button>
            </div>
            <div class="videoControlElements-button videoControlElements-button-lyrics videoControlElements-custom-button">
                <button type="button" id="lyricsbutton" data-role="none" style="opacity:0.5" aria-controls="mep_0" title="Lyrics" aria-label="Lyrics"></button>
            </div>
            <div class="videoControlElements-button videoControlElements-button-facebook videoControlElements-custom-button">
                <button type="button" id="facebookpostbutton" data-role="none"   aria-controls="mep_0" title="Facebook" aria-label="Facebook"></button>
            </div>
            <!--div style =class="videoControlElements-button videoControlElements-button-copyright videoControlElements-custom-button">
                <button type="button" id="copyrightbutton" data-role="none" style="opacity:0.9"  onclick="window.open('','_blank')" aria-controls="mep_0" title="Copyright" aria-label="Copyright"></button>
            </div-->
            <div class="videoControlElements-button videoControlElements-button-external videoControlElements-custom-button">
                <button type="button" id="openexternalbutton" data-role="none" style="opacity:0.5"  aria-controls="mep_0" title="Open external site" aria-label="Open external site"></button>
            </div>
        </div>

    </div>
</div>

<div id="siteLogo" style="display:none" class="fadeincomplete2s" >
    <img id="siteLogoImage" width="70px"  src="" onclick="mediaController.openExternalSite()">
</div>

<div id="songOptions">

</div>



</div>
<!-- /content -->


<!-- Popups ---------------------------------------->

<div data-role="popup" id="popupVideoSettings" data-arrow="true" data-theme="a" class="ui-corner-all">
    <form>
        <div>
            <h3 style="margin-right: 40px; margin-left:40px;text-align: center">Choose Version</h3>
            <ul data-role="listview" id="searchviewVersions" data-theme="b">
                <li ng-repeat="songversion in mediaController.versionList track by songversion.id" data-theme="b" class="fadeslideincompletefast playlistsong" ng-click="mediaController.playVersion(songversion,1,1)"><a href="#"
                                                                                                                                                                                                                          style="padding-left: 15px!important;"
                                                                                                                                                                                                                          id=""
                                                                                                                                                                                                                          title="{{songversion.url}}">{{songversion.title}}<span
                        style="opacity:0"> ..{{mediaController.showDuration(songversion)}}</span> <span style="position:absolute;right: 42px;top:10px;opacity:0.8"> {{ mediaController.showDuration(songversion) }}</span> </a></li>

            </ul>
            <div id="loadversionimg" style="opacity:0">
                <img src="public/img/loader.gif"/>
            </div>
        </div>
    </form>
    <br>
</div>

<div data-role="popup" id="popupLogin" data-arrow="true" data-theme="a" class="ui-corner-all">
    <form>
        <div style="padding:0px 20px 10px 20px">
            <h3 id="signintitle">Sign in</h3>
            <label for="signinusername" class="ui-hidden-accessible">Username:</label>
            <input type="text" name="user" id="signinusername" value="" placeholder="Username" data-theme="a" >
            <span id="useremail" style="display:none">
            <label for="signinuser" class="ui-hidden-accessible">Email Adress:</label>
            <input type="text" name="user" id="signinuser" value="" placeholder="Email Adress" data-theme="a" >
            </span>
            <label for="signinpw" class="ui-hidden-accessible">Password:</label>
            <input type="password" name="pass" id="signinpw" value="" placeholder="Password" data-theme="a" >
            <button onclick="accountController.signIn();" type="submit" id="signinButton" class="ui-btn ui-corner-all ui-shadow ui-btn-b ui-btn-icon-left ui-icon-check">Sign in</button>
        </div>
    </form>
</div>

<div data-role="popup" id="popupRegister" data-arrow="true" data-theme="a" class="ui-corner-all">
    <form>
        <div style="padding:0px 20px 10px 20px">
            <h3 id="registertitle">Sign Up For Free</h3>
            <label for="registerusername" class="ui-hidden-accessible">Username:</label>
            <input type="text" name="user" id="registerusername" value="" placeholder="Username" data-theme="a" >
            <span id="registeruseremail">
            <label for="registeruser" class="ui-hidden-accessible">Email Adress:</label>
            <input type="text" name="user" id="registeruser" value="" placeholder="Email Adress" data-theme="a" >
            </span>
            <label for="registerpw" class="ui-hidden-accessible">Password:</label>
            <input type="password" name="pass" id="registerpw" value="" placeholder="Password" data-theme="a" >
                <span id="pwconfirm">
                <input type="password" name="pass" id="registerpwc" value="" placeholder="Confirm Password" data-theme="a" >
                </span>
            <button onclick="accountController.register();" type="submit" id="registerButton" class="ui-btn ui-corner-all ui-shadow ui-btn-b ui-btn-icon-left ui-icon-check">Create Account</button>
        </div>
    </form>
</div>

<div data-role="popup" id="popupArtist" data-arrow="true" data-theme="a" class="ui-corner-all">
    <form>
        <div style="text-align:center">
            <h3 style="margin-right: 40px;margin-left: 40px;padding-right: 20px;padding-left: 20px;text-align: center;">{{mediaController.getSongArtist(playbackController.playingSong)}}</h3>

            <div style="font-size: 1em;font-weight: bold;margin-top: -10px; margin-bottom: 10px;"> {{playbackController.playingSong.name}}</div>
            <ul data-role="listview" id="popupArtistExternList" data-theme="b">
                <li data-theme="b" class="marked"><a ng-show="playbackController.playingSong" onclick="$('#popupArtist').popup('close');searchController.searchArtistSongs(mediaController.getSongArtist(playbackController.playingSong));"
                                                     style="text-decoration:none" target="_blank">Songs from Artist</a></li>
                <li data-theme="b" class="marked"  style="border-bottom:1px solid #ddd;"><a ng-show="playbackController.playingSong" onclick="$('#popupArtist').popup('close');searchController.searchSimilarSongs(playbackController.playingSong);"
                                                                                            style="text-decoration:none" target="_blank">Similar Songs</a></li>
                <li data-theme="b" style="border-top:15px solid rgba(255,255,255,0.8);"><a ng-show="playbackController.playingSong" href="http://de.wikipedia.org/w/index.php?go=Artikel&title&search={{mediaController.getSongArtist(playbackController.playingSong)}}"
                                                                                           onclick="$('#popupArtist').popup('close')" style="text-decoration:none" target="_blank">Wikipedia</a></li>
                <li data-theme="b"><a ng-show="playbackController.playingSong" href="http://www.lastfm.de/search?q={{mediaController.getSongArtist(playbackController.playingSong)}}" onclick="$('#popupArtist').popup('close')"
                                      style="text-decoration:none" target="_blank">last.fm</a></li>
                <li data-theme="b"><a ng-show="playbackController.playingSong" href="https://myspace.com/search/?q={{mediaController.getSongArtist(playbackController.playingSong)}}" onclick="$('#popupArtist').popup('close')"
                                      style="text-decoration:none" target="_blank">Myspace</a></li>
                <li data-theme="b"><a ng-show="playbackController.playingSong" href="https://www.google.de/search?q={{mediaController.getSongArtist(playbackController.playingSong)}}" onclick="$('#popupArtist').popup('close')"
                                      style="text-decoration:none" target="_blank">Google</a></li>

            </ul>
        </div>
    </form>
    <span id="songfblike">
        <div class="fb-like" style="display:inline-block" data-href="https://www.songbase.fm" data-layout="button_count" data-action="like" data-show-faces="true" data-share="true"></div>
       <!--div class="fb-like"  data-href="https://www.songbase.fm" data-layout="button" data-action="like" data-show-faces="true" data-share="true"></div-->
    </span>
</div>


<div data-role="popup" id="popupAccount" data-arrow="true" data-theme="a" class="ui-corner-all">
    <form>
        <div>
            <h3 style="margin-right: 40px; margin-left:40px;text-align: center">Account</h3>
            <ul data-role="listview">
                <li><a href="#" data-rel="back" id="manageFacebook"><img src="public/img/fb.png">Facebook</a></li>
                <li><a href="#" data-rel="back" onclick="googleHandler.login();" id="manageGoogle"><img src="public/img/gdrive.png">Google
                    <!--span class="GoogleBlue">G</span><span class="GoogleRed">o</span><span class="GoogleYellow">o</span><span class="GoogleBlue">g</span><span class="GoogleGreen">l</span><span class="GoogleRed">e</span--></a></li>
                <li style="border-bottom:1px solid #ddd;"><a href="#" data-rel="back" id="manageDropbox"><img src="public/img/dropbox.png">Dropbox</a></li>
                <li style="border-top:15px solid rgba(255,255,255,0.8);"><a href="#" onclick="accountController.logout();" id="logoutlink" data-rel="back"><img src="public/img/logout.png">Log out</a></li>

            </ul>
        </div>
    </form>
</div>

<!--div data-role="footer" id="footer" data-position="fixed">
    <h4>Footer content</h4>
</div>< /footer -->


<img src="public/img/background.jpg" id="backgroundImage">

</div>


<!-- /dailymotion -->
<script src="http://api.dmcdn.net/all.js"></script>
<!-- /page -->


<script type="text/javascript" src="public/js/libs/jquery-1.11.0.js"></script>

<script type="text/javascript" src="public/js/preload.js"></script>



<script type="text/javascript" src="public/cordova.js"></script>

<!-- Libraries -->
<!-- AngularJS -->
<script type="text/javascript" src="public/js/libs/angular.js"></script>

<!-- jQuery -->
<script type="text/javascript" src="public/js/libs/jquery-ui.js"></script>
<script type="text/javascript" src="public/js/libs/jqm/jquery.mobile-1.4.2.js"></script>

<script type="text/javascript" src="public/js/libs/jquery.ui.touch-punch.js"></script>
<script type="text/javascript" src="public/js/libs/jquery.simulate.js"></script>

<!-- iScroll -->
<script type="text/javascript" src="public/js/libs/iscroll-zoom.js"></script>

<!--Fastclick-->

<script type="text/javascript" src="public/js/libs/fastclick.js"></script>

<!-- mediaplayerjs -->

<script type="text/javascript" src="public/js/libs/mediaelement/mediaelement-and-player.js"></script>
<script type="text/javascript" src="public/js/libs/mediaelement/playlist/mep-feature-playlist.js"></script>


<!-- mediaplayerjs -->
<script type="text/javascript" src="public/js/libs/chosen/chosen.jquery.js"></script>

<!-- hammerjs -->
<script type="text/javascript" src="public/js/libs/hammer.js"></script>

<!-- cipher -->
<script type="text/javascript" src="public/js/libs/rsa/prng4.js"></script>
<script type="text/javascript" src="public/js/libs/rsa/rng.js"></script>
<script type="text/javascript" src="public/js/libs/rsa/jsbn.js"></script>
<script type="text/javascript" src="public/js/libs/rsa/jsbn2.js"></script>
<script type="text/javascript" src="public/js/libs/rsa/rsa.js"></script>
<script type="text/javascript" src="public/js/libs/rsa/rsa2.js"></script>
<script type="text/javascript" src="public/js/encoder.js"></script>
<!-- cipher -->


<!-- helperFunctions -->
<script type="text/javascript" src="public/js/helperFunctions.js"></script>

<!-- business logic -->
<script type="text/javascript" src="public/js/playbackController.js"></script>

<script type="text/javascript" src="public/js/uiController.js"></script>

<script type="text/javascript" src="public/js/mediaelementPlayer.js"></script>
<script type="text/javascript" src="public/js/dailymotionPlayers.js"></script>

<script type="text/javascript" src="public/js/videoController.js"></script>
<script type="text/javascript" src="public/js/videoPlayer.js"></script>


<script type="text/javascript" src="public/js/mediaController.js"></script>

<script type="text/javascript" src="public/js/searchController.js"></script>

<script type="text/javascript" src="public/js/playlistController.js"></script>

<script type="text/javascript" src="public/js/accountController.js"></script>

<script type="text/javascript" src="public/js/authController.js"></script>

<script type="text/javascript" src="public/js/importController.js"></script>

<!-- ng Controlle -->
<script type="text/javascript" src="public/js/mainModule.js"></script>

<script type="text/javascript" src="public/js/mainController.js"></script>

<!-- Include Business Logic and Start App -->
<script type="text/javascript" src="public/js/index.js"></script>
<script type="text/javascript">
    app.initialize();
</script>


<!--Google-->
<script type="text/javascript" src="public/js/googleHandler.js"></script>
<!--Facebook-->
<script type="text/javascript" src="public/js/facebookHandler.js"></script>



<!-- The Google API Loader script. -->
<script type="text/javascript" src="https://apis.google.com/js/api.js?onload=onApiLoad"></script>
<script src="https://apis.google.com/js/client.js?onload=loadClient"></script>

<!--Embed-->
<script type="text/javascript" src="public/js/dailymotionPlayers.js"></script>


<!-- Preload Images/WebGL-->
<div style="display:none">
    <img src="public/img/play.png">
    <img src="public/img/playloading.png">
    <img src="public/img/pause.png">
    <img src="public/img/crosswhite.png">
    <img src="public/img/save.png">
    <img src="public/img/plus.png">
    <img src="public/img/loader.gif">
    <img src="public/img/loadertitle.gif">
    <img src="public/img/cross.png">
    <img src="public/img/loader/sprites.png">

    <canvas id="webglcanvas"></canvas>
</div>



</body>
</html>