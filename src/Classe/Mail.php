<?php
namespace  App\Classe;

use Mailjet\Client;
use Mailjet\Resources;


class Mail
{
    public function send($to_mail,$to_name,$subject,$template,$variables = null){

        //récupération de contenue
        $content = file_get_contents(dirname(__DIR__).'/Mail/'.$template.'.html');

        //Récuperer les variables facultatives

        if($variables){
            foreach ($variables as $key=>$variable) {
                $content = str_replace("{".$key."}","$variable",$content);
            }
        }
        $mj = new Client($_ENV['MJ_APIKEY_PUBLIC'], $_ENV['MJ_APIKEY_PRIVATE'],true,['version' => 'v3.1']);


        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "djelloulimohammed19@gmail.com",
                        'Name' => "La boutique Française"
                    ],
                    'To' => [
                        [
                            'Email' => $to_mail,
                            'Name' => $to_name
                        ]
                    ],
                    'TemplateID'=> 6711143,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    'Variables' => [
                        "content" => $content,
                    ]
                ]
            ]
        ];

        $mj->post(Resources::$Email, ['body' => $body]);

    }

}