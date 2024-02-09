<?PHP

namespace Core\component;

use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use Twig\Extension\DebugExtension;

class AbstractController
{
    protected $twig;

    public function __construct()
    {

        $loader = new FilesystemLoader(TEMPLATE_DIR . '/');
        $this->twig = new Environment($loader, ["debug" => true]);
        $this->twig->addExtension(new DebugExtension());
        }

    public function render($template, array $datas = [])
    {
        $datas['userLoggedIn'] = $this->isUserLoggedIn();
        $datas['flashMessages'] = $this->getFlash();
        $this->twig->addGlobal('session', $_SESSION);
        echo $this->twig->render($template, $datas);
        // $this->getFlash();   //pour unset le message apres affichage

    }
    public function redirect($url)
    {
        header("Location:" . $url);
        exit();
    }
    public function newSession()
    {
        //verifie si une session existe déjà ou non
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
            //$this->twig->addGlobal('session', $_SESSION);
        }
    }
    public function getSessionInfos(string $key)
    {
        if (array_key_exists($key, $_SESSION)) {
            return $_SESSION[$key];
        }
    }
    public function setSessionInfos(string $key, $value)
    {
        $this->newSession();
        $_SESSION[$key] = $value;
    }
    public function deleteSessionInfos(string $key)
    {
        $this->newSession();
        unset($_SESSION[$key]);
    }
    public function deleteSession()
    {
        // Vérifie si la session est active
        if (session_status() === PHP_SESSION_ACTIVE) {
            // Détruit la session
            session_destroy();
            $this->newSession();
        }
    }

    public function isSubmitted($submitButton)
    {
        if (isset($_POST[$submitButton])) {

            return true;
        }

        return false;
    }
    // public function isValided($inputFields)
    // {
    //     // Vérification de base : champs non nuls et non vides
    //     foreach ($inputFields as $key => $input) {
    //         if (empty($input)) {
    //             return false;
    //         }
    //         if (empty($inputFields['firstname']) || empty($inputFields['lastname']) || !filter_var($inputFields['mail'], FILTER_VALIDATE_EMAIL)) {
    //             return false;
    //         }
    //     }

    //     // Validation spécifique de l'email
    //     if (!filter_var($inputFields['mail'], FILTER_VALIDATE_EMAIL)) {
    //         return false;
    //     }

    //     // Validation conditionnelle des mots de passe, s'ils sont fournis
    //     // Assurez-vous que ces champs existent dans votre $inputFields avant de les valider ici
    //     if (isset($inputFields['new_password'], $inputFields['new_checkpassword'])) {
    //         if (empty($inputFields['new_password']) || empty($inputFields['new_checkpassword'])) {
    //             return false; // Les champs de mot de passe sont vides
    //         }

    //         if ($inputFields['new_password'] !== $inputFields['new_checkpassword']) {
    //             return false; // Les mots de passe ne correspondent pas
    //         }
    //     }

    //     return true;
    // }

    // public function isValided($inputFields) : bool
    //   { if (empty(trim($inputFields['firstname']))) {

    //     return false;
    // }

    // if (empty(trim($inputFields['lastname']))) {

    //     return false;
    // }

    // if (filter_var($inputFields['mail'], FILTER_VALIDATE_EMAIL) === FALSE) {

    //     return false;
    // }

    // if (!empty($inputFields['new_password']) && empty($inputFields['new_checkpassword'])) {

    //     return false;
    // } elseif (!empty($inputFields['new_password']) && $inputFields['new_password'] !== $inputFields['new_checkpassword']) {

    //     return false;
    // }

    // return true;

    //  }
    public function isValided($inputFields): bool
    {
        $isvalid = true;
        foreach ($inputFields as $input) {
            if ($input === null || $input === "") {
                $isvalid = false;
            }
        }

        return $isvalid;
    }
    public function isValidedProfil($inputFields)
    {
        // Validation des champs obligatoires
        $requiredFields = ['lastname', 'firstname', 'mail'];
        foreach ($requiredFields as $field) {
            if (empty($inputFields[$field])) {
                // Si un champ obligatoire est vide, retourner false
                return false;
            }
        }

        // Validation spécifique de l'email
        if (!filter_var($inputFields['mail'], FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        // Validation des champs de mot de passe si présents
        if (!empty($inputFields['new_password']) || !empty($inputFields['new_checkpassword'])) {
            if (empty($inputFields['new_password']) || empty($inputFields['new_checkpassword'])) {
                // Si un des champs de mot de passe est vide, retourner false
                return false;
            }
            if ($inputFields['new_password'] !== $inputFields['new_checkpassword']) {
                // Si les mots de passe ne correspondent pas, retourner false
                return false;
            }
        }

        return true;
    }

    //gestion des erreurs
    protected function addFlash($type, $message)
    {
        if (!isset($_SESSION['flash'])) {
            $_SESSION['flash'] = [];
        }
        $_SESSION['flash'][] = ['type' => $type, 'message' => $message];
    }

    protected function getFlash()
    {

        if (isset($_SESSION['flash'])) {
            $flashMessages = $_SESSION['flash'];
            unset($_SESSION['flash']);
            return $flashMessages;
        }
        return [];
    }
    //une fonction comme cela pour unset le flash
    protected function clearFlash()
    {
        if (isset($_SESSION['flash'])) {
            unset($_SESSION['flash']);
        }
    }
    public function isUserLoggedIn()
    {
        $this->newSession();
        return isset($_SESSION['mail']);
    }

    public function isAdmin()
    {
        // Vérifier que la variable de session 'role' est définie
        if (!isset($_SESSION['role'])) {
            return $this->redirect('/not-authorised');
        }

        // verifier que le user est bien en rôle admin
        if (!in_array('admin', $_SESSION['role'])) {
            return $this->redirect('/not-authorised');
        }
        return true;
    }
}
