<?PHP

namespace Core\component;

use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use Symfony\Component\HttpFoundation\Session\SessionInterface;




class AbstractController
{
    protected $twig;

    public function __construct()
    {
        $loader = new FilesystemLoader(TEMPLATE_DIR . '//');
        $this->twig = new Environment($loader, ["debug" => true]);
    }

    public function render($template, array $datas = [])
    {
        $this->twig->addGlobal('session', $_SESSION);
        echo $this->twig->render($template, $datas);
         $this->getFlash();   //pour unset le message apres affichage

    }
    public function redirect($url)
    {
        header("Location:" . $url);
        exit();
    }
    public function newSession()
    {
        //verifie si une session existe déjà ou non
        if (session_status() === PHP_SESSION_NONE) {
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
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
    }
    public function isSubmitted($submitButton)
    {
        if (isset($_POST[$submitButton])) {

            return true;
        }

        return false;
    }
    public function isValided($inputFields)
    {
        $isvalid = true;
        foreach ($inputFields as $input) {
            if ($input === null || $input === "" || !isset($input)) {
                $isvalid = false;
            }
        }

        return $isvalid;
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
      
        if (isset($_SESSION) && isset($_SESSION['flash'])) {
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
}
