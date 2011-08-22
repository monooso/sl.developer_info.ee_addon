<?php if ( ! defined('EXT')) exit('Invalid file request');

/**
 * SL Developer Info module file.
 *
 * @author              Stephen Lewis (http://experienceinternet.co.uk)
 * @author              Marc Miller (http://bigoceanstudion.com)
 * @copyright           Experience Internet
 * @link                http://experienceinternet.co.uk/software/sl-developer-info/
 * @package             Sl_developer_info
 */

class Sl_developer_info
{
    var $return_data = '';


    /**
     * PHP4 constructor.
     * @see __construct
     */
    function Sl_developer_info()
    {
        $this->__construct();
    }


    /**
     * PHP5 constructor.
     */
    function __construct()
    {
        $this->return_data = '';
    }


}


/* End of file      : mod.sl_developer_info.php */
/* File location    : system/modules/sl_developer_info/mod.sl_developer_info.php */
