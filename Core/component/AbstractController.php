<?PHP 
namespace Core\component;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

class AbstractController{
    protected $twig ;
    public function __construct() {
        $loader= new FilesystemLoader(TEMPLATE_DIR . '//');
        $this->twig= new Environment($loader, ["debug"=>true]);
    }

    public function render($template)  {
        echo $this->twig->render($template);
        return true;
    }
}