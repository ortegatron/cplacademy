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
 * @subpackage Fill
 * @author     Jesper Veggerby <pear.nosey@veggerby.dk>
 * @copyright  Copyright (C) 2003, 2004 Jesper Veggerby Hansen
 * @license    http://www.gnu.org/copyleft/lesser.html  LGPL License 2.1
 * @version    CVS: $Id: Image.php,v 1.7 2005/08/24 20:36:03 nosey Exp $
 * @link       http://pear.php.net/package/Image_Graph
 */

/**
 * Include file Image/Graph/Fill.php
 */
require_once dirname(__FILE__).'../../Fill.php';

/**
 * Fill using an image.
 *
 * @category   Images
 * @package    Image_Graph
 * @subpackage Fill
 * @author     Jesper Veggerby <pear.nosey@veggerby.dk>
 * @copyright  Copyright (C) 2003, 2004 Jesper Veggerby Hansen
 * @license    http://www.gnu.org/copyleft/lesser.html  LGPL License 2.1
 * @version    Release: @package_version@
 * @link       http://pear.php.net/package/Image_Graph
 */
class Image_Graph_Fill_Image extends Image_Graph_Fill
{

    /**
     * The file name
     * @var stirng
     * @access private
     */
    var $_filename;

    /**
     * The GD Image resource
     * @var resource
     * @access private
     */
    var $_image;

    /**
     * Resize the image to the bounding box of the area to fill
     * @var bool
     * @access private
     */
    var $_resize = true;

    /**
     * Image_Graph_ImageFill [Constructor]
     *
     * @param string $filename The filename and path of the image to use for filling
     */
    function Image_Graph_Fill_Image($filename)
    {
        parent::Image_Graph_Fill();
        $this->_filename = $filename;
    }

    /**
     * Return the fillstyle
     *
     * @param (something) $ID (Add description)
     * @return int A GD fillstyle
     * @access private
     */
    function _getFillStyle($ID = false)
    {
        return $this->_filename;
    }

}

?>