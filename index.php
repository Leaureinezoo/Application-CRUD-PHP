<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>ATELIER CRUD</title>
</head>
<body>
    <h1 id="titre">ATELIER CRUD</h1>
    <?php
        try
        {
            $servername = 'localhost';
            $username = 'root';
            $password = '';
        
            $conn = new PDO("mysql:host=$servername;dbname=gsb_bdd_pharma", $username, $password);
        }
        catch(PDOException $e)
        {
            print "Erreur : " . $e->getMessage() . "<br/>";
        }
    ?>

    <div id="divAll">
    <p>Choississez une famille de médicaments :</p>
    <form action="index.php" id="formListe" method="POST">
        <select name="ListeFamille" id="ListeFamille" size="20">
            <?php
                $requete = "SELECT * FROM famille;";
                foreach  ($conn->query($requete) as $row) {
                    print '<option value="' . $row['id'] . '">' . $row['libelle'] . '</option>';
                }
            ?>
        </select>
        <br>
        <input type="submit" value="Voir les médicaments" name="ButtonFamille" id="Button1" onClick="document.location.href='TraitFamille.php'">
        <br>
        <input type="submit" value="Ajouter un medicament dans cette famille" name ="ButtonAjouterMedicament" id="Button2">
    </form>

    <?php
    if (isset($_POST['ButtonFamille']) && isset($_POST['ListeFamille'])){
        $_SESSION['ListeFamille'] = $_POST['ListeFamille']
    ?>
    <form action="index.php" id="formMedicament" method="POST">
        <select name="ListeMedicament" id="ListeMed" size="5">
            <?php
            $requete = "SELECT M.* FROM medicament M JOIN famille F ON F.id = M.idFamille WHERE F.id = '" . $_POST['ListeFamille'] . "';";
            foreach  ($conn->query($requete) as $row) {
                echo '<option value="' . $row['id'] . '">' . $row['nomCommercial'] . '</option>';
            }
            ?>
        </select>
        <input type="submit" value="Voir les détails de ce médicament" name="ButtonMedicamentDetails" id="Button3" onClick="document.location.href='TraitMedicament.php'">
        <input type="submit" value="Modifier ce médicament" name="ButtonMedicamentUpdate" id="Button4">
        <input type="submit" value="Supprimer ce médicament" name="ButtonMedicamentSupprimer" id="Button5">
    </form>
    <?php
        }
    if (isset($_POST['ButtonMedicamentDetails']) && isset($_SESSION['ListeFamille'])){
        $requete = "SELECT * FROM medicament WHERE id = '" . $_POST['ListeMedicament'] . "';";
        foreach  ($conn->query($requete) as $row) {
            print '<label>Id : </label>';
            print '<input type="text" value="' . $row['id'] . '" name="idMed" size="52">';
            print '<br>';
            print '<label>Nom commercial: </label>';
            print '<input type="text" value="' . $row['nomCommercial'] . '" name="nomComMed" size="38">';
            print '<br>';
            print '<label>Id de la famille : </label>';
            print '<input type="text" value="' . $row['idFamille'] . '" name="idFamilleMed" size="40">';
            print '<br>'; 
            print '<label>Composition : </label>';
            print '<input type="text" value="' . $row['composition'] . '" name="compoMed" size="42">';
            print '<br>';
            print '<label>Effets : </label>';
            print '<input type="text" value="' . $row['effets'] . '" name="effetsMed" size="49" cols="2">';
            print '<br>';
            print '<label>Contre indications : </label>';
            print '<input type="text" value="' . $row['contreIndications'] . '" size="37" name="contreindicationMed">';
        }
    }

    if (isset($_POST['ButtonAjouterMedicament']) && isset($_POST['ListeFamille'])){
    ?>
        <form action="index.php" method="POST">
            <?php
            print '<label>Id : </label>';
            print '<input type="text" name="idMedAdd" size="52">';
            print '<br>';
            print '<label>Nom commercial: </label>';
            print '<input type="text" name="nomComMedAdd" size="38">';
            print '<br>';
            print '<label>Id de la famille : </label>';
            print '<input type="text" name="idFamilleMedAdd" size="40" value="' . $_POST['ListeFamille'] . '">'; 
            print '<br>';
            print '<label>Composition : </label>';
            print '<input type="text" name="compoMedAdd" size="42">';
            print '<br>';
            print '<label>Effets : </label>';
            print '<input type="text" name="effetsMedAdd" size="49">';
            print '<br>';
            print '<label>Contre indications : </label>';
            print '<input type="text" name="contreindicationMedAdd" size="37">';
            ?>
            <br>
            <input type="submit" value="Ajouter ce nouveau médicament" id="Button5" name="ButtonAddMed">
        </form>
    <?php
    }

    if(isset($_POST['ButtonAddMed']) && isset($_POST['idMedAdd']) && isset($_POST['nomComMedAdd']) && isset($_POST['idFamilleMedAdd']) && isset($_POST['compoMedAdd']) && isset($_POST['effetsMedAdd']) && isset($_POST['contreindicationMedAdd'])){
        $requete = "INSERT INTO medicament(id, nomCommercial, idFamille, composition, effets, contreIndications) VALUES ('" . $_POST['idMedAdd'] . "', '" . $_POST['nomComMedAdd'] . "', '" . $_POST['idFamilleMedAdd'] . "', '" . $_POST['compoMedAdd'] . "', '" . $_POST['effetsMedAdd'] . "', '" . $_POST['contreindicationMedAdd'] . "')";
        try
        {
            $conn->exec($requete);
            $requete = "SELECT libelle FROM famille WHERE id = '" . $_POST['idFamilleMedAdd'] . "'";
            foreach  ($conn->query($requete) as $row) {
                print '<p>Médicement ' . $_POST['nomComMedAdd'] . ' ajouté à la famille ' . $row['libelle'] . '</p>';
            }
        }
        catch(PDOException $ex)
        {
            print " Erreur : " . $ex->getMessage() . "<br/>";
        }
    }

    if (isset($_POST['ListeMedicament']) && isset($_POST['ButtonMedicamentSupprimer'])){
        $requete = "DELETE FROM medicament WHERE id = '" . $_POST['ListeMedicament'] . "'";
        try
        {
            $conn->exec($requete);
            print "<p>Suppression du médicament " . $_POST['ListeMedicament'] . " effectué</p>";
        }
        catch(PDOException $exe)
        {
            print "Erreur : " . $e->getMessage() . "<br/>";
        }
    }

    if (isset($_POST['ButtonMedicamentUpdate']) && isset($_SESSION['ListeFamille']) && isset($_POST['ListeMedicament'])){
        $requete = "SELECT * FROM medicament WHERE id = '" . $_POST['ListeMedicament'] . "';";
        $_SESSION['ListeMedicament'] = $_POST['ListeMedicament']
        ?>
        <form action="index.php" method="POST">
            <?php
                foreach  ($conn->query($requete) as $row) {
                    print '<label>Id : </label>';
                    print '<input type="text" value="' . $row['id'] . '" name="idMed2" size="52">';
                    print '<br>';
                    print '<label>Nom commercial: </label>';
                    print '<input type="text" value="' . $row['nomCommercial'] . '" name="nomComMed2" size="38">';
                    print '<br>';
                    print '<label>Id de la famille : </label>';
                    print '<input type="text" value="' . $row['idFamille'] . '" name="idFamilleMed2" size="40">';
                    print '<br>'; 
                    print '<label>Composition : </label>';
                    print '<input type="text" value="' . $row['composition'] . '" name="compoMed2" size="42">';
                    print '<br>';
                    print '<label>Effets : </label>';
                    print '<input type="text" value="' . $row['effets'] . '" name="effetsMed2" size="49">';
                    print '<br>';
                    print '<label>Contre indications : </label>';
                    print '<input type="text" value="' . $row['contreIndications'] . '" size="37" name="contreindicationMed2">';
            ?>
            <br>
            <input type="submit" name="ButtonModMed" value="Modifier ce médicament" id="Button6">
        </form>
        <?php
        }
    }

    if (isset($_POST['ButtonModMed']) && isset($_POST['idMed2']) && isset($_POST['nomComMed2']) && isset($_POST['idFamilleMed2']) && isset($_POST['compoMed2']) && isset($_POST['effetsMed2']) && isset($_POST['contreindicationMed2'])){
        $requete = "UPDATE medicament SET id = '" . $_POST['idMed2'] . "', nomCommercial = '" . $_POST['nomComMed2'] . "', idFamille = '" . $_POST['idFamilleMed2'] . "', composition = '" . $_POST['compoMed2'] . "', effets = '" . $_POST['effetsMed2'] . "', contreIndications = '" . $_POST['contreindicationMed2'] . "' WHERE id = '" . $_SESSION['ListeMedicament'] . "'";
        try
        {
            $conn->exec($requete);
            print "<p>Modification du médicament " . $_SESSION['ListeMedicament'] . " effectué</p>";
        }
        catch(PDOException $ex)
        {
            print " Erreur : " . $ex->getMessage() . "<br/>";
        }
    }
    ?>
    </div>
</body>
</html>