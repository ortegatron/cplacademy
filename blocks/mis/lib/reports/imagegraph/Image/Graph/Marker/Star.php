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
 * @subpackage Marker
 * @author     Jesper Veggerby <pear.nosey@veggerby.dk>
 * @copyright  Copyright (C) 2003, 2004 Jesper Veggerby Hansen
 * @license    http://www.gnu.org/copyleft/lesser.html  LGPL License 2.1
 * @version    CVS: $Id: Star.php,v 1.2 2005/08/03 21:21:54 nosey Exp $
 * @link       http://pear.php.net/package/Image_Graph
 */

/**
 * Include file Image/Graph/Marker.php
 */
require_once dirname(__FILE__).'../../Marker.php';

/**
 * Data marker as a triangle.
 *
 * @category   Images
 * @package    Image_Graph
 * @subpackage Marker
 * @author     Jesper Veggerby <pear.nosey@veggerby.dk>
 * @copyright  Copyright (C) 2003, 2004 Jesper Veggerby Hansen
 * @license    http://www.gnu.org/copyleft/lesser.html  LGPL License 2.1
 * @version    Release: @package_version@
 * @link       http://pear.php.net/package/Image_Graph
 */
class Image_Graph_Marker_Star extends Image_Graph_Marker
{

    /**
     * Draw the marker on the canvas
     *
     * @param int $x The X (horizontal) position (in pixels) of the marker on
     *   the canvas
     * @param int $y The Y (vertical) position (in pixels) of the marker on the
     *   canvas
     * @param array $values The values representing the data the marker 'points'
     *   to
     * @access private
     */
    function _drawMarker($x, $y, $values = false)
    {
        $this->_getFillStyle();
        $this->_getLineStyle();
        
        $d = $this->_size / 5;
        $x = round($x);
        $y = round($y);
        
        $this->_canvas->addVertex(array('x' => $x, 'y' => $y - $this->_size));
        $this->_canvas->addVertex(array('x' => $x + round($d), 'y' => $y - round($d)));
        $this->_canvas->addVertex(array('x' => $x + $this->_size, 'y' => $y - round($d)));
        $this->_canvas->addVertex(array('x' => $x + round(2 * $d), 'y' => $y + round($d)));
        $this->_canvas->addVertex(array('x' => $x + round(3 * $d), 'y' => $y + $this->_size));
        $this->_canvas->addVertex(array('x' => $x, 'y' => $y + round(3 * $d)));
        $this->_canvas->addVertex(array('x' => $x - round(3 * $d), 'y' => $y + $this->_size));
        $this->_canvas->addVertex(array('x' => $x - round(2 * $d), 'y' => $y + round($d)));
        $this->_canvas->addVertex(array('x' => $x - $this->_size, 'y' => $y - round($d)));
		$this->_canvas->addVertex(array('x' => $x - round($d), 'y' => $y - round($d)));
        $this->_canvas->polygon(array('connect' => true));
        
        parent::_drawMarker($x, $y, $values);
    }

}

?>