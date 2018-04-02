<?php
/**
 * includeq Smarty compiler plug-in
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
 * Quick include template plug-in
 *
 * Like {@link smarty_compiler_foreachq() foreachq}, this plug-in has been written to provide
 * a faster version of an already existing Smarty function. <var>includeq</var> can be used
 * as a replacement for the Smarty
 * {@link http://smarty.php.net/manual/en/language.function.include.php include} function as long
 * as you are aware of the differences between them.
 *
 * Normally, when you include a template, Smarty does the following:
 * - Backup all your template variables in an array
 * - Include the template you specified
 * - Restore the template variables from the previously created backup array
 *
 * The advantage of this method is that it makes the main template variables <i>safe</i>: if your
 * main template uses a variable called <var>$stuff</var> and the included template modifies it
 * value, the main template will recover the original value automatically.
 *
 * While this can be useful in some cases (for example, when you include templates you have absolutely
 * no control over), some may consider this a limitation and it has the disadvantage of slowing down
 * the inclusion mechanism a lot.
 *
 * <var>includeq</var> fixes that: the code it generates doesn't contain the variables backup/recovery
 * mechanism and thus makes templates inclusion faster. Note that however, this new behavior may
 * create problems in some cases (but you can prevent them most of the times, for example by always
 * using a <var>tmp_</var> prefix for the variables you create in included templates looping sections).
 *
 * @deprecated
 */
class Smarty_Compiler_Includeq extends Smarty_Internal_Compile_Include
{

}

?>
