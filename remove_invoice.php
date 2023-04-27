<?php

require 'dbconnection.php';

try {

$dbcon = openDB(); //avataan tietokantayhteys

$invoice_id = 7; //määritetään jokin ID:n arvo

$sql = "DELETE FROM invoice_items 
        WHERE InvoiceId = ?"; //sql lause, joka poistaa rivin, jossa InvoiceId on sama kuin määritetty arvo
        
$statement = $dbcon->prepare($sql); //valmistellaan sql lause
$statement->bindParam(1, $invoice_id, PDO::PARAM_INT); //sidotaan parametri
$statement->execute(); //suoritetaan sql lause

echo "Invoice item(s) deleted successfully."; //onnistunut poisto
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage(); //virheilmoitus
}


