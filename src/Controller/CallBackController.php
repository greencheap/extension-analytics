<?php 
namespace GreenCheap\Metrica\Controller;

use GreenCheap\Application as App;
use Symfony\Component\HttpFoundation\Request;

class CallBackController
{
    /**
     * @Access(admin=true)
     */
    public function callbackAction(Request $request)
    {
        $logo = 'app/system/assets/images/logo/logo-white.svg';
        return App::response(App::view('metrica:views/callback.php' , compact('logo')));
    }
}
?>