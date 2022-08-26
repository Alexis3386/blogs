<?php
require_once('config.php');
require_once('vendor/autoload.php');

use App\User;
use App\Util;

if (
!(Util::validStringNotEmpty($_POST, 'email')
    && Util::validStringNotEmpty($_POST, 'password')
)
) {
    header('Location: /?p=connexion&champ_vide');
    exit();
}

$sql = "SELECT * FROM users where email = :email";
$query = $pdo->prepare($sql);
$query->bindParam(":email", $_POST['email'], PDO::PARAM_STR);
$query->execute();

$user = $query->fetch();

$user = new User($user['id'], $user['email'], $user['pseudo'], $user['username'], $user['isadmin'])

?>
<pre>
    <?php
    var_dump($user);
    ?>
</pre>
