<?php

// Verbindung zur Datenbank herstellen
$conn = new mysqli("localhost", "root", "", "ticketsystem");

// Überprüfen der Verbindung
if ($conn->connect_error) {
  die("Verbindung fehlgeschlagen: " . $conn->connect_error);
}

if(isset($_POST['submit'])) {
  // Überprüfen, ob alle Felder ausgefüllt sind
  if(!empty($_POST['name']) && !empty($_POST['iban']) && !empty($_POST['direction'])) {
      $name = $_POST['name'];
      $iban = $_POST['iban'];
      $direction = $_POST['direction'];

     // Validierung des Namens: Überprüfen auf Sonderzeichen oder Zahlen
     if(preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $name) || preg_match('/[0-9]/', $name)) {
      echo "Ungültiger Name! Bitte verwenden Sie nur Buchstaben.";
  } else {
    // Überprüfen, ob der Benutzer bereits existiert
    $checkUser = "SELECT * FROM Benutzer WHERE name='$name' AND mail='$iban'";
    $result = $conn->query($checkUser);

    if ($result->num_rows > 0) {
        echo "Benutzer mit diesen Daten existiert bereits!";
    } else {
      // Daten in die Benutzer-Tabelle einfügen
      $insertUser = "INSERT INTO Benutzer (name, mail) VALUES ('$name', '')"; // Hier könnte die E-Mail hinzugefügt werden, falls verfügbar
      $conn->query($insertUser);
      $userId = $conn->insert_id; // ID des eingefügten Benutzers erhalten

      // Daten in die Ticket-Tabelle einfügen
      $insertTicket = "INSERT INTO Ticket (titel, beschreibung, status) VALUES ('Ticket #$userId: Karte nach $direction', 'Beschreibung des Tickets', 'In Bearbeitung')";
      $conn->query($insertTicket);
      $ticketId = $conn->insert_id; // ID des eingefügten Tickets erhalten

      // Daten in die Zuweisung-Tabelle einfügen
      $insertAssignment = "INSERT INTO Zuweisung (ticket_id, user_id) VALUES ('$ticketId', '$userId')";
      $conn->query($insertAssignment);

      // Erfolgsmeldung mit zusätzlichen Informationen
        echo "<div class='ticket'>";
        echo "<h1>Fahrplan</h1>";
        echo "<h2>Ticket #$ticketId: Karte nach $direction</h2>";
        echo "<p>Zugewiesen an: $name</p>";
        echo "<p>Status: In Bearbeitung</p>";
        echo "<p>Beschreibung: $direction</p>";
        echo "<form method='post' action='index.php'>";
                echo "<input type='hidden' name='ticket_id' value='$ticketId'>";
                echo "<button type='submit' name='edit'>Ticket bearbeiten</button>";
                echo "<button type='submit' name='delete'>Ticket löschen</button>";
        echo "</div>";
    }
  }
      } else {
          echo "Bitte füllen Sie alle Felder aus!";
  }
}

// Ticket bearbeiten
if (isset($_POST['edit'])) {
  $ticketId = $_POST['ticket_id'];
  // Hier kommt die Logik für das Bearbeiten des Tickets
  $updateQuery = "UPDATE Ticket SET status='Bearbeitet' WHERE id='$ticketId'";
  $conn->query($updateQuery);
  echo "Ticket bearbeitet!";
}

// Ticket löschen
if (isset($_POST['delete'])) {
  $ticketId = $_POST['ticket_id'];
  // Hier kommt die Logik für das Löschen des Tickets
  $deleteQuery = "DELETE FROM Ticket WHERE id='$ticketId'";
  $conn->query($deleteQuery);
  echo "Ticket gelöscht!";
}

// Verbindung zur Datenbank schließen
$conn->close();

?>