<?php
use DBAL\XML\Data\Tree;
/*
 * ----------------------------------------------
 * CREATE DUMMY TREE TO QUERY
 * ----------------------------------------------
 */
$node = new Tree\Node('test');
$attr = array('test' => 'someValue',
              'attr' => 2 );
/*
 * Tree\Node Constructor Arguments
 * 1. Name
 * 2. Attributes
 * 3. Parent
 */
// CHILD 1, NO ATTRIBUTES
new Tree\Node( 'subnode', null , $node );
// CHILD 2
new Tree\Node( 'subnode', $attr, $node );
// CHILD 3, NO ATTRIBUTES
new Tree\Node( 'subnode', null , $node );
// CHILD 4
new Tree\Node( 'subnode', $attr, $node );
/*
 * ----------------------------------------------
 * EQUIVALENT XML
 * ----------------------------------------------
 * <test>
 *      <subnode></subnode>
 *      <subnode test="someValue" attr="2"></subnode>
 *      <subnode></subnode>
 *      <subnode test="someValue" attr="2"></subnode>
 * </test>
 * ----------------------------------------------
 * QUERY BUILDER CREATES VALID XPATH
 * SEARCH NODES WHERE 'subnode' ELEMENT
 * WHERE ATTRIBUTE 'test' = 'someValue'
 * ----------------------------------------------
 */
$query = new \DBAL\XML\Query();
$query->build()
      ->where('subnode')
      ->attribute('test', 'someValue');

// RETRIEVE XPATH
$xpath = (string)$query;
/*
 * EXECUTING XPATH QUERY
 * XPATH: subnode[@test="someValue"]
 */
$results = $node( $xpath );

var_dump( $results );
/* ----------------------------------------------
 * OUTPUT:
 * array() = ( 0 => (object)Tree\Node,      - CHILD 2
 *             1 => (object)Tree\Node )     - CHILD 4
 *            
 * ----------------------------------------------
 */