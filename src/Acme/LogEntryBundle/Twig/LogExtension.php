<?php

namespace Acme\LogEntryBundle\Twig;

class LogExtension extends \Twig_Extension
{

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('a2s',
                array(
                    $this,
                    'a2s'
                )
            )
        );
    }

    /*
     * Converts array Changeset to text string
     */
    public function a2s($array){
        if (is_array($array)) {
            $str = '';
            foreach($array as $key=>$value){
                if (is_array($value)){
                    $str .= $key . ' :' . $this->a2s($value);
                }
                else
                $str .= $key . ': ' . $value."<br />";
            }
            return $str;
        }
    }

    public function getName()
    {
        return 'log_extension';
    }

}
