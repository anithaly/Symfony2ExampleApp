<?php

namespace Acme\LogEntryBundle\Twig;

class LogExtension extends \Twig_Extension
{

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('array2string',
                array(
                    $this,
                    'array2string'
                )
            )
        );
    }

    /*
     * Converts array Changeset to text string
     */
    public function array2string($array){
        if (is_array($array)) {
            $str = '';
            foreach($array as $key=>$value){
                if (is_array($value)){
                    $str .= $key . ' :' . $this->array2string($value);
                }
                else{
                    if ($value instanceof \DateTime) {
                        $str .= $key . ': ' . $value->format('d-m-Y H:i:s')."<br />";
                    } else {
                        $str .= $key . ': ' . $value."<br />";
                    }
                }
            }
            return $str;
        }
    }

    public function getName()
    {
        return 'log_extension';
    }

}
