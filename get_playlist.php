<?php

require 'dbconnection.php';

try {

$dbcon = openDB();

$playlist_id=10; // TV shows :)

$sql = "SELECT TrackId 
        FROM playlist_track 
        WHERE PlaylistId = ?"; //haetaan ensin kaikki trackId:t, jotka kuuluvat playlistiin

$statement = $dbcon->prepare($sql);
$statement->bindParam(1, $playlist_id, PDO::PARAM_INT);
$statement->execute();

$json = json_encode($statement->fetchAll(PDO::FETCH_ASSOC));
header("Content-Type: application/json");
/* echo $json; */ //tulostetaan json muodossa, soittolistaan kuuluu TrackId:t 2819-3429

$trackMin = 2819;
$trackMax = 3429;

$sql2 = "SELECT Name,
                Composer
        FROM tracks 
        WHERE TrackId BETWEEN ? AND ?"; //haetaan kaikki kappaleet, jotka kuuluvat playlistiin

$statement2 = $dbcon->prepare($sql2);
$statement2->bindParam(1, $trackMin, PDO::PARAM_INT);
$statement2->bindParam(2, $trackMax, PDO::PARAM_INT);
$statement2->execute();

$songs = $statement2->fetchAll(PDO::FETCH_ASSOC);
header("Content-Type: text/html; charset=utf-8"); 

echo "<h2>". "Playlist" . "</h2>";

foreach ($songs as $song) { //tulostetaan kaikki kappaleet ja säveltäjät
    echo "<b>".  $song["Name"] . "</b>" . " <br> " . "( " . $song["Composer"] . " )" . "<br>"."<br>";
}

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage(); //virheilmoitus
}
