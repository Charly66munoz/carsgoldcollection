<?php 

namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LuckyController


{
     #[Route('/lucky/number')]
    public function number(): Response
    {
        $number = rand(0,100);


        return new Response(
            '<html><body>Lucky number: '. $number.'</body></html>'
        );
    }


}