<?php

require 'dbconnection.php';

try {
    $dbcon = openDB();
    $artist_id = 7; // Apocalyptica
    $dbcon->beginTransaction(); // aloitetaan transaktio

    $sql = "SELECT artists.Name AS ArtistName, albums.Title AS AlbumTitle, tracks.Name AS TrackName 
            FROM albums
            JOIN artists ON albums.ArtistId = artists.ArtistId
            JOIN tracks ON albums.AlbumId = tracks.AlbumId
            WHERE albums.ArtistId = ?"; // haetaan albumit ja niiden kappaleet sekä artistin nimi id:n perusteella
    
    $statement = $dbcon->prepare($sql);
    $statement->bindParam(1, $artist_id, PDO::PARAM_INT);
    $statement->execute();
    
    $result = array();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) { // Haetaan rivit yksi kerrallaan
        $album_title = $row['AlbumTitle']; // Haetaan albumin nimi
        if (!isset($result[$album_title])) { // Jos albumia ei ole lisätty tuloksiin vielä, lisätään se tuloslistaan
            $album = array(
                'artist' => $row['ArtistName'], // Lisätään artistin nimi
                'album' => $album_title, // Lisätään albumin nimi
                'tracks' => array() // Lisätään tyhjä kappalelista
            );
            $result[$album_title] = $album; 
        }
        // Lisätään kappale tuloslistaan
        $result[$album_title]['tracks'][] = $row['TrackName'];
    }

    $json = json_encode(array_values($result));
    header("Content-Type: application/json");
    echo $json; // Tulostetaan json muodossa

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
