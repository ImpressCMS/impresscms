<?php
/**
 * foreachq Smarty compiler plug-in
 *
 * See the enclosed file LICENSE for licensing information.
 * If you did not receive this file, get it at http://www.fsf.org/copyleft/gpl.html
 *
 * @copyright   The XOOPS project http://www.xoops.org/
 * @license     http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author		Skalpa Keo <skalpa@xoops.org>
 * @package		xos_opal
 * @subpackage	xos_opal_Smarty
 * @since       2.0.14
 */

/**
 * Quick foreach template plug-in
 *
 * This plug-in works as a direct replacement for the original Smarty
 * {@link http://smarty.php.net/manual/en/language.function.foreach.php foreach} function.
 *
 * The difference with <var>foreach</var> is minimal in terms of functionality, but can boost your templates
 * a lot: foreach duplicates the content of the variable that is iterated, to ensure non-array
 * variables can be specified freely. This implementation does not do that, but as a consequence
 * requires that the variable you specify in the <var>from</var> parameter is an array or
 * (when using PHP5) an object. Check the difference between the code generated by foreach
 * and foreachq to understand completely.
 *
 * <b>Note:</b> to use foreachq, only the opening tag has to be replaced. The closing tab still
 * remains {/foreach}
 *
 * <code>
 * // Iterate, slow version
 * {foreach from=$array item=elt}
 *   {$elt}
 * {/foreach}
 * // Iterate, fast version
 * {foreachq from=$array item=elt}
 *   {$elt}
 * {/foreach}
 * </code>
 *
 * @deprecated
 */
class Smarty_Compiler_Foreachq extends Smarty_Internal_Compile_Foreach
{

}

?>