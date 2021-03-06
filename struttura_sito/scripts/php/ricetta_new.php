<?php
session_start();

require_once(__DIR__ . "/../../scripts/php/database.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $db_connection = db_connect();

  if (name_ricetta_exists()) {
    exit_insert("ERRORE: Nome ricetta già presente");
  }
  insert_ricetta();
  exit_insert("Ricetta Aggiunta");
}



// Verifica se esiste una ricetta con lo stesso nome
function name_ricetta_exists() {
  $nome=trim($_POST["nome"]);
  $query_name = $GLOBALS["db_connection"]->prepare("SELECT nome FROM ricette WHERE nome=?"); 
  $query_name->bind_param("s", $nome);
  $query_name->execute();
  $row = $query_name->get_result()->fetch_assoc();
  if($row) {
    return true; 
  }
  return false;
}

// Inserisce la ricetta nel database
function insert_ricetta() {
  if(isset($_SESSION['usid'])) {
    $ricetta_autore = $_SESSION['usid'];
  } else {
    exit_insert("ERRORE: Effettuare login");
  }
  
  $insert_db = $GLOBALS["db_connection"]->prepare("INSERT INTO ricette (nome, vegetariana, tipo, ingredienti, informazioni, autore, nome_immagine)
  VALUES (?, ?, ?, ?, ?, ?, ?)");
  $insert_db->bind_param("sisssis", $ricetta_nome, $ricetta_vegetariana, $ricetta_tipo, $ricetta_ingredienti, $ricetta_informazioni, $ricetta_autore, $ricetta_nome_immagine);

  $ricetta_nome = trim($_POST["nome"]);
  $ricetta_vegetariana = FALSE;

  if (isset($_POST["vegetariana"])) {
    $ricetta_vegetariana = TRUE;
  }

  $ricetta_tipo = $_POST["tipo"];
  $ricetta_ingredienti = $_POST["ingredienti"];
  $ricetta_informazioni = $_POST["descrizione"];
  $ricetta_nome_immagine = "default.jpg";
  if ($_FILES['immagine']['name']) {
    $ext = pathinfo($_FILES['immagine']['name'], PATHINFO_EXTENSION);
    $ricetta_nome_immagine = strtolower(str_replace(" ", "_", $ricetta_nome)) . "." . $ext;
    if (!save_image($ricetta_nome_immagine)) {
      exit_insert("ERRORE: Errore nel caricamento dell'immagine");
    }
  }

  $insert_db->execute();
  $insert_db->close();
}

// Salva nella cartalle /img/ricette il file in POST con il nome richiesto dal parametro
function save_image($image_name) {
  return move_uploaded_file($_FILES['immagine']['tmp_name'], "../../imgs/ricette/" . $image_name);
}

// Termina l'inserimento ed imposta il messaggio di output da fornire all'utente
function exit_insert($exit_message) {
  $_SESSION['post_output'] = $exit_message;
  db_close($GLOBALS["db_connection"]);
  header('Location: ../../ricetta-aggiungi');
  exit();
}
?>