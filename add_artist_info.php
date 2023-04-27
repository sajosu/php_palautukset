<?php

require 'dbconnection.php';

try {
    $dbcon = openDB();
    $dbcon->beginTransaction();

    // Lisätään uusi artisti
    $artist_name = 'Connie Francis';
    $sql = "INSERT INTO artists (Name) VALUES (?)";
    $stmt = $dbcon->prepare($sql);
    $stmt->execute([$artist_name]);
    $artist_id = $dbcon->lastInsertId();

    // Lisätään uusi albumi
    $album_title = 'Best of Connie';
    $sql = "INSERT INTO albums (ArtistId, Title) VALUES (?, ?)";
    $stmt = $dbcon->prepare($sql);
    $stmt->execute([$artist_id, $album_title]);
    $album_id = $dbcon->lastInsertId();

    // Lisätään kappaleet albumiin. Millisekunnit ja bitit on nyt vaan kaikilla sama arvot ja säveltäjä on artistin nimi.
    $tracks = array('Lipstick on Your Collar', 'Where the Boys Are', 'Stupid Cupid', "Who's Sorry Now");
    foreach ($tracks as $track) {
        $sql = "INSERT INTO tracks (Name, AlbumId,  MediaTypeId, GenreId, Composer, Milliseconds, Bytes, UnitPrice ) VALUES (?, ?, 1, 1, ?, 99991, 11223344, 0.99)";
        $stmt = $dbcon->prepare($sql);
        $stmt->execute([$track, $album_id, $artist_name]);
    }

    $dbcon->commit();

    echo "Artist, album and tracks added successfully";

} catch (PDOException $e) {
    $dbcon->rollback();
    echo "Error: " . $e->getMessage();
}

