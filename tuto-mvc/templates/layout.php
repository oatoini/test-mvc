<?php
/* Le layout va contenir le squelette commun à l'ensemble des pages web. Il contient les balises de
premier niveau (html, head et body). C'est au sein du layout que vont venir se greffer les pages web.*/
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php if (isset($data['title'])) echo $data['title']; ?> - Plugo</title>
    <?php if (isset($data['description'])) { ?>
        <meta name="description" content="<?= $data['description'] ?>">
    <?php } ?>
</head>
<body>
<?php include '_navbar.php'; ?>
<main>
    <?php require $templatePath ?>
</main>
<?php include '_footer.php'; ?>
</body>
</html>