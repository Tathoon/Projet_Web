<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
</head>
<body class="body-login">
<svg id="wave" viewBox="0 0 1440 480" version="1.1" xmlns="http://www.w3.org/2000/svg">
    <defs>
        <linearGradient id="sw-gradient-0" x1="0" x2="0" y1="1" y2="0">
            <stop stop-color="rgba(255, 255, 255, 1)" offset="0%"></stop>
            <stop stop-color="rgba(255, 255, 255, 1)" offset="100%"></stop>
        </linearGradient>
    </defs>
    <path style="transform:translate(0, 0px); opacity:1" fill="url(#sw-gradient-0)" d="M0,0L40,16C80,32,160,64,240,104C320,144,400,192,480,184C560,
        176,640,112,720,120C800,128,880,208,960,224C1040,240,1120,192,1200,144C1280,96,1360,48,1440,72C1520,96,1600,192,1680,200C1760,208,1840,128,1920,
        80C2000,32,2080,16,2160,8C2240,0,2320,0,2400,16C2480,32,2560,64,2640,96C2720,128,2800,160,2880,168C2960,176,3040,160,3120,128C3200,96,3280,48,3360,
        32C3440,16,3520,32,3600,80C3680,128,3760,208,3840,248C3920,288,4000,288,4080,312C4160,336,4240,384,4320,384C4400,384,4480,336,4560,280C4640,224,4720,
        160,4800,144C4880,128,4960,160,5040,200C5120,240,5200,288,5280,256C5360,224,5440,112,5520,64C5600,16,5680,32,5720,40L5760,
        48L5760,480L5720,480C5680,480,5600,480,5520,480C5440,480,5360,480,5280,480C5200,480,5120,480,5040,480C4960,480,4880,480,4800,480C4720,480,
        4640,480,4560,480C4480,480,4400,480,4320,480C4240,480,4160,480,4080,480C4000,480,3920,480,3840,480C3760,480,3680,480,3600,480C3520,480,3440,
        480,3360,480C3280,480,3200,480,3120,480C3040,480,2960,480,2880,480C2800,480,2720,480,2640,480C2560,480,2480,480,2400,480C2320,480,2240,480,
        160,480C2080,480,2000,480,1920,480C1840,480,1760,480,1680,480C1600,480,1520,480,1440,480C1360,480,1280,480,1200,480C1120,480,1040,480,960,
        480C880,480,800,480,720,480C640,480,560,480,480,480C400,480,320,480,240,480C160,480,80,480,40,480L0,480Z">
    </path>
</svg>
<img src="images/Logo-Web.png" alt="Logo" class="img-login">
    <form action="#" method="post">
        <div class="form-group">
            <label for="email" hidden></label>
            <input type="email" id="email" name="email" class="placeholder-white" placeholder="&#xf2c0;   E-mail" required style="font-family:Arial, FontAwesome"> 
        </div>
        <div class="form-group">
            <label for="password" hidden></label> 
            <input type="password" id="password" name="password" class="placeholder-white" placeholder="&#xf023;    Mot de passe" required style="font-family:Arial, FontAwesome">
        </div>
        <div class="form-group">
            <input type="submit" value="LOGIN">
        </div>
        <?php
            session_start();

            echo ucfirst($_SESSION['nom']);

            if (isset($_POST['email']) && isset($_POST['password'])) {
            $usermail = $_POST['email'];
            $userpasswd = $_POST['password'];

                $db = new PDO('mysql:host=localhost;dbname=e11event_bdd;charset=utf8mb4', 'root', '');

                $query = $db->query('SELECT * FROM utilisateur');
                $data = $query->fetchAll();

                $success = false;
                $userRole = null;

                foreach ($data as $row) {
                    if ($row['mail'] == $usermail && $row['mdp'] == $userpasswd) {
                        $success = true;
                        $userRole = $row['role'];
                        $_SESSION['role'] = $row['role'];
                        $_SESSION['nom'] = $row['nom'];
                        break;
                    }
                }

                if ($success && $userRole){

                    switch ($userRole) {
                        case 1: 
                            header('Location: pages/admin/dashboard_admin.php');
                            break;
                        case 2: 
                            header('Location: pages/commercial/tickets_commercial.php');
                            break;
                        case 3:
                            header('Location: pages/comptable/dashboard_comptable.php');
                            break;
                        default:
                            break;
                    }
                } else {
                    echo '<br><div class="error-alert" role="alert" style="color:white;">
                        <strong>Erreur</strong> le mail ou le mot de passe est inccorect.
                    </div>';
                }
            }
        ?>
    </form>
    <div class="target">
        <div class="center"></div>
        <div class="outer-circle"></div>
        <div class="inner-circle"></div>
    </div>
    <script type="text/javascript" src="index.js"></script>
</body>
</html>
