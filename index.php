<?php
require_once 'db.php';

$mensagem = '';
$nomeUtenteInserito = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $cognome = trim($_POST['cognome'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if (!empty($nome) && !empty($cognome) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO utenti (nome, cognome, email) VALUES (?, ?, ?)");
            $stmt->execute([$nome, $cognome, $email]);
            $nomeUtenteInserito = $nome;
        } catch (PDOException $e) {
            $mensagem = "Errore durante l'inserimento: Email già registrata o errore del database.";
        }
    } else {
        $mensagem = "Per favore, compila tutti i campi correttamente.";
    }
}

$stmt = $pdo->query("SELECT * FROM utenti ORDER BY id DESC");
$utenti = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Gestione Utenti</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; background-color: #f4f4f9; color: #333; }
        .container { max-width: 600px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .welcome-box { background-color: #e7f3fe; border-left: 6px solid #2196F3; padding: 15px; margin-bottom: 20px; border-radius: 4px; }
        .user-welcome { background-color: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 12px; margin-bottom: 20px; border-radius: 4px; }
        .form-group { margin-bottom: 12px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], input[type="email"] { width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; }
        button { background-color: #007bff; color: white; border: none; padding: 10px 15px; border-radius: 4px; cursor: pointer; width: 100%; }
        button:hover { background-color: #0056b3; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f8f9fa; }
        .error { color: red; margin-bottom: 10px; }
    </style>
</head>
<body>

<div class="container">
    <div class="welcome-box">
        <h3>Benvenuto alla prova tecnica!</h3>
        <p>Sono <strong>Thiago Abreu</strong> e spero che questo progetto ti piaccia!</p>
    </div>

    <?php if (!empty($nomeUtenteInserito)): ?>
        <div class="user-welcome">
            Benvenuto/a, <strong><?= htmlspecialchars($nomeUtenteInserito) ?></strong>! Il tuo profilo è stato registrato con successo.
        </div>
    <?php endif; ?>

    <h2>Aggiungi Nuovo Utente</h2>
    
    <?php if ($mensagem): ?>
        <p class="error"><?= htmlspecialchars($mensagem) ?></p>
    <?php endif; ?>

    <form id="userForm" action="index.php" method="POST" onsubmit="return validaFormulario()">
        <div class="form-group">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome">
        </div>
        <div class="form-group">
            <label for="cognome">Cognome:</label>
            <input type="text" id="cognome" name="cognome">
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email">
        </div>
        <button type="submit">Salva Utente</button>
    </form>

    <hr style="margin: 30px 0;">

    <h2>Elenco Utenti</h2>
    
    <div class="form-group">
        <label for="search">Cerca:</label>
        <input type="text" id="search" placeholder="Filtra per nome o cognome..." onkeyup="filtraUtenti()">
    </div>

    <table id="utentiTable">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Cognome</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($utenti as $u): ?>
                <tr>
                    <td class="nome-td"><?= htmlspecialchars($u['nome']) ?></td>
                    <td class="cognome-td"><?= htmlspecialchars($u['cognome']) ?></td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
    function validaFormulario() {
        const nome = document.getElementById('nome').value.trim();
        const cognome = document.getElementById('cognome').value.trim();
        const email = document.getElementById('email').value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (nome === '' || cognome === '' || email === '') {
            alert('Tutti i campi sono obbligatori.');
            return false;
        }

        if (!emailRegex.test(email)) {
            alert('Inserisci un indirizzo email valido.');
            return false;
        }

        return true;
    }

    function filtraUtenti() {
        const input = document.getElementById('search').value.toLowerCase();
        const table = document.getElementById('utentiTable');
        const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

        for (let i = 0; i < rows.length; i++) {
            const nomeTd = rows[i].querySelector('.nome-td').textContent.toLowerCase();
            const cognomeTd = rows[i].querySelector('.cognome-td').textContent.toLowerCase();

            if (nomeTd.includes(input) || cognomeTd.includes(input)) {
                rows[i].style.display = '';
            } else {
                rows[i].style.display = 'none';
            }
        }
    }
</script>

</body>
</html>