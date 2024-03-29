<?php
/**
 * Usage example for Image_Graph.
 * 
 * Main purpose: 
 * Demonstrate SWF canvas
 * 
 * Other: 
 * None specific
 * 
 * $Id: driver_swf01.php,v 1.3 2005/08/03 21:21:53 nosey Exp $
 * 
 * @package Image_Graph
 * @author Jesper Veggerby <pear.nosey@veggerby.dk>
 */

require_once dirname(__FILE__).'../../Graph.php';
require_once 'Image/Canvas.php';

$Canvas =& Image_Canvas::factory('swf', array('width' => 600, 'height' => 400));


// create the graph
$Graph =& Image_Graph::factory('graph', $Canvas); 
// add a TrueType font
$Font =& $Graph->addNew('font', 'Verdana');
// set the font size to 11 pixels
$Font->setSize(11);

$Graph->add(
    Image_Graph::vertical(
        Image_Graph::factory('title', array('Simple Area Chart Sample', &$Font)),        
        Image_Graph::vertical(
            $Plotarea = Image_Graph::factory('plotarea'),
            $Legend = Image_Graph::factory('legend'),
            90
        ),
        5
    )
);   
$Legend->setPlotarea($Plotarea);        
	
// create the dataset
$Dataset =& Image_Graph::factory('random', array(10, 2, 15, true));
// create the 1st plot as smoothed area chart using the 1st dataset
    
$Plot =& $Plotarea->addNew('area', $Dataset);

// set a line color
$Plot->setLineColor('gray');

// set a standard fill style
$Plot->setFillColor('blue@0.2');        

// output the Graph
$Graph->done();
?>
