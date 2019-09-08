<?php
// $Id: xoopsstory.php 12329 2013-09-19 13:53:36Z skenow $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
// Author: Kazumi Ono (AKA onokazu)                                          //
// URL: http://www.myweb.ne.jp/, http://www.xoops.org/, http://jp.xoops.org/ //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //
/**
 * Old class for generating news items
 *
 * @copyright	http://www.xoops.org/ The XOOPS Project
 * @copyright	XOOPS_copyrights.txt
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package	core
 * @since	XOOPS
 * @author	http://www.xoops.org The XOOPS Project
 * @author	modified by UnderDog <underdog@impresscms.org>
 * @version	$Id: xoopsstory.php 12329 2013-09-19 13:53:36Z skenow $
 */



if (!defined('ICMS_ROOT_PATH')) {
    exit();
}
include_once ICMS_ROOT_PATH.'/class/xoopstopic.php';
include_once ICMS_ROOT_PATH.'/kernel/user.php';

class XoopsStory
{
    public $table;
    public $storyid;
    public $topicid;
    public $uid;
    public $title;
    public $hometext;
    public $bodytext='';
    public $counter;
    public $created;
    public $published;
    public $expired;
    public $hostname;
    public $nohtml=0;
    public $nosmiley=0;
    public $ihome=0;
    public $notifypub=0;
    public $type;
    public $approved;
    public $topicdisplay;
    public $topicalign;
    public $db;
    public $topicstable;
    public $comments;

    /**
     * Contstructor
     *
     * @param   int      $storyid
     **/
    public function Story($storyid=-1)
    {
        $this->db = icms_db_Factory::instance();
        $this->table = '';
        $this->topicstable = '';
        if (is_array($storyid)) {
            $this->makeStory($storyid);
        } elseif ($storyid != -1) {
            $this->getStory((int) ($storyid));
        }
    }

    /**
     * Sets current storyid
     *
     * @param   int      $value
     **/
    public function setStoryId($value)
    {
        $this->storyid = (int) ($value);
    }

    /**
     * Sets current topicid
     *
     * @param   int      $value
     **/
    public function setTopicId($value)
    {
        $this->topicid = (int) ($value);
    }

    /**
     * Sets current userid
     *
     * @param   int      $value
     **/
    public function setUid($value)
    {
        $this->uid = (int) ($value);
    }

    /**
     * Sets current title
     *
     * @param   string   $value
     **/
    public function setTitle($value)
    {
        $this->title = $value;
    }

    /**
     * Sets current hometext (intro text)
     *
     * @param   string   $value
     **/
    public function setHometext($value)
    {
        $this->hometext = $value;
    }

    /**
     * Sets current body (body text)
     *
     * @param   string   $value
     **/
    public function setBodytext($value)
    {
        $this->bodytext = $value;
    }

    /**
     * Sets current date published
     *
     * @param   int      $value
     **/
    public function setPublished($value)
    {
        $this->published = (int) ($value);
    }

    /**
     * Sets current date expired
     *
     * @param   int      $value
     **/
    public function setExpired($value)
    {
        $this->expired = (int) ($value);
    }

    /**
     * Sets current hostname
     *
     * @param   string      $value
     **/
    public function setHostname($value)
    {
        $this->hostname = $value;
    }

    /**
     * Sets value of nohtml
     *
     * @param   int      $value
     **/
    public function setNohtml($value=0)
    {
        $this->nohtml = $value;
    }

    /**
     * Sets value of nosmiley
     *
     * @param   int      $value
     **/
    public function setNosmiley($value=0)
    {
        $this->nosmiley = $value;
    }

    /**
     * Sets current value of ihome
     *
     * @param   string      $value
     **/
    public function setIhome($value)
    {
        $this->ihome = $value;
    }

    /**
     * Sets current value of notifypub
     *
     * @param   string      $value
     **/
    public function setNotifyPub($value)
    {
        $this->notifypub = $value;
    }

    /**
     * Sets type
     *
     * @param   string      $value
     **/
    public function setType($value)
    {
        $this->type = $value;
    }

    /**
     * Sets current value of approved
     *
     * @param   int        $value
     **/
    public function setApproved($value)
    {
        $this->approved = (int) ($value);
    }

    /**
     * Sets current value of topicdisplay
     *
     * @param   string      $value
     **/
    public function setTopicdisplay($value)
    {
        $this->topicdisplay = $value;
    }

    /**
     * Sets current value of topicalign
     *
     * @param   string      $value
     **/
    public function setTopicalign($value)
    {
        $this->topicalign = $value;
    }

    /**
     * Sets current value of comments
     *
     * @param   int      $value
     **/
    public function setComments($value)
    {
        $this->comments = (int) ($value);
    }

    /**
     * Stores the story. Don't set to published when not approved
     *
     * @param   bool      $approved
     **/
    public function store($approved=false)
    {
        //$newpost = 0;
        $myts = icms_core_Textsanitizer::getInstance();
        $title = $myts->censorString($this->title);
        $hometext = $myts->censorString($this->hometext);
        $bodytext = $myts->censorString($this->bodytext);
        $title = $myts->addSlashes($title);
        $hometext = $myts->displayTarea($hometext);
        $bodytext = $myts->displayTarea($bodytext);
        if (!isset($this->nohtml) || $this->nohtml != 1) {
            $this->nohtml = 0;
        }
        if (!isset($this->nosmiley) || $this->nosmiley != 1) {
            $this->nosmiley = 0;
        }
        if (!isset($this->notifypub) || $this->notifypub != 1) {
            $this->notifypub = 0;
        }
        if (!isset($this->topicdisplay) || $this->topicdisplay != 0) {
            $this->topicdisplay = 1;
        }
        $expired = !empty($this->expired) ? $this->expired : 0;
        if (!isset($this->storyid)) {
            //$newpost = 1;
            $newstoryid = $this->db->genId($this->table . '_storyid_seq');
            $created = time();
            $published = ($this->approved) ? $this->published : 0;

            $sql = sprintf("INSERT INTO %s (storyid, uid, title, created, published, expired, hostname, nohtml, nosmiley, hometext, bodytext, counter, topicid, ihome, notifypub, story_type, topicdisplay, topicalign, comments) VALUES ('%u', '%u', '%s', '%u', '%u', '%u', '%s', '%u', '%u', '%s', '%s', '%u', '%u', '%u', '%u', '%s', '%u', '%s', '%u')", $this->table, (int) ($newstoryid), (int) ($this->uid), $title, (int) ($created), (int) ($published), (int) ($expired), $this->hostname, (int) ($this->nohtml), (int) ($this->nosmiley), $hometext, $bodytext, 0, (int) ($this->topicid), (int) ($this->ihome), (int) ($this->notifypub), $this->type, (int) ($this->topicdisplay), $this->topicalign, (int) ($this->comments));
        } else {
            if ($this->approved) {
                $sql = sprintf("UPDATE %s SET title = '%s', published = '%u', expired = '%u', nohtml = '%u', nosmiley = '%u', hometext = '%s', bodytext = '%s', topicid = '%u', ihome = '%u', topicdisplay = '%u', topicalign = '%s', comments = '%u' WHERE storyid = '%u'", $this->table, $title, (int) ($this->published), (int) ($expired), (int) ($this->nohtml), (int) ($this->nosmiley), $hometext, $bodytext, (int) ($this->topicid), (int) ($this->ihome), (int) ($this->topicdisplay), $this->topicalign, (int) ($this->comments), (int) ($this->storyid));
            } else {
                $sql = sprintf("UPDATE %s SET title = '%s', expired = '%u', nohtml = '%u', nosmiley = '%u', hometext = '%s', bodytext = '%s', topicid = '%u', ihome = '%u', topicdisplay = '%u', topicalign = '%s', comments = '%u' WHERE storyid = '%u'", $this->table, $title, (int) ($expired), (int) ($this->nohtml), (int) ($this->nosmiley), $hometext, $bodytext, (int) ($this->topicid), (int) ($this->ihome), (int) ($this->topicdisplay), (int) ($this->topicalign), (int) ($this->comments), (int) ($this->storyid));
            }
            $newstoryid = $this->storyid;
        }
        if (!$result = $this->db->query($sql)) {
            return false;
        }
        if (empty($newstoryid)) {
            $newstoryid = $this->db->getInsertId();
            $this->storyid = $newstoryid;
        }
        return $newstoryid;
    }

    /**
     * Gets story by ID
     *
     * @param   int      $storyid
     **/
    public function getStory($storyid)
    {
        $storyid = (int) ($storyid);
        $sql = 'SELECT * FROM ' . $this->table . " WHERE storyid='" . $storyid . "'";
        $array = $this->db->fetchArray($this->db->query($sql));
        $this->makeStory($array);
    }

    /**
     * Makes the story
     *
     * @param   array      $array
     **/
    public function makeStory($array)
    {
        foreach ($array as $key=>$value) {
            $this->$key = $value;
        }
    }

    /**
     * Deletes the story by ID
     *
     * @return   bool
     **/
    public function delete()
    {
        $sql = sprintf("DELETE FROM %s WHERE storyid = '%u'", $this->table, (int) ($this->storyid));
        if (!$result = $this->db->query($sql)) {
            return false;
        }
        return true;
    }

    /**
     * Updates the counter
     *
     * @param   bool
     **/
    public function updateCounter()
    {
        $sql = sprintf("UPDATE %s SET counter = counter+1 WHERE storyid = '%u'", $this->table, (int) ($this->storyid));
        if (!$result = $this->db->queryF($sql)) {
            return false;
        }
        return true;
    }

    /**
     * Updates the number of comments
     *
     * @param   bool
     **/
    public function updateComments($total)
    {
        $sql = sprintf("UPDATE %s SET comments = '%u' WHERE storyid = '%u'", $this->table, (int) ($total), (int) ($this->storyid));
        if (!$result = $this->db->queryF($sql)) {
            return false;
        }
        return true;
    }

    /**
     * Returns the current topicid
     *
     * @return   int
     **/
    public function topicid()
    {
        return $this->topicid;
    }

    /**
     * Returns the current topic (@link XoopsTopic) object
     *
     * @param   object
     **/
    public function topic()
    {
        return new XoopsTopic($this->topicstable, $this->topicid);
    }

    public function uid()
    {
        return $this->uid;
    }

    /**
     * Returns the current username from (@link icms_member_user_Object)
     *
     * @return   string
     **/
    public function uname()
    {
        return icms_member_user_Object::getUnameFromId($this->uid);
    }

    /**
     * Returns the title in a certain format
     *
     * @param    string    $format
     * @return   string    $title
     **/
    public function title($format='Show')
    {
        $myts = icms_core_Textsanitizer::getInstance();
        $smiley = 1;
        if ($this->nosmiley()) {
            $smiley = 0;
        }
        switch ($format) {
            case 'Show':
                $title = $myts->htmlSpecialChars($this->title, $smiley);
                break;
            case 'Edit':
                $title = $myts->htmlSpecialChars($this->title);
                break;
            case 'Preview':
                $title = $myts->makeTboxData4Preview($this->title, $smiley);
                break;
            case 'InForm':
                $title = $myts->makeTboxData4PreviewInForm($this->title);
                break;
        }
        return $title;
    }

    /**
     * Returns the hometext in a certain format
     *
     * @param    string    $format
     * @return   string    $hometext
     **/
    public function hometext($format='Show')
    {
        $myts = icms_core_Textsanitizer::getInstance();
        $html = 1;
        $smiley = 1;
        $xcodes = 1;
        if ($this->nohtml()) {
            $html = 0;
        }
        if ($this->nosmiley()) {
            $smiley = 0;
        }
        switch ($format) {
            case 'Show':
                $hometext = $myts->displayTarea($this->hometext, $html, $smiley, $xcodes);
                break;
            case 'Edit':
                $hometext = $myts->displayTarea($this->hometext);
                break;
            case 'Preview':
                $hometext = $myts->previewTarea($this->hometext, $html, $smiley, $xcodes);
                break;
            case 'InForm':
                $hometext = $myts->makeTareaData4PreviewInForm($this->hometext);
                break;
        }
        return $hometext;
    }

    /**
     * Returns the bodytext in a certain format
     *
     * @param    string    $format
     * @return   string    $bodytext
     **/
    public function bodytext($format='Show')
    {
        $myts = icms_core_Textsanitizer::getInstance();
        $html = 1;
        $smiley = 1;
        $xcodes = 1;
        if ($this->nohtml()) {
            $html = 0;
        }
        if ($this->nosmiley()) {
            $smiley = 0;
        }
        switch ($format) {
            case 'Show':
                $bodytext = $myts->displayTarea($this->bodytext, $html, $smiley, $xcodes);
                break;
            case 'Edit':
                $bodytext = $myts-previewTarea($this->bodytext);
                break;
            case 'Preview':
                $bodytext = $myts->previewTarea($this->bodytext, $html, $smiley, $xcodes);
                break;
            case 'InForm':
                $bodytext = $myts->makeTareaData4PreviewInForm($this->bodytext);
                break;
        }
        return $bodytext;
    }

    /**
     * Returns the counter
     *
     * @return   int
     **/
    public function counter()
    {
        return $this->counter;
    }

    /**
     * Returns date created
     *
     * @return   int
     **/
    public function created()
    {
        return $this->created;
    }

    /**
     * Returns date published
     *
     * @return   int
     **/
    public function published()
    {
        return $this->published;
    }

    /**
     * Returns date expired
     *
     * @return   int
     **/
    public function expired()
    {
        return $this->expired;
    }

    /**
     * Returns hostname
     *
     * @return   string
     **/
    public function hostname()
    {
        return $this->hostname;
    }

    /**
     * Returns storyid
     *
     * @return   int
     **/
    public function storyid()
    {
        return $this->storyid;
    }

    /**
     * Returns value for nohtml
     *
     * @return   int
     **/
    public function nohtml()
    {
        return $this->nohtml;
    }

    /**
     * Returns value for nosmiley
     *
     * @return   int
     **/
    public function nosmiley()
    {
        return $this->nosmiley;
    }

    /**
     * Returns value for notifypub
     *
     * @return   int
     **/
    public function notifypub()
    {
        return $this->notifypub;
    }

    /**
     * Returns the type
     *
     * @return   string
     **/
    public function type()
    {
        return $this->type;
    }

    /**
     * Returns value for ihome
     *
     * @return   string
     **/
    public function ihome()
    {
        return $this->ihome;
    }

    /**
     * Returns value for topicdisplay
     *
     * @return   string
     **/
    public function topicdisplay()
    {
        return $this->topicdisplay;
    }

    /**
     * Returns value for topicalign
     *
     * @param    bool      $astext   Align the topic as text
     * @return   string
     **/
    public function topicalign($astext=true)
    {
        if ($astext) {
            if ($this->topicalign == 'R') {
                $ret = 'right';
            } else {
                $ret = 'left';
            }
            return $ret;
        }
        return $this->topicalign;
    }

    /**
     * Returns the number of comments
     *
     * @return   int
     **/
    public function comments()
    {
        return $this->comments;
    }
}
