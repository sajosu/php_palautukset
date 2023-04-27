<?php

require 'dbconnection.php';

$dbcon = openDB();

try {

    $artist_id = 24; //Marcos Valle

    $dbcon->beginTransaction(); //aloitetaan transaktio

     //poista kaikki invoice_itemsin liittyen artistin kappaleisiin
     $statement1 = $dbcon->prepare("DELETE FROM invoice_items WHERE TrackId IN (SELECT TrackId FROM tracks WHERE AlbumId IN (SELECT AlbumId FROM albums WHERE ArtistId = ?))");
     $statement1->execute([$artist_id]);

    //poista kaikki playlistit, joissa on artistin kappaleita
    $statement2 = $dbcon->prepare("DELETE FROM playlist_track WHERE TrackId IN (SELECT TrackId FROM tracks WHERE AlbumId IN (SELECT AlbumId FROM albums WHERE ArtistId = ?))");
    $statement2->execute([$artist_id]);

    //poista kaikki artistin kappaleet
    $statement3 = $dbcon->prepare("DELETE FROM tracks WHERE AlbumId IN (SELECT AlbumId FROM albums WHERE ArtistId = ?)");
    $statement3->execute([$artist_id]);

    //poista kaikki artistin albumit
    $statement4 = $dbcon->prepare("DELETE FROM albums WHERE ArtistId = ?");
    $statement4->execute([$artist_id]);

    //poista artisti
    $statement5 = $dbcon->prepare("DELETE FROM artists WHERE ArtistId = ?");
    $statement5->execute([$artist_id]);

    $dbcon->commit(); //suorita transaktio

    echo "Artist deleted successfully."; //onnistunut poisto

} catch (Exception $e) {
    $dbcon->rollBack(); //rollback
    echo "Error: " . $e->getMessage(); //virheilmoitus
}
