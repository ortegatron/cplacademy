<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Image_Graph - PEAR PHP OO Graph Rendering Utility.
 *
 * PHP versions 4 and 5
 *
 * LICENSE: This library is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation; either version 2.1 of the License, or (at your
 * option) any later version. This library is distributed in the hope that it
 * will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty
 * of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser
 * General Public License for more details. You should have received a copy of
 * the GNU Lesser General Public License along with this library; if not, write
 * to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 * 02111-1307 USA
 *
 * @category   Images
 * @package    Image_Graph
 * @subpackage Line
 * @author     Jesper Veggerby <pear.nosey@veggerby.dk>
 * @copyright  Copyright (C) 2003, 2004 Jesper Veggerby Hansen
 * @license    http://www.gnu.org/copyleft/lesser.html  LGPL License 2.1
 * @version    CVS: $Id: Dotted.php,v 1.6 2005/08/24 20:35:52 nosey Exp $
 * @link       http://pear.php.net/package/Image_Graph
 */

/**
 * Include file Image/Graph/Line/Formatted.php
 */
require_once 'Formatted.php';

/**
 * Dotted line style.
 *
 * This style displays as a short line with a shorter space afterwards, i.e
 * 1px color1, 1px color2, 1px color1, etc.
 *
 * @category   Images
 * @package    Image_Graph
 * @subpackage Line
 * @author     Jesper Veggerby <pear.nosey@veggerby.dk>
 * @copyright  Copyright (C) 2003, 2004 Jesper Veggerby Hansen
 * @license    http://www.gnu.org/copyleft/lesser.html  LGPL License 2.1
 * @version    Release: @package_version@
 * @link       http://pear.php.net/package/Image_Graph
 */
class Image_Graph_Line_Dotted extends Image_Graph_Line_Formatted
{

    /**
     * DottedLine [Constructor]
     *
     * @param mixed $color1 The color representing the dots
     * @param mixed $color2 The color representing the spaces
     */
    function Image_Graph_Line_Dotted($color1, $color2)
    {
        parent::Image_Graph_Line_Formatted(array ($color1, $color2));
    }

}

?>